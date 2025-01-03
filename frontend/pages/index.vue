<!-- pages/index.vue -->
<script setup lang="ts">
definePageMeta({ layout: 'default', auth: false, title: 'Trang chủ' })
import BannerHome2 from '@/components/BannerHome2.vue'
import TrendingTopics from '@/components/TrendingTopics.vue'
import RecentPosts from '@/components/RecentPosts.vue'
import PopularPosts from '~/components/PopularPosts.vue';
import Tags from '~/components/Tags.vue';
import { toast } from 'vue3-toastify';

//refs
const activeTab = ref('day');
const postsFilter = ref(null);
const pendingPostFilterStatus = ref(false);
const errorFilter = ref(null);
// fetch api
const { data: posts, pending, error } = await useFetch('/api/posts')
const { data: categories, status: categoriesStatus, error: categoriesError } = await useFetch('/api/categories')
const { data: tags, status: tagsStatus, error: tagsError } = await useFetch('/api/tags')


const postsPined = posts.value?.filter((post: any) => post.is_pin === 1);
const postsRecent = posts.value?.filter((post: any) => post.is_pin !== 1);
// toast.success('Operation successful!');
// watch activeTab and refetch data

const fetchPostsFilter = async () => {
  try {
    pendingPostFilterStatus.value = true;
    const queryString = {
      viewTime: activeTab.value,
      orderBy: 'views_count',
      limit: 10,
    }
    const { data, error } = await useFetch(`/api/posts?viewTime=${activeTab.value}`);
    postsFilter.value = data.value;
    errorFilter.value = error.value;
  } catch (e) {
    console.error('Error fetching postsFilter:', e);
  } finally {
    pendingPostFilterStatus.value = false;
  }
};

watch(activeTab, fetchPostsFilter, { immediate: true });


const showToast = () => {
  toast.success('Operation successful!');
}


const setActiveTab = (tab: string) => {
  activeTab.value = tab;
};

</script>

<template>
  <main class="main">
    <BannerHome2 :sliders="postsPined" />
    <div class="cover-home3">
      <div class="container">
        <div class="row">
          <div class="col-xl-1"></div>
          <div class="col-xl-10 col-lg-12">
            <div class="mt-70 mb-50">
              <h2 class="color-linear mb-10 wow animate__animated animate__fadeInUp">Bài viết gần đây</h2>
              <p class="text-lg color-gray-500 wow animate__animated animate__fadeInUp">Khám phá những bài viết nổi bật
                nhất trong mọi chủ đề</p>
              <div class="row mt-70">
                <RecentPosts :posts="postsRecent" />
              </div>
            </div>
            <h2 class="color-linear d-inline-block mb-10 wow animate__animated animate__fadeInUp">Thẻ phổ biến</h2>
            <p class="text-lg color-gray-500 wow animate__animated animate__fadeInUp">Từ khóa được tìm kiếm nhiều nhất
            </p>
            <div class="row mt-70 mb-50">
              <Tags :tags="tags" />
            </div>
            <div class="row mt-70">
              <div class="col-lg-12">
                <h2 class="color-linear d-inline-block mb-10 wow animate__animated animate__fadeInUp">Bài viết phổ biến
                </h2>
                <p class="text-lg color-gray-500 wow animate__animated animate__fadeInUp">Đừng bỏ lỡ những xu hướng mới
                  nhất</p>
                <div class="box-list-posts mt-50">
                  <div class="col-lg-12 mb-50">
                    <span class="wow animate__animated animate__fadeInUp" data-wow-delay=".0s">
                      <button class="btn btn-border-linear btn-filter hover-up"
                        :class="{ active: activeTab === 'day' }" @click="setActiveTab('day')">
                        Nổi bật trong ngày
                      </button>
                    </span>
                    <span class="wow animate__animated animate__fadeInUp" data-wow-delay=".0s">
                      <button class="btn btn-border-linear btn-filter hover-up"
                        :class="{ active: activeTab === 'week' }" @click="setActiveTab('week')">
                        Nổi bật trong tuần
                      </button>
                    </span>
                    <span class="wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                      <button class="btn btn-border-linear btn-filter hover-up"
                        :class="{ active: activeTab === 'month' }" @click="setActiveTab('month')">
                        Nổi bật trong tháng
                      </button>
                    </span>
                    <span class="btn btn-border-linear btn-filter hover-up"
                      :class="{ active: activeTab === 'all' }" @click="setActiveTab('all')">
                      Nổi bật trong tất cả
                    </span>
                  </div>
                  <div class="row">
                    <PopularPosts :posts="postsFilter" />
                  </div>
                </div>
                <div class="text-start mb-80"><a
                    class="btn btn-linear btn-load-more wow animate__animated animate__zoomIn">Show More Posts<i
                      class="fi-rr-arrow-small-right"></i></a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>