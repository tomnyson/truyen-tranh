<template>
    <div v-if="loading" id="preloader-active">
      <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
          <div class="text-center">
            <img class="mb-10" src="assets/imgs/template/favicon.svg" alt="GenZ" />
            <div class="preloader-dots"></div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  defineProps({
    loading: {
      type: Boolean,
      default: false,
    },
  });
  </script>