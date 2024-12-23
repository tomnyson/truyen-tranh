<script setup lang="ts">
import { ref, computed } from 'vue';
import BannerHome2 from '@/components/BannerHome2.vue';
import TrendingTopics from '@/components/TrendingTopics.vue';
import RecentPosts from '@/components/RecentPosts.vue';
import PopularPosts from '~/components/PopularPosts.vue';
import { toast } from 'vue3-toastify';

definePageMeta({ layout: 'default', auth: true, title: 'Thông tin cá nhân' });

const route = useRoute();
const { userId } = route.query;

// Reactive state for active tab
const activeTab = ref('news');

// Fetch posts and author data
const { data: posts, pending, error } = await useFetch(`/api/posts?userId=${userId}`);
const { data: author, pending: userPending } = await useFetch(`/api/authors/${userId}`);
console.log(author.value);

// Function to set active tab
const setActiveTab = (tab: string) => {
  activeTab.value = tab;
};


const profileData = ref({
  avatar: '',
  introduction: '',
  socialLinks: [
    { icon: '', url: '' },
  ],
});

// Avatar preview
const avatarPreview = ref<string | null>(null);

const socialMediaIcons = ref([
  { label: 'Facebook', value: 'facebook' },
  { label: 'Twitter', value: 'twitter' },
  { label: 'Instagram', value: 'instagram' },
  { label: 'LinkedIn', value: 'linkedin' },
  { label: 'YouTube', value: 'youtube' },
]);

// Handle avatar upload
const handleAvatarUpload = (event: Event) => {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      avatarPreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
    profileData.value.avatar = file; // Save the file for uploading
  }
};

// Add a new social media link
const addSocialLink = () => {
  profileData.value.socialLinks.push({ icon: '', url: '' });
};

// Remove a social media link
const removeSocialLink = (index: number) => {
  profileData.value.socialLinks.splice(index, 1);
};

// Save profile data
const saveProfile = async () => {
  try {
    const formData = new FormData();
    if (profileData.value.avatar) {
      formData.append('avatar', profileData.value.avatar); // Add avatar file
    }
    formData.append('introduction', profileData.value.introduction);
    formData.append('socialLinks', JSON.stringify(profileData.value.socialLinks));

    const response = await fetch('/api/profile', {
      method: 'POST',
      body: formData,
    });

    if (!response.ok) {
      throw new Error('Failed to save profile');
    }

    toast.success('Thông tin cá nhân đã được lưu thành công!');
  } catch (error) {
    console.error('Error saving profile:', error);
    toast.error('Lưu thông tin thất bại, vui lòng thử lại.');
  }
};
</script>

<template>
  <main class="main">
    <div class="cover-home1">
      <div class="container">
        <div class="row">
          <div class="col-xl-1"></div>
          <div class="col-xl-10 col-lg-12">
            <div class="text-center mt-70 mb-50">
              <h2 class="color-linear d-inline-block mb-20 wow animate__animated animate__fadeInUp">
                Bài viết của tôi
              </h2>
              <p class="text-lg color-gray-500 wow animate__animated animate__fadeInUp">
                The convention is the main event of the year for professionals in
                <br class="d-none d-lg-block" />the world of design and architecture.
              </p>
            </div>
            <div class="row text-center filter-nav">
              <div class="col-lg-12">
                <span class="wow animate__animated animate__fadeInUp" data-wow-delay=".0s">
                  <button
                    class="btn btn-border-linear btn-filter hover-up"
                    :class="{ active: activeTab === 'news' }"
                    @click="setActiveTab('news')"
                  >
                    Danh sách bài viết
                  </button>
                </span>
                <span class="wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                  <button
                    class="btn btn-border-linear btn-filter hover-up"
                    :class="{ active: activeTab === 'profile' }"
                    @click="setActiveTab('profile')"
                  >
                    Thông tin cá nhân
                  </button>
                </span>
                <span
                  class="btn btn-border-linear btn-filter hover-up"
                  :class="{ active: activeTab === 'new-post' }"
                  @click="setActiveTab('new-post')"
                >
                  Tạo bài viết mới
                </span>
              </div>
            </div>

            <!-- Content for each tab -->
            <div class="mt-70">
              <div v-if="activeTab === 'news'">
                <div class="project">
                  <h1>Danh sách bài viết</h1>
                  <div v-for="post in posts" :key="post.id" class="card-style-1 hover-up mb-30">
                    <div class="card-image">
                      <a class="link-post" :href="`/post/${post.id}`">
                        <img :src="post.coverImage" :alt="post.title" />
                        <div class="card-info card-bg-2">
                          <div class="info-bottom mb-15">
                            <h3 class="color-white mb-10">{{ post.title }}</h3>
                            <p class="color-gray-500 text-sm">{{ post.content }}</p>
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="activeTab === 'profile'">
                <h4 class="color-linear d-inline-block mb-20 wow animate__animated animate__fadeInUp">Cập nhật tin cá nhân</h4>

                <!-- Avatar Upload -->
                <div class="mb-4">
                  <label for="avatar" class="form-label">Avatar:</label>
                  <div class="avatar-preview">
                    <img :src="avatarPreview || author?.avatar" alt="Avatar" class="avatar-image" />
                  </div>
                  <input type="file" id="avatar" @change="handleAvatarUpload" />
                </div>

                <!-- Introduction Text -->
                <div class="mb-4">
                  <label for="introduction" class="form-label">Giới thiệu:</label>
                  <textarea
                    id="introduction"
                    v-model="profileData.introduction"
                    class="form-control"
                    rows="3"
                    placeholder="Nhập giới thiệu cá nhân..."
                  ></textarea>
                </div>

                <!-- Social Media Links -->
                <div class="mb-4">
                  <label class="form-label">Liên kết mạng xã hội:</label>
                  <div v-for="(link, index) in profileData.socialLinks" :key="index" class="mb-3">
                    <div class="d-flex align-items-center">
                      <!-- Social Media Icon -->
                      <select v-model="link.icon" class="form-select me-2" style="width: 150px;">
                        <option disabled value="">Chọn biểu tượng</option>
                        <option
                          v-for="icon in socialMediaIcons"
                          :key="icon.value"
                          :value="icon.value"
                        >
                          {{ icon.label }}
                        </option>
                      </select>

                      <!-- Social Media URL -->
                      <input
                        type="text"
                        v-model="link.url"
                        placeholder="Nhập URL"
                        class="form-control me-2"
                      />

                      <!-- Remove Button -->
                      <button @click="removeSocialLink(index)" class="btn btn-sm btn-danger">
                        Xóa
                      </button>
                    </div>
                  </div>
                  <button @click="addSocialLink" class="btn btn-sm btn-primary mt-2">
                    Thêm liên kết
                  </button>
                </div>

                <!-- Save Button -->
                <button @click="saveProfile" class="btn btn-success">Lưu thông tin</button>
              </div>
              <div v-if="activeTab === 'new-post'">
                <h1>Thêm bài viết mới</h1>
                <p>Form content goes here...</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<style>
.btn-filter.active {
  background-color: #5a5a5a;
  color: white;
}
.avatar-image {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  margin-top: 10px;
}

.d-flex {
  display: flex;
}

.me-2 {
  margin-right: 8px;
}

.mb-3 {
  margin-bottom: 16px;
}
</style>
