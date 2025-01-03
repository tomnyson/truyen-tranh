<script lang="ts" setup>
import { computed, ref } from 'vue';
import { getMedia, formatDate } from '@/utils/index';
import { useAuth } from '#imports'; 
import { useForm } from "vee-validate";
import * as yup from "yup";
import { toast } from 'vue3-toastify';

interface Category {
  id: number;
  title: string;
  image?: string;
}

interface Comment {
  id: number;
  authorName: string;
  authorImage?: string;
  content: string;
  created_at: string;
}

interface Post {
  id: number;
  title: string;
  authorName: string;
  authorImage?: string;
  coverImage?: string;
  content: string;
  tags: Category[];
  comments: Comment[];
  created_at: string;
}

definePageMeta({ layout: 'default', auth: false, title: 'Trang chủ' });

const route = useRoute();

const { status, token, data: session } = useAuth();
const isLoggedIn = computed(() => status.value === 'authenticated');
const postId = computed(() => route.params.id as string);
const reply = ref(false);
const selectedComment = ref(null);

const { handleSubmit, errors, defineField } = useForm({
  validationSchema: yup.object({
    content: yup.string().required('Nội dung không được để trống'),
  }),
});

// const { handleSubmit: handleSubmitReply, errors: errorReply, defineField: defineFieldReply } = useForm({
//   validationSchema: yup.object({
//     content: yup.string().required('Nội dung không được để trống'),
//   }),
// });

const [content, contentAttrs] = defineField('content');

const { data: post, pending, error } = await useFetch<Post>(`/api/posts/${postId.value}`, {
  headers: {
    'Content-Type': 'application/json',
    Authorization: token.value ? `Bearer ${token.value}` : undefined,
  },
});

// URL for comments API
const url = computed(() => `/api/comments?postId=${postId.value}`);

// Fetch comments
const { 
  data: comments, 
  pending: isPending, 
  error: errorComment, 
  refresh
} = await useFetch<Comment[]>(url, {
  headers: {
    'Content-Type': 'application/json',
    Authorization: token.value ? `${token.value}` : undefined,
  },
});

// Submit a comment and refresh comments
const onSubmit = handleSubmit(async (formValues) => {

  try {

    let payload = {
      content: formValues.content,
      user_id: session.value?.user.id,
      post_id: postId.value,
    };

    if(selectedComment.value) {
      payload.parent_id = selectedComment.value.id;
    }
   

    // Post the comment
    const response = await useFetch(`/api/comments`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: token.value ? `${token.value}` : undefined,
      },
      body: payload,
    });
    await refresh();
    toast.success('Đăng bình luận thành công.');
    reply.value = false;
    selectedComment.value = null;
    content.value = '';
  } catch (error: any) {
    console.error('Comment submission failed:', error.message);
    toast.error('Đăng bình luận thất bại, vui lòng thử lại.');
  }
});

// Set page meta
useHead({
  title: post.value?.title || 'GenZ - Personal Blog Template',
  meta: [
    {
      name: 'description',
      content: 'GenZ - Personal Blog Template',
    },
  ],
});

const enableRelyModel = (comment) => {
  reply.value = !reply.value;
  selectedComment.value = comment;
};

</script>

