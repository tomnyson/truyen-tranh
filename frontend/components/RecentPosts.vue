<!-- components/RecentPosts.vue -->
<template>
    <div class="col-lg-4" v-for="recentPost in recentPosts" :key="recentPosts.id">
                    <div class="card-style-1 hover-up mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".0s">
                      <div class="card-image"><NuxtLink class="post-type" :to="`/blogs/${recentPost.id}`"></NuxtLink><NuxtLink class="link-post" :to="`/blogs/${recentPost.id}`"><img :src="getMedia(apiBaseUrl, recentPost.image)" alt="Genz">
                          <div class="card-info card-bg-2">
                            <div class="info-bottom mb-15">
                              <h4 class="color-white mb-15">{{ recentPost.title || 'no tile' }}</h4>
                              <div class="box-author"><img src="assets/imgs/page/homepage3/author.jpg" alt="Genz">
                                <div class="author-info">
                                    <h6 class="mr-15 color-gray-700">{{ recentPost.user.name }}</h6><span class="color-gray-700 text-sm">{{formatDate(recentPost.created_at)}}</span>
                                </div>
                              </div>
                            </div>
                          </div></NuxtLink>
                      </div>
                    </div>
                  </div>
  </template>
  
  <script setup lang="ts">
  import {formatDate, getMedia} from '@/utils/index'
  const config = useRuntimeConfig();
  const apiBaseUrl = config.public.apiBaseUrl;

  interface Tag {
    id: number
    name: string
  }
  
  interface Post {
    id: number
    title: string
    created_at: string
    tags: Tag[]
  }
  
  const props = defineProps<{
    posts: Post[]
  }>()
  
  const recentPosts = computed(() => props.posts.slice(0, 10))
  </script>