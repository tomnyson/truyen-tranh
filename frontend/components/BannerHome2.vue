<template>
  <div class="container mt-5 slider">
    <Swiper
      :slides-per-view="3"
      :space-between="0"
      :loop="true"
      pagination
      class="mySwiper"
    >
      <SwiperSlide v-for="(slide, index) in slides" :key="index">
        <div class="swiper-slide-content">
          <div class="col-lg-10">
            <div
              class="card-style-1 hover-up mb-30 wow animate__animated animate__fadeIn"
              data-wow-delay=".0s"
            >
              <div class="card-image">
                <a class="post-type" href="blog-archive.html"></a
                ><a class="link-post" href="single-sidebar.html"
                  ><img src="assets/imgs/page/homepage3/news1.png" alt="Genz" />
                  <div class="card-info card-bg-2">
                    <div class="info-bottom mb-15">
                      <h4 class="color-white mb-15">Aenean Auctor Wisi Et Urna Aliquam Erat</h4>
                      <div class="box-author">
                        <img src="assets/imgs/page/homepage3/author.jpg" alt="Genz" />
                        <div class="author-info">
                          <h6 class="mr-15 color-gray-700">Thomson</h6>
                          <span class="color-gray-700 text-sm">25 April 2022</span>
                        </div>
                      </div>
                    </div>
                  </div></a
                >
              </div>
            </div>
          </div>
        </div>
      </SwiperSlide>
    </Swiper>
  </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';


// Sample slide data
const slides = [
  { title: 'Slide 1', content: 'This is the first slide.' },
  { title: 'Slide 2', content: 'This is the second slide.' },
  { title: 'Slide 3', content: 'This is the third slide.' },
  { title: 'Slide 4', content: 'This is the first slide.' },
  { title: 'Slide 5', content: 'This is the second slide.' },
  { title: 'Slide 6', content: 'This is the third slide.' },
];
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
