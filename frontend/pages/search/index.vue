<!-- pages/index.vue -->
<script setup lang="ts">
definePageMeta({ layout: 'default', auth: false, title: 'Trang chủ' })

const { data: posts, pending, error } = await useFetch('/api/posts')
const { data: categories, status: categoriesStatus, error: categoriesError } = await useFetch('/api/categories')
const { data: tags, status: tagsStatus, error: tagsError } = await useFetch('/api/tags')

import BannerHome2 from '@/components/BannerHome2.vue'
import TrendingTopics from '@/components/TrendingTopics.vue'
import RecentPosts from '@/components/RecentPosts.vue'
import PopularPosts from '~/components/PopularPosts.vue';
import { toast } from 'vue3-toastify';

// toast.success('Operation successful!');
const showToast = () => {
  toast.success('Operation successful!');
}
</script>

<template>
    <main class="main">
      <div class="cover-home3">
        <div class="container">
          <div class="row">
            <div class="col-xl-1"></div>
            <div class="col-xl-10 col-lg-12">
              <div class="row align-items-end mt-50">
                <div class="col-lg-12 text-center">
                  <div class="d-inline-block position-relative">
                    <h1 class="color-white mb-10">Search results</h1>
                  </div>
                  <p class="color-gray-500 text-base mb-20">We found 22 articles for "Hello" key word</p>
                </div>
                <div class="col-lg-12 text-center">
                  <div class="box-breadcrumbs">
                    <ul class="breadcrumb">
                      <li><a class="home" href="index.html">Home</a></li>
                      <li><span>Search results</span></li>
                    </ul>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="border-bottom border-gray-800 mt-30 mb-30"></div>
                </div>
              </div>
              <div class="box-list-posts mt-40">
                <div class="row">
                  <div class="col-lg-8 m-auto">
                    <div class="box-list-posts mt-30">
                      <div class="card-list-posts card-list-posts-small border-bottom border-gray-800 pb-30 mb-30 wow animate__animated animate__fadeIn">
                        <div class="card-image hover-up">
                          <div class="box-author mb-20"><img src="assets/imgs/page/healthy/author.png" alt="Genz">
                            <div class="author-info">
                              <h6 class="color-gray-700">Joseph</h6><span class="color-gray-700 text-sm">25 April 2022</span>
                            </div>
                          </div><a class="btn btn-tag bg-gray-800 hover-up" href="blog-archive.html">Working Tips</a>
                        </div>
                        <div class="card-info"><a href="single-sidebar.html">
                            <h3 class="mb-20 color-white">What Are NFTs? Non-Fungible Tokens &amp; Crypto Art Explained</h3></a>
                          <p class="color-gray-500">Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far</p>
                          <div class="row mt-20">
                            <div class="col-7"><a class="color-gray-700 text-sm mr-15" href="blog-archive.html"># Travel</a><a class="color-gray-700 text-sm" href="blog-archive.html"># Lifestyle</a></div>
                            <div class="col-5 text-end"><span class="color-gray-700 text-sm timeread">3 mins read</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="card-list-posts card-list-posts-small border-bottom border-gray-800 pb-30 mb-30 wow animate__animated animate__fadeIn">
                        <div class="card-image hover-up">
                          <div class="box-author mb-20"><img src="assets/imgs/page/healthy/author.png" alt="Genz">
                            <div class="author-info">
                              <h6 class="color-gray-700">Joseph</h6><span class="color-gray-700 text-sm">25 April 2022</span>
                            </div>
                          </div><a class="btn btn-tag bg-gray-800 hover-up" href="blog-archive.html">Working Tips</a>
                        </div>
                        <div class="card-info"><a href="single-sidebar.html">
                            <h3 class="mb-20 color-white">5 Resources To Find Diverse Stock Images For Your Website</h3></a>
                          <p class="color-gray-500">Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far</p>
                          <div class="row mt-20">
                            <div class="col-7"><a class="color-gray-700 text-sm mr-15" href="blog-archive.html"># Travel</a><a class="color-gray-700 text-sm" href="blog-archive.html"># Lifestyle</a></div>
                            <div class="col-5 text-end"><span class="color-gray-700 text-sm timeread">3 mins read</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="card-list-posts card-list-posts-small border-bottom border-gray-800 pb-30 mb-30 wow animate__animated animate__fadeIn">
                        <div class="card-image hover-up">
                          <div class="box-author mb-20"><img src="assets/imgs/page/healthy/author.png" alt="Genz">
                            <div class="author-info">
                              <h6 class="color-gray-700">Joseph</h6><span class="color-gray-700 text-sm">25 April 2022</span>
                            </div>
                          </div><a class="btn btn-tag bg-gray-800 hover-up" href="blog-archive.html">Working Tips</a>
                        </div>
                        <div class="card-info"><a href="single-sidebar.html">
                            <h3 class="mb-20 color-white">What if you could spend the lockdown at Hogwarts</h3></a>
                          <p class="color-gray-500">Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far</p>
                          <div class="row mt-20">
                            <div class="col-7"><a class="color-gray-700 text-sm mr-15" href="blog-archive.html"># Travel</a><a class="color-gray-700 text-sm" href="blog-archive.html"># Lifestyle</a></div>
                            <div class="col-5 text-end"><span class="color-gray-700 text-sm timeread">3 mins read</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="card-list-posts card-list-posts-small border-bottom border-gray-800 pb-30 mb-30 wow animate__animated animate__fadeIn">
                        <div class="card-image hover-up">
                          <div class="box-author mb-20"><img src="assets/imgs/page/healthy/author.png" alt="Genz">
                            <div class="author-info">
                              <h6 class="color-gray-700">Joseph</h6><span class="color-gray-700 text-sm">25 April 2022</span>
                            </div>
                          </div><a class="btn btn-tag bg-gray-800 hover-up" href="blog-archive.html">Working Tips</a>
                        </div>
                        <div class="card-info"><a href="single-sidebar.html">
                            <h3 class="mb-20 color-white">Inside 3D Illustration with Elements Author Amrit Pal Singh</h3></a>
                          <p class="color-gray-500">Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far</p>
                          <div class="row mt-20">
                            <div class="col-7"><a class="color-gray-700 text-sm mr-15" href="blog-archive.html"># Travel</a><a class="color-gray-700 text-sm" href="blog-archive.html"># Lifestyle</a></div>
                            <div class="col-5 text-end"><span class="color-gray-700 text-sm timeread">3 mins read</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="card-list-posts card-list-posts-small border-bottom border-gray-800 pb-30 mb-30 wow animate__animated animate__fadeIn">
                        <div class="card-image hover-up">
                          <div class="box-author mb-20"><img src="assets/imgs/page/healthy/author.png" alt="Genz">
                            <div class="author-info">
                              <h6 class="color-gray-700">Joseph</h6><span class="color-gray-700 text-sm">25 April 2022</span>
                            </div>
                          </div><a class="btn btn-tag bg-gray-800 hover-up" href="blog-archive.html">Working Tips</a>
                        </div>
                        <div class="card-info"><a href="single-sidebar.html">
                            <h3 class="mb-20 color-white">Indian digital artists are exploring the latest craze: Cryptoart</h3></a>
                          <p class="color-gray-500">Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far</p>
                          <div class="row mt-20">
                            <div class="col-7"><a class="color-gray-700 text-sm mr-15" href="blog-archive.html"># Travel</a><a class="color-gray-700 text-sm" href="blog-archive.html"># Lifestyle</a></div>
                            <div class="col-5 text-end"><span class="color-gray-700 text-sm timeread">3 mins read</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="card-list-posts card-list-posts-small border-bottom border-gray-800 pb-30 mb-30 wow animate__animated animate__fadeIn">
                        <div class="card-image hover-up">
                          <div class="box-author mb-20"><img src="assets/imgs/page/healthy/author.png" alt="Genz">
                            <div class="author-info">
                              <h6 class="color-gray-700">Joseph</h6><span class="color-gray-700 text-sm">25 April 2022</span>
                            </div>
                          </div><a class="btn btn-tag bg-gray-800 hover-up" href="blog-archive.html">Working Tips</a>
                        </div>
                        <div class="card-info"><a href="single-sidebar.html">
                            <h3 class="mb-20 color-white">How Young Indian Digital Artists See A New Future In NFTs</h3></a>
                          <p class="color-gray-500">Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far</p>
                          <div class="row mt-20">
                            <div class="col-7"><a class="color-gray-700 text-sm mr-15" href="blog-archive.html"># Travel</a><a class="color-gray-700 text-sm" href="blog-archive.html"># Lifestyle</a></div>
                            <div class="col-5 text-end"><span class="color-gray-700 text-sm timeread">3 mins read</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="card-list-posts card-list-posts-small border-bottom border-gray-800 pb-30 mb-30 wow animate__animated animate__fadeIn">
                        <div class="card-image hover-up">
                          <div class="box-author mb-20"><img src="assets/imgs/page/healthy/author.png" alt="Genz">
                            <div class="author-info">
                              <h6 class="color-gray-700">Joseph</h6><span class="color-gray-700 text-sm">25 April 2022</span>
                            </div>
                          </div><a class="btn btn-tag bg-gray-800 hover-up" href="blog-archive.html">Working Tips</a>
                        </div>
                        <div class="card-info"><a href="single-sidebar.html">
                            <h3 class="mb-20 color-white">NFT: The new brush stroke in the Indian art world</h3></a>
                          <p class="color-gray-500">Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far</p>
                          <div class="row mt-20">
                            <div class="col-7"><a class="color-gray-700 text-sm mr-15" href="blog-archive.html"># Travel</a><a class="color-gray-700 text-sm" href="blog-archive.html"># Lifestyle</a></div>
                            <div class="col-5 text-end"><span class="color-gray-700 text-sm timeread">3 mins read</span></div>
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
        </div>
      </div>
    </main>
</template>