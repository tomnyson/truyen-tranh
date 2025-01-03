<template>
  <!-- <div id="preloader-active">
      <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
          <div class="text-center"><img class="mb-10" src="assets/imgs/template/favicon.svg" alt="GenZ">
            <div class="preloader-dots"></div>
          </div>
        </div>
      </div>
    </div> -->
  <header class="header sticky-bar">
    <div class="container">
      <div class="row align-items-start">
        <div class="col-xl-10 col-lg-12">
          <div class="main-header">
            <div class="header-logo">
              <NuxtLink class="d-flex" to="/"><img class="logo-night" alt="GenZ"
                  :src="logo"><img class="d-none logo-day" alt="GenZ"
                  :src="logo"></NuxtLink>
            </div>
            <div class="header-nav">
              <nav class="nav-main-menu d-none d-xl-block">
                <ul class="main-menu">
                  <li>
                    <NuxtLink class="color-gray-500" to="/">Trang chủ</NuxtLink>
                  </li>
                  <li class="has-children"><a class="color-gray-500" href="#">Danh mục</a>
                    <ul class="sub-menu two-col">
                      <li>
                        <NuxtLink class="color-gray-500" :to="`/blogs?categoryId=${item.id}`"
                          v-for="(item, index) in categories" :key="index">{{ item.name }}</NuxtLink>
                      </li>
                    </ul>
                  </li>

                  <li v-show="isLoggedIn" class="has-children"><a class="color-gray-500" href="#">Xin chào: {{
                      session?.user?.name }}</a>
                    <ul class="sub-menu two-col">
                      <li><NuxtLink class="color-gray-500" to="/profile">Tài khoản của tôi</NuxtLink></li>
                    </ul>
                  </li>
                </ul>
              </nav>
              <div class="burger-icon burger-icon-white"><span class="burger-icon-top"></span><span
                  class="burger-icon-mid"></span><span class="burger-icon-bottom"></span></div>
            </div>
            <div class="header-right text-end"><a class="btn btn-search" href="#"></a>
              <div class="form-search p-20">
                <form action="#">
                  <input class="form-control" type="text" v-model="search" placeholder="Search">
                  <input class="btn-search-2" type="button" @click="handleSearch" >
                </form>
                <div class="popular-keywords text-start mt-20">
                  <p class="mb-10 color-white">Popular tags:</p><a class="color-gray-600 mr-10 font-xs" href="#">#
                    Travel,</a><a class="color-gray-600 mr-10 font-xs" href="#"># Tech,</a><a
                    class="color-gray-600 mr-10 font-xs" href="#"># Movie</a><a class="color-gray-600 mr-10 font-xs"
                    href="#"># Lifestyle</a><a class="color-gray-600 mr-10 font-xs" href="#"># Sport</a>
                </div>
              </div>
              <div class="switch-button">
                <div class="form-check form-switch">
                  <input class="form-check-input" id="flexSwitchCheckChecked" type="checkbox" role="switch" checked="">
                </div>
              </div>
              <NuxtLink v-if="isLoggedIn"
                class="btn btn-linear d-none d-sm-inline-block d-md-inline-block d-lg-inline-block hover-up" to="#"
                @click.prevent="handleLogout">Đăng Xuất</NuxtLink>
              <NuxtLink v-else
                class="btn btn-linear d-none d-sm-inline-block d-md-inline-block d-lg-inline-block hover-up"
                to="/login">Đăng Nhập</NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <div class="mobile-header-active mobile-header-wrapper-style perfect-scrollbar bg-gray-900">
    <div class="mobile-header-wrapper-inner">
      <div class="mobile-header-content-area">
        <div class="mobile-logo border-gray-800"><NuxtLink class="d-flex" to="/"><img class="logo-night" alt="GenZ"
              src="assets/imgs/template/logo.svg"><img class="d-none logo-day" alt="GenZ"
              :src="logo"></NuxtLink></div>
        <div class="perfect-scroll">
          <div class="mobile-menu-wrap mobile-header-border">
            <nav class="mt-15">
              <ul class="mobile-menu font-heading">
                <li class="has-children"><NuxtLink to="/">Trang chủ</NuxtLink>
                </li>
                <li class="has-children"><a href="#">Danh mục</a>
                  <ul class="sub-menu">
                    <li><a href="blog-archive.html">Blog Category 1</a></li>
                    <li><a href="blog-archive-2.html">Blog Category 2</a></li>
                    <li><a href="blog-archive-3.html">Blog Category 3</a></li>
                    <li><a href="blog-archive-4.html">Blog Category 4</a></li>
                    <li><a href="blog-archive-5.html">Blog Category 5</a></li>
                  </ul>
                </li>
                <li class="has-children"><a class="color-gray-500" href="page-portfolio.html">Portfolio</a>
                  <ul class="sub-menu">
                    <li><a class="color-gray-500" href="page-portfolio.html">My Portfolio</a></li>
                    <li><a class="color-gray-500" href="page-portfolio-2.html">My Portfolio 2</a></li>
                    <li><a class="color-gray-500" href="portfolio-details.html">Portfolio Details</a></li>
                  </ul>
                </li>
                <li class="has-children"><a href="#">Pages</a>
                  <ul class="sub-menu">
                    <li><a href="page-about.html">About</a></li>
                    <li><a href="page-author.html">Author posts</a></li>
                    <li><a href="page-contact.html">Contact</a></li>
                    <li><a href="page-search.html">Search results</a></li>
                    <li><a href="page-login.html">Login</a></li>
                    <li><a href="page-signup.html">Signup</a></li>
                    <li><a href="page-404.html">Page 404</a></li>
                  </ul>
                </li>
                <li><a href="page-contact.html">Contact</a></li>
                <li class="has-children"><a href="#">Profile</a>
                  <ul class="sub-menu">
                    <li><a href="page-about.html">About</a></li>
                    <li><a href="page-author.html">Author posts</a></li>
                    <li><a href="page-contact.html">Contact</a></li>
                    <li><a href="page-search.html">Search results</a></li>
                    <li><a href="page-login.html">Login</a></li>
                    <li><a href="page-signup.html">Signup</a></li>
                    <li><a href="page-404.html">Page 404</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
          <div class="mobile-account border-gray-800">
            <div class="mobile-header-top bg-gray-900">
              <div class="user-account"><a href="page-login.html"><img src="assets/imgs/template/ava.jpg"
                    alt="GenZ"></a>
                <div class="content">
                  <h6 class="user-name color-white">Hello<span class="color-white"> Steven !</span></h6>
                  <p class="font-xs text-muted">You have 3 new messages</p>
                </div>
              </div>
            </div>
            <ul class="mobile-menu">
              <li><a href="page-login.html">Profile</a></li>
              <li><a href="page-login.html">Articles Saved</a></li>
              <li><a href="page-login.html">Add new post</a></li>
              <li><a href="page-login.html">My Likes</a></li>
              <li><a href="page-login.html">Account Setting</a></li>
              <li><a href="page-login.html">Sign out</a></li>
            </ul>
          </div>
          <div class="site-copyright color-gray-400 mt-30">Copyright 2023 &copy; Genz - Personal Blog
            Template.<br>Designed by<a href="http://alithemes.com" target="_blank">&nbsp; AliThemes</a></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed, watchEffect, ref } from "vue";
import { toast } from 'vue3-toastify';
import { useRouter } from "vue-router";

interface Category {
  id: number;
  name: string;
  image?: string;
}

interface Setting {
  key: string;
  value: string;
}

const props = defineProps<{
  data: Array<Category>;
  settings: Setting[];
}>();

const categories = computed(() => props.data ?? []);
const search = ref('');
const router = useRouter();
const { status, data: session, signOut } = useAuth();
const LOGO_KEY = 'LOGO';
const isLoggedIn = computed(() => status.value === 'authenticated');
const logo = props.settings?.filter(setting => setting.key === LOGO_KEY)[0]?.value || 'assets/imgs/template/logo.svg';

useHead({
  title: 'news truyên tranh',
  meta: [
    {
      name: 'description',
      content: 'news truyên tranh'
    }
  ]
});

watchEffect(() => {
  if (isLoggedIn.value) {
    console.log('User is logged in', JSON.stringify(session.value));
  } else {
    console.log('User is not logged in');
  }
});

const handleLogout = async () => {
  toast.info('Logging out...');
  await signOut({
    callbackUrl: '/login'
  })
}
const handleSearch = () => {
  console.log('Search:', search.value);
  router.push({ path: '/blogs', query: { search: search.value } });
}
</script>