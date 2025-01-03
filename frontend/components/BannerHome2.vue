<template>
  <div class="container mt-5 slider">
    <Swiper :slides-per-view="3" :space-between="0" :loop="true" pagination class="mySwiper">
      <SwiperSlide v-for="(slide, index) in postsPined" :key="index">
        <div class="swiper-slide-content">
          <div class="col-sm-10">
            <div class="card-style-1 hover-up mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".0s">
              <div class="card-image">
                <NuxtLink class="post-type" :to="`/blogs?categoryId=${slide.category.id}`"></NuxtLink><NuxtLink class="link-post" :to="`/blogs/${slide.id}`"><img
                    :src="getMedia(slide.image)" alt="Genz" />
                  <div class="card-info card-bg-2">
                    <div class="info-bottom mb-15">     
                      <h4 class="color-white mb-15">{{ slide.title }}</h4>
                      <div class="box-author">
                        <img src="assets/imgs/page/homepage3/author.jpg" alt="Genz" />
                        <div class="author-info">
                          <h6 class="mr-15 color-gray-700">{{ slide.user.nam }}</h6>
                          <span class="color-gray-700 text-sm">{{formatDate(slide.created_at)}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </SwiperSlide>
    </Swiper>
  </div>
</template>

<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
  import {formatDate, getMedia} from '@/utils/index'

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

const props = defineProps < {
  sliders: Post[]
} > ()
  const postsPined = computed(() => props.sliders)
  console.log(postsPined.value)



// Sample slide data
</script>

<style scoped>
.mySwiper {
  /* height: 300px; */
}

.swiper-slide-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: white;
  width: 100%;
  text-align: center;
}

.swiper-slide-content h2 {
  font-size: 2rem;
}

.swiper-slide-content p {
  font-size: 1rem;
  margin-top: 10px;
}

.slider {
  padding-top: 50px;
}
</style>