<template>
  <main class="main">
    <div class="cover-home3">
      <div class="container">
        <div class="row">
          <div class="col-xl-1"></div>
          <div class="col-xl-10 col-lg-12">
            <div class="pt-30 border-bottom border-gray-800 pb-20">
              <div class="box-breadcrumbs">
                <ul class="breadcrumb">
                  <li><a class="home" href="index.html">Home</a></li>
                  <li><a href="blog-archive.html">Blog</a></li>
                  <li>
                    <span>{{ post?.title || 'unknow' }}</span>
                  </li>
                </ul>
              </div>
            </div>
            <div class="row mt-50">
              <div class="col-lg-6">
                <h2 class="color-linear mb-30">{{ post?.title || 'unknow' }}</h2>
                <div class="box-author mb-20">
                  <img src="assets/imgs/page/about/author2.png" alt="Genz" />
                  <div class="author-info">
                    <h6 class="color-gray-700">{{ post?.user?.name }}</h6>
                    <span
                      class="color-gray-700 text-sm mr-30"
                      >{{ formatDate(post?.created_at) }}</span
                    ><span class="color-gray-700 text-sm">3 mins to read</span>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <img class="img-bdrd-16 image-detail" :src="getMedia(post?.image)" alt="Genz" />
              </div>
            </div>
            <div class="row mt-50">
              <div class="col-lg-9">
                <div class="content-detail border-gray-800">
                  <div v-html="post?.content"></div>
                </div>
                <div class="box-tags">
                  <NuxtLink
                    class="btn btn-tags bg-gray-850 border-gray-800 mr-10 hover-up"
                    :to="`/tags/${tag.id}`"
                    v-for="tag in post?.tags"
                    :key="tag.id"
                    >#{{ tag.name }}</NuxtLink
                  >
                </div>
                <div class="box-comments border-gray-800">
                  <h3 class="text-heading-2 color-gray-300">Bình luận</h3>
                  <p v-show="comments.length==0" class="no-comment text-xl  mt-5">không có bình luận</p>
                  <div class="list-comments-single" v-for="comment in comments" :key="comment.id">
                    <div class="item-comment" >
                      <div class="comment-left">
                        <div class="box-author mb-20">
                          <img src="assets/imgs/page/single/author.png" alt="Genz" />
                          <div class="author-info">
                            <h6 class="color-gray-700">{{ comment.user.name }}</h6>
                            <span class="color-gray-700 text-sm mr-30">{{ formatDate(comment.created_at) }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="comment-right">
                        <div
                          class="text-comment text-xl color-gray-500 bg-gray-850 border-gray-800"
                        >
                          {{ comment.content }}
                        </div>
                        <div class="d-flex justify-content-end">
                          <button v-show="isLoggedIn" class="btn btn-text color-white-500" @click="enableRelyModel(comment)">Reply</button>
                        </div>
                        <div class="box-forms">
                    <form @submit.prevent="onSubmit" v-show="reply && selectedComment.id === comment.id">
                      <textarea
                        class="form-control bg-gray-850 border-gray-800 bdrd16 color-gray-500"
                        name="comment"
                        rows="1"
                        v-model="content"
                        v-bind="contentAttrs"
                        placeholder="Viết bình luận"
                      ></textarea>
                      <div class="row mt-20">
                        <span class="text-danger mt-2">{{ errors.content }}</span>
                        <div class="col-sm-6 mb-20">
                        </div>
                        <div class="col-sm-6 text-end">
                          <button type="submit" class="btn btn-linear">Đăng bình luận</button>
                        </div>
                      </div>
                    </form>
                  </div>
                       
                      </div>
                     
                    </div>
                    <div v-show="comment.replies" class="item-comment item-comment-sub" v-for="reply in comment.replies" :key="reply.id">
                      <div class="comment-left">
                        <div class="box-author mb-20">
                          <img src="assets/imgs/page/single/author3.png" alt="Genz" />
                          <div class="author-info">
                            <h6 class="color-gray-700">{{ reply.user.name }}</h6>
                            <span class="color-gray-700 text-sm mr-30">{{formatDate(reply.created_at)}}</span>
                          </div>
                        </div>
                      </div>
                      <div class="comment-right">
                        <div
                          class="text-comment text-xl color-gray-500 bg-gray-850 border-gray-800"
                        >
                         {{reply.content}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-form-comments mb-50" v-show="isLoggedIn">
                  <h4 class="text-heading-4 color-gray-300 mb-40">Để lại bình luận</h4>
                  <div class="box-forms">
                    <form @submit.prevent="onSubmit">
                      <textarea
                        class="form-control bg-gray-850 border-gray-800 bdrd16 color-gray-500"
                        name="comment"
                        rows="5"
                        v-model="content"
                        v-bind="contentAttrs"
                        placeholder="Viết bình luận"
                      ></textarea>
                      <div class="row mt-20">
                        <span class="text-danger mt-2">{{ errors.content }}</span>
                        <div class="col-sm-6 mb-20">
                        </div>
                        <div class="col-sm-6 text-end">
                          <button type="submit" class="btn btn-linear">Đăng bình luận</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 pl-40">
                <div class="box-share border-gray-800">
                  <h6 class="d-inline-block color-gray-500 mr-10">Share</h6>
                  <a class="icon-media icon-fb" href="#"></a
                  ><a class="icon-media icon-tw" href="#"></a
                  ><a class="icon-media icon-printest" href="#"></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
.image-detail {
  max-width: 250px;
}
.no-comment {
  padding: 10px;
  border-radius: 5px;
  text-align: center;
  margin-top: 10px;
}
</style>
