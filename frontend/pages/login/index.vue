<!-- pages/index.vue -->
<script setup lang="ts">
definePageMeta({ layout: 'default' })

import { useRouter } from 'vue-router';
import { defineRule, Field, Form, ErrorMessage, configure } from 'vee-validate'
import { useForm } from "vee-validate";
import * as yup from "yup";
import { toast } from 'vue3-toastify';
import { sleep } from '@/utils/index';

const router = useRouter();
const { signIn } = useAuth()
const { handleSubmit, errors, defineField } = useForm({
  validationSchema: yup.object({
    email: yup.string().required().email(),
    password: yup.string().min(8).required(),
  }),
});

const [email, emailAttrs] = defineField('email');
const [password, passwordAttrs] = defineField('password');

const onSubmit = handleSubmit(async (formValues) => {
  try {
    const res = await signIn(
        { ...formValues },
        { callbackUrl: '/' } // Where the user will be redirected after a successiful login
      )
    router.push('/');

    // Handle success (e.g., redirect or store user data)
  } catch (error: any) {
    console.error('Login failed:', error.message);
    toast.error('Login failed');
  }
});
</script>

<template>
  <main class="main">
    <div class="cover-home3">
      <div class="container">
        <div class="row">
          <div class="col-xl-10 col-lg-12 m-auto">
            <div class="text-center mt-50 pb-50">
              <h2 class="color-linear d-inline-block">Chào mừng trở lại!</h2>
            </div>
            <div class="box-form-login pb-50">
              <div class="form-login bg-gray-850 border-gray-800 text-start">
                <form @submit.prevent="onSubmit">
                  <div class="form-group">
                    <input
                      class="form-control bg-gray-850 border-gray-800"
                      type="text"
                      v-model="email"
                      v-bind="emailAttrs"
                      placeholder="User name"
                    />
                    <span class="text-danger">{{ errors.email }}</span>
                  </div>
                  <div class="form-group position-relative">
                    <input
                      class="form-control bg-gray-850 border-gray-800 password"
                      type="password"
                      v-model="password"
                      v-bind="passwordAttrs"
                      placeholder="Password"
                    /><span class="viewpass"></span>
                    <span class="text-danger">{{ errors.password }}</span>
                  </div>
                  <div class="form-group">
                    <a class="color-white link" href="#">Quên mật khẩu?</a>
                  </div>
                  <div class="form-group">
                    <input
                      class="btn btn-linear color-gray-850 hover-up"
                      type="submit"
                      value="Đăng nhập cho tôi"
                    />
                  </div>
                  <div class="form-group mb-0">
                    <span>Bạn chưa có tài khoản?</span>
                    <NuxtLink class="color-linear" to="/register"> Đăng ký</NuxtLink>
                  </div>
                </form>
              </div>
              <!-- <div class="box-line"><span class="bg-gray-900">Or, sign in with your email</span></div>
                <div class="box-login-gmail bg-gray-850 border-gray-800 hover-up"><a class="btn btn-login-gmail color-gray-500" href="#">Sign in with Google</a></div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
