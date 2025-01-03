<template>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th class="color-linear">#</th>
            <th class="color-linear">Image</th>
            <th class="color-linear">Title</th>
            <th class="color-linear">Author</th>
            <th class="color-linear">Created At</th>
            <th class="color-linear">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(recentPost, index) in recentPosts" :key="recentPost.id">
            <td>{{ index + 1 }}</td>
            <td class="color-linear">
              <img :src="getMedia(recentPost.image)" alt="Post Image" class="img-thumbnail" style="width: 100px; height: auto;" />
            </td>
            <td class="color-linear">{{ recentPost.title || 'No title' }}</td>
            <td class="color-linear">{{ recentPost.user.name }}</td>
            <td class="color-linear">{{ formatDate(recentPost.created_at) }}</td>
            <td>
              <NuxtLink :to="`/blogs/${recentPost.id}`" class="btn btn-primary btn-sm">
                View
              </NuxtLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup lang="ts">
  import { formatDate, getMedia } from '@/utils/index';
  
  interface Tag {
    id: number;
    name: string;
  }
  
  interface Post {
    id: number;
    title: string;
    created_at: string;
    tags: Tag[];
    image: string;
    user: {
      name: string;
    };
  }
  
  const props = defineProps<{
    posts: Post[];
  }>();
  
  const recentPosts = computed(() => props.posts.slice(0, 10));
  </script>
  
  <style scoped>
  /* Apply white text color to table headers and cells */
  table td,
  table th {
    color: #fff;
  }
  
  /* Optional: Add background color for table header */
  table thead th {
    background-color: #333;
  }
  
  /* Optional: Add hover effect for table rows */
  table tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.1);
  }
  
  /* Ensure table is responsive */
  .table-responsive {
    overflow-x: auto;
  }
  </style>