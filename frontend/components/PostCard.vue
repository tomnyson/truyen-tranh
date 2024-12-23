<!-- components/PostCard.vue -->
<template>
    <div class="col-lg-4">
                    <div class="card-style-1 hover-up mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".0s">
                      <div class="card-image"><a class="post-type" href="blog-archive.html"></a><a class="link-post" href="single-sidebar.html"><img src="assets/imgs/page/homepage3/news1.png" alt="Genz">
                          <div class="card-info card-bg-2">
                            <div class="info-bottom mb-15">
                              <h4 class="color-white mb-15">Aenean Auctor Wisi Et Urna Aliquam Erat</h4>
                              <div class="box-author"><img src="assets/imgs/page/homepage3/author.jpg" alt="Genz">
                                <div class="author-info">
                                  <h6 class="mr-15 color-gray-700">Thomson</h6><span class="color-gray-700 text-sm">25 April 2022</span>
                                </div>
                              </div>
                            </div>
                          </div></a>
                      </div>
                    </div>
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
    post: Post,
    image: string
  }>()
  </script>