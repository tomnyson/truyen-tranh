<!-- components/TrendingTopics.vue -->
<template>
    <div class="mb-70 mt-70">
      <div class="box-topics box-topics-2 border-gray-800 bg-gray-850">
        <div class="row">
          <div class="col-lg-12">
            <div class="box-swiper">
              <div class="swiper-container swiper-group-6">
                <div class="swiper-wrapper">
                  <div class="swiper-slide" v-for="category in topCategories" :key="category.id">
                    <div class="card-style-1">
                      <a href="blog-archive.html">
                        <div class="card-image">
                          <img src="assets/imgs/page/homepage1/sport.png" alt="Genz">
                          <div class="card-info">
                            <div class="info-bottom">
                              <h6 class="color-white mb-5">{{ category.name }}</h6>
                              <p class="text-xs color-gray-500">38 Articles</p>
                            </div>
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-button-prev swiper-button-prev-style-2"></div>
              <div class="swiper-button-next swiper-button-next-style-2"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  interface Category {
    id: number
    name: string
  }
  
  const props = defineProps<{
    categories: Category[]
  }>()
  
  const topCategories = computed(() => props.categories.slice(0, 5))
  </script>