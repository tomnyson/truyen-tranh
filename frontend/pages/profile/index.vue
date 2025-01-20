<script setup lang="ts">
import { ref, computed } from 'vue'
import ProfilePost from '~/components/ProfilePosts.vue';
import { toast } from 'vue3-toastify';
import Loading from '~/components/Loading.vue';
import { getMedia } from '~/utils';
const config = useRuntimeConfig();
const apiBaseUrl = config.public.apiBaseUrl;

definePageMeta({ layout: 'default', auth: true, title: 'Thông tin cá nhân' });

const route = useRoute();
const { userId } = route.query;
// Reactive state for active tab
const activeTab = ref('news');

// Fetch posts and author data

const { status, token, data: session, signOut } = useAuth();
const { data: posts, pending, error } = await useFetch(`/api/posts?userId=${session.value?.user.id}`);
const { data: author, pending: userPending } = await useFetch(`/api/authors/${userId}`);
// Function to set active tab
const setActiveTab = (tab: string) => {
  activeTab.value = tab;
};

console.log('session.value',session.value)
const profileData = ref({
  avatar: getMedia(apiBaseUrl, session.value?.user.avatar) ||'',
  introduction: session.value?.user.intro || '',
  name: session.value?.user.name || '',
  email: session.value?.user.email || '',

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
    profileData.value.avatar = file;
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

const saveProfile = async () => {
  try {
    // Initialize FormData
    const formData = new FormData();

    // Add the fields to FormData
    formData.append('name', profileData.value.name);
    formData.append('social', JSON.stringify(profileData.value.socialLinks)); // Stringify JSON
    formData.append('introduction', profileData.value.introduction);

    // Add the avatar file if available
    if (profileData.value.avatar) {
      formData.append('avatar', profileData.value.avatar);
    }

    // Make the fetch request
    const response = await fetch(`${apiBaseUrl}/api/profile`, {
      method: 'POST',
      headers: {
        Authorization: token.value ? `${token.value}` : undefined, // Include Authorization header
      },
      body: formData, // Use FormData as the body
    });

    if (!response.ok) {
      console.error('Error response:', response);
      throw new Error('Failed to save profile');
    }

    const result = await response.json();
    console.log('Profile saved:', result);

    toast.success('Thông tin cá nhân đã được lưu thành công!');
  } catch (error) {
    console.error('Error saving profile:', error);
    toast.error('Lưu thông tin thất bại, vui lòng thử lại.');
  }
};
</script>

<template>
  <Loading :loading="pending" />
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
                Quản lý bài viết và thông tin cá nhân của bạn
              </p>
            </div>
            <div class="row text-center filter-nav">
              <div class="col-lg-12">
                <span class="wow animate__animated animate__fadeInUp" data-wow-delay=".0s">
                  <button class="btn btn-border-linear btn-filter hover-up" :class="{ active: activeTab === 'news' }"
                    @click="setActiveTab('news')">
                    Danh sách bài viết
                  </button>
                </span>
                <span class="wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                  <button class="btn btn-border-linear btn-filter hover-up" :class="{ active: activeTab === 'profile' }"
                    @click="setActiveTab('profile')">
                    Thông tin cá nhân
                  </button>
                </span>
                <span class="btn btn-border-linear btn-filter hover-up" :class="{ active: activeTab === 'new-post' }"
                  @click="setActiveTab('new-post')">
                  Tạo bài viết mới
                </span>
              </div>
            </div>
            <!-- Content for each tab -->
            <div class="mt-70">
              <div v-if="activeTab === 'news'">
                <div class="row mt-70">
                  <ProfilePost :posts="posts" />
                </div>
              </div>
              <div v-if="activeTab === 'profile'">
                <h4 class="color-linear d-inline-block mb-20 wow animate__animated animate__fadeInUp">
                  Cập nhật thông tin cá nhân
                </h4>

                <!-- Avatar Upload -->
                <div class="mb-4">
                  <label class="form-label">Avatar:</label>
                  <div class="avatar-preview">
                    <img :src="avatarPreview || profileData?.avatar || '/default-avatar.png'" alt="Avatar"
                      class="avatar-image" />
                    <label for="avatar" class="icon-upload">
                      <Icon name="ep:circle-plus" style="color: #5a5a5a; cursor: pointer;" size="30" />
                    </label>
                  </div>
                  <!-- Hidden input -->
                  <input type="file" id="avatar" @change="handleAvatarUpload" class="hidden-input" />
                </div>
                <div class="mb-4">
                  <label for="name" class="form-label">Tên:</label>
                  <input autocomplete="off" type="text" id="name" v-model="profileData.name" class="form-control"
                    rows="3" placeholder="Nhập tên của bạn.">
                </div>
                <div class="mb-4">
                  <label for="name" class="form-label">Email:</label>
                  <input  autocomplete="off" readonly type="text" id="email" v-model="profileData.email" class="form-control" rows="3"
                    placeholder="Nhập email của bạn.">
                </div>
                <!-- Introduction Text -->
                <div class="mb-4">
                  <label for="introduction" class="form-label">Giới thiệu:</label>
                  <textarea id="introduction" v-model="profileData.introduction" class="form-control" rows="3"
                    placeholder="Nhập giới thiệu cá nhân..."
                    maxlength="255"
                    ></textarea>
                </div>

                <!-- Social Media Links -->
                <div class="mb-4">
                  <label class="form-label">Liên kết mạng xã hội: </label>
                  <div v-for="(link, index) in profileData.socialLinks" :key="index" class="mb-3">
                    <div class="d-flex align-items-center">
                      <!-- Social Media Icon -->
                      <select v-model="link.icon" class="form-select me-2" style="width: 150px;">
                        <option disabled value="">Chọn biểu tượng</option>
                        <option v-for="icon in socialMediaIcons" :key="icon.value" :value="icon.value">
                          {{ icon.label }}
                        </option>
                      </select>

                      <!-- Social Media URL -->
                      <input type="text" v-model="link.url" placeholder="Nhập URL" class="form-control me-2" />

                      <!-- Remove Button -->
                      <Icon @click="removeSocialLink(index)" name="ep:remove" style="color: red" size="30" />
                    </div>
                  </div>
                  <Icon @click="addSocialLink" name="ep:circle-plus" style="color: #fff" size="30" />
                </div>

                <!-- Save Button -->
                <button @click="saveProfile" class="btn btn-success">Lưu thông tin</button>
              </div>
            </div>
            <div v-if="activeTab === 'new-post'">
              <p>Comming soon</p>
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

.avatar-preview {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

/* Avatar Image */
.avatar-image {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 10px;
  border: 2px solid #ddd;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Hidden Input */
.hidden-input {
  display: none;
}

.form-control {
  padding: 10px
}
</style>
