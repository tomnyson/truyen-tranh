<script setup lang="ts">
import { ref, watch } from 'vue';
import Loading from '@/components/Loading.vue';
import { useRoute } from 'vue-router';
import { useFetch } from '#app';

definePageMeta({
  layout: 'default',
  auth: false,
});

const route = useRoute();

const posts = ref([]);
const isLoading = ref(false);
const error = ref(null);

const fetchPosts = async () => {
  isLoading.value = true;
  const { categoryId, tagId } = route.query;

  const queryParams = new URLSearchParams({
    ...(categoryId && { categoryId }),
    ...(tagId && { tagId }),
  }).toString();

  try {
    const { data } = await useFetch(`/api/posts?${queryParams}`);
    posts.value = data.value;
  } catch (fetchError) {
    error.value = fetchError;
  } finally {
    isLoading.value = false;
  }
};

fetchPosts();

watch(
  () => route.query,
  () => {
    fetchPosts();
  },
  { immediate: true } 
);
</script>
<template>
   <Loading :loading="isLoading"/>
     <main class="main">
      <div class="cover-home3">
        <div class="container">
          <div class="row">
            <div class="col-xl-1"></div>
            <div class="col-xl-10 col-lg-12">
              <div class="row align-items-end mt-50">
                <div class="col-lg-12 text-center">
                  <div class="d-inline-block position-relative">
                    <h1 class="color-white mb-10 color-linear wow animate__animated animate__fadeIn">{{ posts[0]?.category?.name }}</h1>
                  </div>
                  <p class="color-gray-500 text-base mb-20 wow animate__animated animate__fadeIn">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla convallis nisi sed turpis<br class="d-none d-lg-block">vulputate viverra. Morbi ligula elit, hendrerit non nisl tincidunt, sodales consequat magna.</p>
                </div>
                <div class="col-lg-12 text-center">
                  <div class="box-breadcrumbs wow animate__animated animate__fadeIn">
                    <ul class="breadcrumb">
                      <li><NuxtLink class="home" to="">Home</NuxtLink></li>
                      <li><NuxtLink href="/blogs">Blog</NuxtLink></li>
                      <li><span>Healthy</span></li>
                    </ul>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="border-bottom border-gray-800 mt-30 mb-30"> </div>
                </div>
              </div>
              <div class="box-list-posts mt-40">
                <div class="row">
                 
                  <div class="col-lg-8 m-auto">
                    <div class="box-list-posts mt-30">
                      <h3 v-if="posts?.length==0" class="mb-20 color-white text-center">Không có bài viết nào
                      </h3>
                      <div v-else class="card-list-posts card-list-posts-small border-bottom border-gray-800 pb-30 mb-30 wow animate__animated animate__fadeIn"
                        v-for="post in posts" :key="post.id">
                        <NuxtLink class="card-image hover-up" :to="`/authors?userId=${post.user?.id}`">
                          <div class="box-author mb-20"><img src="assets/imgs/page/healthy/author.png" alt="Genz">
                            <div class="author-info">
                              <h6 class="color-gray-700">{{ post.user?.name }}</h6><span class="color-gray-700 text-sm">{{ formatDate(post.created_at) }}</span>
                            </div>
                          </div><NuxtLink class="btn btn-tag bg-gray-800 hover-up" :to="`/blogs?categoryId=${post.category?.id}`">
                          {{ post.category.name }}</NuxtLink>
                        </NuxtLink>
                        <div class="card-info"><NuxtLink :to="`/blogs/${post.id}`">
                            <h3 class="mb-20 color-white">{{ post.title }}</h3></NuxtLink>
                          <p class="color-gray-500">{{ truncatedContent(post.content) }}</p>
                          <div class="row mt-20">
                            <div class="col-7">
                              <NuxtLink class="color-gray-700 text-sm mr-15" :to="`/blogs?tagId=${tag.id}`"
                                v-for="tag in post.tags" key="category.id"
                              ># {{ tag.name }}</NuxtLink></div>
                            <!-- <div class="col-5 text-end"><span class="color-gray-700 text-sm timeread"></span></div> -->
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- <nav class="mb-50">
                      <ul class="pagination">
                        <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".0s"><a class="page-link page-prev" href="#"><i class="fi-rr-arrow-small-left"></i></a></li>
                        <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".1s"><a class="page-link" href="#">1</a></li>
                        <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".2s"><a class="page-link active" href="#">2</a></li>
                        <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".3s"><a class="page-link" href="#">3</a></li>
                        <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".4s"><a class="page-link" href="#">...</a></li>
                        <li class="page-item wow animate__animated animate__fadeIn" data-wow-delay=".5s"><a class="page-link page-next" href="#"><i class="fi-rr-arrow-small-right"></i></a></li>
                      </ul>
                    </nav> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
</template>