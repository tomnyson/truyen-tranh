<!-- pages/index.vue -->
<script setup lang="ts">
import BannerHome2 from '@/components/BannerHome2.vue'
import TrendingTopics from '@/components/TrendingTopics.vue'
import RecentPosts from '@/components/RecentPosts.vue'
import PopularPosts from '~/components/PopularPosts.vue';
import { toast } from 'vue3-toastify';

definePageMeta({ layout: 'default', auth: false, title: 'Bài viết của' })
const route = useRoute();
const {userId} =  route.query

const { data: posts, pending, error } =  await useFetch(`/api/posts?userId=${userId}`);
const { data: author, pending: userPending} = await useFetch(`/api/authors/${userId}`);
</script>

<template>
     <main class="main">
      <Loading :loading="pending || userPending"/>
      <div class="banner banner-home4 bg-gray-850">
        <div class="container">
          <div class="row align-items-start">
            <div class="col-xl-1"></div>
            <div class="col-xl-10 col-lg-12">
              <div class="pt-20">
                <div class="box-banner-4">
                  <div class="banner-image"><img src="assets/imgs/page/homepage4/banner.png" alt="Genz"></div>
                  <div class="banner-info"><span class="text-sm-bold color-gray-600">{{ author.name }}</span>
                    <h3 class="color-linear d-inline-block mt-10 mb-15">I'm Steven, a lover of technology, business and experiencing new things</h3>
                    <div class="box-socials"><a class="bg-gray-800 hover-up" href="#"><span class="fb"></span></a><a class="bg-gray-800 hover-up" href="#"><span class="inst"></span></a><a class="bg-gray-800 hover-up" href="#"><span class="snap"></span></a><a class="bg-gray-800 hover-up" href="#"><span class="tw"></span></a></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      {{ post }}
      <div class="cover-home3">
        <div class="container">
          <div class="row">
            <div class="col-xl-1"></div>
            <div class="col-xl-10 col-lg-12">
              <div class="mt-50">
                <h2 class="color-linear d-inline-block mb-10">Posted by {{author.name}}</h2>
                <p class="text-lg color-gray-500">Exclusive author</p>
                <div class="row mt-50 mb-10">
                  <h3 v-if="posts?.length==0" class="mb-20 color-white text-center">Không có bài viết nào
                  </h3>
                  <!-- <div class="col-lg-6">
                    <div class="card-blog-1 border-gray-800 bg-gray-850 hover-up">
                      <div class="card-image mb-20"><a class="post-type" href="#"></a><a href="single-sidebar.html"><img src="assets/imgs/page/travel-tip/news1.png" alt="Genz"></a></div>
                      <div class="card-info">
                        <div class="row">
                          <div class="col-7"><a class="color-gray-700 text-sm" href="blog-archive.html"># Travel</a><a class="color-gray-700 text-sm" href="blog-archive.html"># Lifestyle</a></div>
                          <div class="col-5 text-end"><span class="color-gray-700 text-sm timeread">3 mins read</span></div>
                        </div><a href="single-sidebar.html">
                          <h4 class="color-white mt-20">Self-observation is the first step of inner unfolding</h4></a>
                        <div class="row align-items-center mt-25">
                          <div class="col-7">
                            <div class="box-author"><img src="assets/imgs/page/homepage1/author.jpg" alt="Genz">
                              <div class="author-info">
                                <h6 class="color-gray-700">Joseph</h6><span class="color-gray-700 text-sm">25 April 2022</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-5 text-end"><a class="readmore color-gray-500 text-sm" href="single-sidebar.html"><span>Read more</span></a></div>
                        </div>
                      </div>
                    </div>
                  </div> -->
                  <div class="col-lg-4" v-for="post in posts" :key="post.id">
                    <div class="card-blog-1 border-gray-800 bg-gray-850 hover-up">
                      <div class="card-image mb-20"><NuxtLink class="post-type" :to="`/blogs?categoryId=${post.category.id}`">{{ post.category.name }}</NuxtLink><NuxtLink :to="``"><img src="assets/imgs/page/travel-tip/news3.png" alt="Genz"></NuxtLink></div>
                      <div class="card-info">
                        <div class="row">
                          <div class="col-7">
                            <NuxtLink class="color-gray-700 text-sm" :to="`/blogs?tagId=${tag.id}`"
                            v-for="tag in post.tags" key="category.id"
                            ># {{ tag.name }}</NuxtLink></div>
                          <!-- <div class="col-5 text-end"><span class="color-gray-700 text-sm timeread">3 mins read</span></div> -->
                        </div><NuxtLink :to="`/blogs/${post.id}`">
                          <h4 class="color-white mt-20">{{ post.title }}</h4></NuxtLink>
                        <div class="row align-items-center mt-25">
                          <div class="col-7">
                            <div class="box-author"><img src="assets/imgs/page/homepage1/author.jpg" alt="Genz">
                              <div class="author-info">
                                <h6 class="color-gray-700">{{post.user.name}}</h6><span class="color-gray-700 text-sm">{{ formatDate(post.created_at) }}</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-5 text-end"><NuxtLink class="readmore color-gray-500 text-sm" :to="`/blogs/${post.id}`"><span>Read more</span></NuxtLink></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <nav class="mb-50">
                  <ul class="pagination">
                    <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".0s"><a class="page-link page-prev" href="#"><i class="fi-rr-arrow-small-left"></i></a></li>
                    <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".1s"><a class="page-link" href="#">1</a></li>
                    <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".2s"><a class="page-link active" href="#">2</a></li>
                    <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".3s"><a class="page-link" href="#">3</a></li>
                    <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".4s"><a class="page-link" href="#">...</a></li>
                    <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".5s"><a class="page-link page-next" href="#"><i class="fi-rr-arrow-small-right"></i></a></li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
</template>