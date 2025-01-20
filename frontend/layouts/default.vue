<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import Header from '@/components/Header.vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// Using useHead for meta tags, styles, and scripts
useHead({
  link: [
    { rel: 'stylesheet', href: '/assets/css/style.css' }
  ],
  script: [
    { src: '/assets/js/vendors/jquery-3.6.0.min.js', defer: true, body: true },
    { src: '/assets/js/vendors/waypoints.js', defer: true, body: true  },
    { src: '/assets/js/vendors/bootstrap.bundle.min.js', defer: true },
    { src: '/assets/js/vendors/wow.js', defer: true, body: true },
    { src: '/assets/js/vendors/text-type.js', defer: true,  body: true },
    { src: '/assets/js/vendors/swiper-bundle.min.js', defer: true,  body: true },
    { src: '/assets/js/vendors/jquery.progressScroll.min.js', defer: true,  body: true },
    { src: '/assets/js/main.js', defer: true,  body: true }
  ],
  title: 'My Page Title',
  meta: [
    { name: 'description', content: 'This is my page description' },
    { property: 'og:title', content: 'My Open Graph Title' }
  ]
});

// Fetch categories data
const {data: categories} = await useFetch('/api/categories');
const { data: settings, status: settingStatus, error: settingError } = await useFetch('/api/settings')
</script>


<template>
  <!-- Pass categories to Header and Footer if available -->
  <Header :data="categories" :settings="settings" />
  <!-- Slot for dynamic content -->
  <slot />
  <!-- {{ response }} -->
  <Footer :data="categories || []" />
</template>

<style scoped>
/* Add scoped styles here if needed */
.card-style-1 .card-image img {
    width: 100%;
    border-radius: 8px;
    height: 300px !important;
}
</style>