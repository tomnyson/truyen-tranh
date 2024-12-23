<!-- pages/index.vue -->
<script setup lang="ts">
definePageMeta({ layout: 'default' })


import { useRouter } from 'vue-router';
import { useForm } from "vee-validate";
import * as yup from "yup";

const router = useRouter();
const { handleSubmit, errors, defineField } = useForm({
  validationSchema: yup.object({
    email: yup.string().required().email(),
    password: yup.string().min(8).required(),
    name: yup.string().required(),
    password_confirmation: yup.string().min(8).required().oneOf([yup.ref('password')], 'Passwords must match'),
  }),
});

const [email, emailAttrs] = defineField('email');
const [password, passwordAttrs] = defineField('password');
const [name, nameAttrs] = defineField('name');
const [password_confirmation, passwordConfirmationAttrs] = defineField('password_confirmation');

const onSubmit = handleSubmit(async (formValues) => {
  try {
    const response = await fetch('/api/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formValues),
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || 'An error occurred during login.');
    }
    const data = await response.json();

    console.log('Login successful:', data);
    localStorage.setItem('authToken', data.access_token);
    router.push('/');

  } catch (error: any) {
    console.error('Login failed:', error.message);

    alert(error.message);
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
                <h2 class="color-linear d-inline-block">Register</h2>
              </div>
              <div class="box-form-login pb-50">
                <div class="form-login bg-gray-850 border-gray-800 text-start">
                  <form @submit.prevent="onSubmit">
                    <div class="form-group">
                      <input class="form-control bg-gray-850 border-gray-800" type="text" placeholder="Full name" v-model="name" v-bind="nameAttrs">
                      <span class="text-danger">{{ errors.name }}</span>
                    </div>
                    <div class="form-group">
                      <input class="form-control bg-gray-850 border-gray-800" type="text" placeholder="Email" v-model="email" v-bind="emailAttrs">
                      <span class="text-danger">{{ errors.email }}</span>
                    </div>
                    <div class="form-group position-relative">
                      <input class="form-control bg-gray-850 border-gray-800 password" type="password" placeholder="Password" v-model="password" v-bind="passwordAttrs"><span class="viewpass"  ></span>
                      <span class="text-danger">{{ errors.password }}</span>
                    </div>
                    <div class="form-group position-relative">
                      <input class="form-control bg-gray-850 border-gray-800 password" type="password" placeholder="Confirm password" v-model="password_confirmation" v-bind="passwordConfirmationAttrs"><span class="viewpass"></span>
                      <span class="text-danger">{{ errors.password_confirmation }}</span>
                    </div>
                    <div class="form-group">
                      <input class="btn btn-linear color-gray-850 hover-up" type="submit" value="Create an account">
                    </div>
                    <div class="form-group mb-0"><span>Already have an account?</span><NuxtLink class="color-linear" :to="`/login`"> Sign In</NuxtLink></div>
                  </form>
                </div>
                <!-- <div class="box-line"><span class="bg-gray-900">Or, sign up with your email</span></div> -->
                <!-- <div class="box-login-gmail bg-gray-850 border-gray-800 hover-up"><a class="btn btn-login-gmail color-gray-500" href="#">Sign up with Google</a></div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
</template>