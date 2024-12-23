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
          <img :src="`${IMAGE_URL}/storage/${item.image}`" :alt="item.title" />
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
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import {formatDate, IMAGE_URL, truncatedContent} from '@/utils/index'

interface Tag {
  id: number
  name: string
}

interface Post {
  id: number
  title: string
  content: string
  created_at: string
  image: string
  tags: Tag[]
}

const props = defineProps<{
  posts: Post[]
}>()  

const popularPosts = computed(() => props.posts.slice(0, 10))
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