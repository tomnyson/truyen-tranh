<script lang="ts" setup>
import { computed } from "vue";

interface Category {
  id: number;
  name: string;
  image?: string;
}
const props = defineProps<{
  data: Array<Category>;
}>();

const categories = computed(() => props.data ?? []);

const numberSlice = computed(() => Math.round(categories.value.length / 2));

const categoriesFirst = computed(() =>
  categories.value.slice(0, numberSlice.value)
);
const categoriesSecond = computed(() =>
  categories.value.slice(numberSlice.value)
);
</script>

<template>
  <footer class="footer">

    <div class="container">
      <div class="footer-1 bg-gray-850 border-gray-800">
        <div class="row">          <!-- Footer Left Section -->
          <div class="col-lg-4 mb-30">
            <a
              class="wow animate__animated animate__fadeInUp"
              href="index.html"
            >
              <img
                class="logo-night"
                src="assets/imgs/template/logo.svg"
                alt="Genz"
              />
              <img
                class="d-none logo-day"
                alt="GenZ"
                src="assets/imgs/template/logo-day.svg"
              />
            </a>
            <p class="mb-20 mt-20 text-sm color-gray-500 wow animate__animated animate__fadeInUp">
              When an unknown prnoto sans took a galley and scrambled it to make
              specimen book not only five When an unknown prnoto sans took a
              galley and scrambled it to five centurie.
            </p>
            <h6 class="color-white mb-5 wow animate__animated animate__fadeInUp">
              Address
            </h6>
            <p class="text-sm color-gray-500 wow animate__animated animate__fadeInUp">
              123 Main Street<br />New York, NY 10001
            </p>
          </div>

          <!-- Categories Section -->
          <div class="col-lg-4 mb-30">
            <h6
              class="text-lg mb-30 color-white wow animate__animated animate__fadeInUp"
            >
              Categories
            </h6>
            <div class="row">
              <div class="col-6">
                <ul class="menu-footer" v-if="categoriesFirst.length">
                  <li
                    class="wow animate__animated animate__fadeInUp"
                    v-for="(item, index) in categoriesFirst"
                    :key="index"
                  >
                    <NuxtLink
                      class="color-gray-500"
                      :to="`/blog-archive/${item.id}`"
                      >{{ item.name }}</NuxtLink
                    >
                  </li>
                </ul>
              </div>
              <div class="col-6">
                <ul class="menu-footer" v-if="categoriesSecond.length">
                  <li
                    class="wow animate__animated animate__fadeInUp"
                    v-for="(item, index) in categoriesSecond"
                    :key="index"
                  >
                    <NuxtLink
                      class="color-gray-500"
                      :to="`/blog-archive/${item.id}`"
                      >{{ item.name }}</NuxtLink
                    >
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Newsletter Section -->
          <div class="col-lg-4 mb-30">
            <h4
              class="text-lg mb-30 color-white wow animate__animated animate__fadeInUp"
            >
              Newsletter
            </h4>
            <p class="text-base color-gray-500 wow animate__animated animate__fadeInUp">
              Sign up to be first to receive the latest stories inspiring us,
              case studies, and industry news.
            </p>
            <div
              class="form-newsletters mt-15 wow animate__animated animate__fadeInUp"
            >
              <form action="#">
                <div class="form-group">
                  <input
                    class="input-name border-gray-500"
                    type="text"
                    placeholder="Your name"
                  />
                </div>
                <div class="form-group">
                  <input
                    class="input-email border-gray-500"
                    type="email"
                    placeholder="Email address"
                  />
                </div>
                <div class="form-group mt-20">
                  <button class="btn btn-linear hover-up">
                    Subscribe
                    <i class="fi-rr-arrow-small-right"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Footer Bottom Section -->
        <div class="footer-bottom border-gray-800">
          <div class="row">
            <div class="col-lg-5 text-center text-lg-start">
              <p
                class="text-base color-white wow animate__animated animate__fadeIn"
              >
                © Created by
                <a
                  class="copyright"
                  href="#"
                  target="_blank"
                  >tomnyson</a
                >
              </p>
            </div>
            <div class="col-lg-7 text-center text-lg-end">
              <div class="box-socials">
                <div
                  class="d-inline-block mr-30 wow animate__animated animate__fadeIn"
                  data-wow-delay=".0s"
                >
                  <a
                    class="icon-socials icon-twitter color-gray-500"
                    href="https://twitter.com"
                    >Twitter</a
                  >
                </div>
                <div
                  class="d-inline-block mr-30 wow animate__animated animate__fadeIn"
                  data-wow-delay=".2s"
                >
                  <a
                    class="icon-socials icon-linked color-gray-500"
                    href="https://www.linkedin.com"
                    >LinkedIn</a
                  >
                </div>
                <div
                  class="d-inline-block wow animate__animated animate__fadeIn"
                  data-wow-delay=".4s"
                >
                  <a
                    class="icon-socials icon-insta color-gray-500"
                    href="https://www.instagram.com"
                    >Instagram</a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="progressCounter progressScroll hover-up hover-neon-2">
      <div class="progressScroll-border">
        <div class="progressScroll-circle">
          <span class="progressScroll-text">
            <i class="fi-rr-arrow-small-up"></i>
          </span>
        </div>
      </div>
    </div>
  </footer>
</template>