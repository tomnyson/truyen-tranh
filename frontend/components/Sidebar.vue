<!-- components/Sidebar.vue -->
<template>
    <div class="sidebar"> 
      <div class="box-sidebar bg-gray-850 border-gray-800">
        <div class="head-sidebar wow animate__animated animate__fadeIn">
          <h5 class="line-bottom">Popular Posts</h5>
        </div>
        <div class="content-sidebar">
          <div class="list-posts">
            <div
              class="item-post wow animate__animated animate__fadeIn"
              v-for="post in popularPosts"
              :key="post.id"
            >
              <div class="image-post"><a href="single-sidebar.html"><img src="/assets/imgs/page/homepage1/img-post.jpg" alt="Genz"></a></div>
              <div class="info-post border-gray-800">
                <a href="single-sidebar.html"><h6 class="color-white">{{ post.title }}</h6></a>
                <span class="color-gray-700">15 mins read</span>
                <ul class="d-inline-block">
                  <li class="color-gray-700">{{ post.created_at }}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Last Comments section goes here similarly -->
      <!-- ... -->
    </div>
  </template>
  
  <script setup lang="ts">
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
  
  const popularPosts = computed(() => props.posts.slice(0,3))
  </script>