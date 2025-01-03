<template>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-6" v-for="tag in tagsPublic" :key="tag.id">
      <div class="card-style-2 hover-up hover-neon wow animate__animated animate__fadeIn" data-wow-delay="0s">
        <div class="card-image"><NuxtLink :to="`/blogs?tagId=${tag.id}`"><img :src="tag.image" alt="Genz"></NuxtLink></div>
        <div class="card-info"><NuxtLink class="color-gray-500" :to="`/blogs?tagId=${tag.id}`">{{ tag.name }}</NuxtLink></div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
    interface Tag {
      id: number
      name: string
      image?: string
    }
  
  const props = defineProps<{
      tags: Tag[]
    }>()
  
  const tagsPublic = computed(() => props.tags.slice(0, 10))
  </script>