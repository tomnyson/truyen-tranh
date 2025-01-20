<!-- components/RecentPosts.vue -->
<template>
  <div class="col-lg-6">
    <div
      class="card-list-posts card-list-posts-small wow animate__animated animate__fadeIn"
      v-for="item in popularPosts"
      :key="item.id"
    >
      <div class="card-image hover-up">
        <NuxtLink :to="`/blog/${item.id}`">
          <img :src="`${getMedia(apiBaseUrl, item.image)}`" :alt="item.title" />
        </NuxtLink>
      </div>
      <div class="card-info">
        <NuxtLink :to="`/blogs/${item.id}`">
          <h5 class="mb-15 color-white">{{ item.title || 'no title' }}</h5>
        </NuxtLink>
        <p class="color-gray-500 description">{{ truncatedContent(item.content, 300)}}</p>
        <div class="row mt-20">
          <div class="col-12">
            <span class="calendar-icon color-gray-700 text-sm mr-25">{{ formatDate(item.created_at) }}</span>
            <span class="eye-icon color-gray-700 text-sm mr-25">lượt xem: {{ item.views_count }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import {formatDate, getMedia, truncatedContent} from '@/utils/index'
const config = useRuntimeConfig();
const apiBaseUrl = config.public.apiBaseUrl;
interface Tag {
  id: number
  name: string
}

interface Post {
  id: number
  title: string
  content: string
  created_at: string,
  views_count: number,
  image: string
  tags: Tag[]
}

const props = defineProps<{
  posts: Post[]
}>()

const popularPosts = computed(() => {
  // Safely handle cases where props.posts might be undefined or null
  return (props.posts || []).slice(0, 5);
});
const image_url = process.env.VUE_APP_STATIC_URL
</script>

<style scoped>
.description {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 10px;
}
</style>