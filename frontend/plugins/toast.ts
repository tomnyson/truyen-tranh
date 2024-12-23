import { defineNuxtPlugin } from '#app';
import Toast from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.use(Toast, {
    autoClose: 3000, // Default timeout
    position: 'top-right', // Default position
  });
});