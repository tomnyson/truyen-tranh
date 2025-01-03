<template>
  <div>
    <!-- Header Row -->
    <div class="d-flex justify-content-between">
      <h4>Posts</h4>
      <button @click="openCreateModal" class="btn btn-success mb-3">
        Add New <font-awesome-icon icon="plus" />
      </button>
    </div>

    <!-- Loading Indicator -->
    <Loading :isLoading="loading" />

    <!-- Table of Posts -->
    <table v-show="!isLoading" class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Category</th>
          <th>Tags</th>
          <th>Image</th>
          <th>pinned to home</th>
          <th>Create At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(post, index) in posts" :key="index">
          <td>{{ post.id }}</td>
          <td>{{ post.title }}</td>
          <td>{{ post.category.name }}</td>
          <td>
            <span v-for="(tag, i) in post.tags" :key="i" class="badge bg-secondary me-2">
              {{ tag.name }}
            </span>
          </td>
          <td>
            <img
              v-if="post.image"
              :src="`${image_url}/${post.image}`"
              alt="Post Image"
              class="img-thumbnail"
              width="100"
            />
          </td>
          <td>
            <span
              :class="[
              'badge me-2',
              post.is_pin ? 'bg-success' : 'bg-primary'
            ]"
            >
              {{ post.is_pin ? 'Pinned' : 'Not pinned' }}
            </span>
          </td>
          <td>{{ new Date(post.created_at).toLocaleString() }}</td>
          <td>
            <button class="btn btn-danger btn-sm" @click="removePost(post)">Delete</button>
            <button class="btn btn-info btn-sm" @click="openEditModal(post)">Edit</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <nav v-if="pagination.last_page > 1" aria-label="Page pagination example">
      <ul v-show="!navigation" class="pagination">
        <li class="page-item">
          <a class="page-link" href="#" @click.prevent="fetchPosts(pagination.current_page - 1)"
            >Previous</a
          >
        </li>
        <li class="page-item" v-for="page in pagination.last_page" :key="page">
          <a class="page-link" href="#" @click.prevent="fetchPosts(page)">{{ page }}</a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#" @click.prevent="fetchPosts(pagination.current_page + 1)"
            >Next</a
          >
        </li>
      </ul>
    </nav>

    <!-- Modal -->
    <Modal
      v-model="showModal"
      :title="modalTitle"
      size="large"
      :showDefaultActions="true"
      @confirm="handleConfirm"
      @close="handleClose"
    >
      <template #default>
        <!-- Main Form -->
        <form @submit.prevent="handleSubmit">
          <!-- Pill Nav -->
          <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button
                class="nav-link active"
                id="pills-home-tab"
                data-bs-toggle="pill"
                data-bs-target="#pills-home"
                type="button"
                role="tab"
                aria-controls="pills-home"
                aria-selected="true"
              >
                Post config
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button
                class="nav-link"
                id="pills-profile-tab"
                data-bs-toggle="pill"
                data-bs-target="#pills-profile"
                type="button"
                role="tab"
                aria-controls="pills-profile"
                aria-selected="false"
              >
                Seo config
              </button>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content" id="pills-tabContent">
            <!-- Post config tab -->
            <div
              class="tab-pane fade show active"
              id="pills-home"
              role="tabpanel"
              aria-labelledby="pills-home-tab"
            >
              <!-- Row 1: Title & Category -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="title" class="form-label">Title</label>
                  <input
                    type="text"
                    id="title"
                    v-model="title"
                    v-bind="titleAttrs"
                    class="form-control"
                    placeholder="Enter post title"
                    required
                  />
                  <span class="text-danger">{{ errors.title }}</span>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="category" class="form-label">Category</label>
                  <v-select
                    :options="categories.map((cat) => ({
                    label: cat.name,
                    value: cat.id,
                  }))"
                    v-model="category_id"
                    v-bind="categoryIdAttrs"
                    placeholder="Select a category"
                  />
                  <span class="text-danger">{{ errors.category_id }}</span>
                </div>
              </div>

              <!-- Row 2: Tags & Image -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="tags" class="form-label">Tags</label>
                  <v-select
                    :options="tagLists.map((tag) => ({
                    label: tag.name,
                    value: Number(tag.id),
                  }))"
                    v-model="tags"
                    v-bind="tagsAttrs"
                    multiple
                    placeholder="Select hash tags"
                  />
                  <span class="text-danger">{{ errors.tags }}</span>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="image" class="form-label">Image</label>
                  <input
                    type="file"
                    id="image"
                    class="form-control"
                    @change="handleImageUploadPost"
                  />
                  <div class="position-relative">
                    <img
                      v-if="imagePreview"
                      class="mt-3 img-thumbnail image-preview"
                      :src="imagePreview"
                      alt="Image preview"
                    />
                    <button
                      v-if="imagePreview"
                      type="button"
                      class="btn-close"
                      aria-label="Remove"
                      @click="removeImage"
                    ></button>
                  </div>
                </div>
              </div>

              <!-- Row 3: isPin & status -->
              <div class="row" style="margin-left: 6px;">
                <div class="col-md-6 mb-3 form-check">
                  <input
                    type="checkbox"
                    id="isPaid"
                    class="form-check-input"
                    v-model="is_pin"
                    v-bind="isPinAttrs"
                  />
                  <label for="isPaid" class="form-check-label"> Pinned to home </label>
                  <span class="text-danger">{{ errors.type }}</span>
                </div>
                <div class="col-md-6 mb-3">
                  <v-select
                    :options="statusArr.map((st) => ({
                    label: st.label,
                    value: st.value,
                  }))"
                    v-model="status"
                    v-bind="statusAttrs"
                    placeholder="Select post status"
                  />
                  <span class="text-danger">{{ errors.status }}</span>
                </div>
              </div>

              <!-- Row 4: Content -->
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="content" class="form-label">Content</label>
                  <Editor
                    v-model="content"
                    v-bind="contentAttrs"
                    :api-key="VUE_API_EDITOR_KEY"
                    :init="{
      height: 400,
      plugins: [
        'lists',
        'link',
        'image',
        'table',
        'code',
        'help',
        'wordcount'
      ],
      toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image',
      automatic_uploads: true,
      images_upload_credentials: true,
      images_reuse_filename: true,
      convert_urls: false,
      file_picker_types: 'file image media',
      images_upload_url: 'http://localhost:8000/api/media/upload',
      images_upload_handler: images_upload_handler,
      // Additional recommended settings
      menubar: true,
      relative_urls: false,
      remove_script_host: false,
      extended_valid_elements: 'img[class|src|border=0|alt|title|width|height|style]',
      image_dimensions: false,
      // Better image sizing defaults
      image_advtab: true,
      image_uploadtab: true,
      images_file_types: 'jpeg,jpg,png,gif',
      // Add image styles
      style_formats: [
        {
          title: 'Image styles',
          items: [
            { title: 'Full width', selector: 'img', styles: { 'width': '100%' } },
            { title: 'Half width', selector: 'img', styles: { 'width': '50%' } }
          ]
        }
      ]
    }"
                  />

                  <span class="text-danger">{{ errors.content }}</span>
                </div>
              </div>
            </div>

            <!-- SEO config tab -->
            <div
              class="tab-pane fade"
              id="pills-profile"
              role="tabpanel"
              aria-labelledby="pills-profile-tab"
            >
              <form>
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <label for="title" class="form-label">Meta title</label>
                    <input
                      type="text"
                      id="title"
                      v-model="meta_title"
                      v-bind="metaTitleAttrs"
                      class="form-control"
                      placeholder="Enter meta title"
                      required
                    />
                    <span class="text-danger">{{ errors.meta_title }}</span>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label for="title" class="form-label">Meta description</label>
                    <input
                      type="text"
                      id="title"
                      v-model="meta_description"
                      v-bind="metaDescriptionAttrs"
                      class="form-control"
                      placeholder="Enter meta description"
                      required
                    />
                    <span class="text-danger">{{ errors.meta_description }}</span>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label for="title" class="form-label">Meta Keyword</label>
                    <input
                      type="text"
                      id="title"
                      v-model="meta_keywords"
                      v-bind="metaKeywordsAttrs"
                      class="form-control"
                      placeholder="Enter meta keyword"
                      required
                    />
                    <span class="text-danger">{{ errors.meta_keyword }}</span>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </form>
      </template>

      <!-- Footer: the actual submission buttons -->
      <template #footer>
        <form @submit.prevent="onSubmit">
          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary me-2" @click="handleClose">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary">
              {{ isEditing ? "Update Post" : "Add Post" }}
            </button>
          </div>
        </form>
      </template>
    </Modal>
  </div>
</template>

<script setup>
/* -----------------------------
  Imports
----------------------------- */
import { ref, onMounted, computed } from 'vue';
import newService from '@/services/NewService';
import { notify } from '@kyvg/vue3-notification';
import Modal from '@/components/Modal.vue';
import Loading from '@/components/Loading.vue';
import { useForm } from 'vee-validate';
import * as yup from 'yup';
import Editor from '@tinymce/tinymce-vue';
import axios from 'axios';

/* -----------------------------
  Constants / Config
----------------------------- */
const image_url = process.env.VUE_APP_STATIC_URL;
const VUE_API_EDITOR_KEY = process.env.VUE_APP_EDITOR_KEY;

const statusArr = [
  { label: 'draft', value: 'draft' },
  { label: 'review', value: 'review' },
  { label: 'published', value: 'published' },
];

/* -----------------------------
  Reactive State
----------------------------- */
const posts = ref([]);
const showModal = ref(false);
const loading = ref(false);
const limit = ref(10);
const categories = ref([]);
const tagLists = ref([]);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0,
});

const isEditing = ref(false);
const editedPost = ref(null);

const image = ref(null);
const imagePreview = ref(null);

/* -----------------------------
  Computed
----------------------------- */
const modalTitle = computed(() =>
  isEditing.value ? 'Edit Post' : 'Create Post'
);

/* -----------------------------
  Vee-Validate Form
----------------------------- */
const { handleSubmit, errors, defineField } = useForm({
  validationSchema: yup.object({
    title: yup.string().required('Title is required'),
    content: yup.string().required('Content is required'),
    category_id: yup
      .object()
      .shape({
        label: yup.string().required(),
        value: yup.string().required(),
      })
      .required('Category is required'),
    tags: yup
      .array()
      .of(
        yup.object().shape({
          label: yup.string().required(),
          value: yup.string().required(),
        })
      )
      .min(1, 'At least one tag is required')
      .required('Tags are required'),
    type: yup.boolean().default(false),
    meta_title: yup.string().max(60).nullable().optional(),
    meta_description: yup.string().max(160).nullable().optional(),
    meta_keyword: yup.string().max(160).nullable().optional(),
    is_pin: yup.boolean().default(false),
    status: yup
      .object()
      .shape({
        label: yup.string().required(),
        value: yup.string().required(),
      })
      .required('status is required'),
  }),
});

// Define each field (two-way binding via useForm)
const [title, titleAttrs] = defineField('title');
const [content, contentAttrs] = defineField('content');
const [category_id, categoryIdAttrs] = defineField('category_id');
const [tags, tagsAttrs] = defineField('tags');
const [type, typeAttrs] = defineField('type');
const [meta_title, metaTitleAttrs] = defineField('meta_title');
const [meta_description, metaDescriptionAttrs] = defineField('meta_description');
const [meta_keywords, metaKeywordsAttrs] = defineField('meta_keyword');
const [is_pin, isPinAttrs] = defineField('is_pin');
const [status, statusAttrs] = defineField('status');

/* -----------------------------
  Lifecycle
----------------------------- */
onMounted(async () => {
  await loadInitialData();
});

/* -----------------------------
  Methods: Data / Fetching
----------------------------- */
async function loadInitialData() {
  // Parallel requests for posts & categories/tags if desired
  await fetchPosts();
  await fetchCategories();
  await fetchTags();
}

async function fetchPosts(page = pagination.value.current_page) {
  loading.value = true;
  const queryString = { limit: limit.value, page };
  const response = await newService.getPosts(queryString);

  posts.value = response.data.data;
  pagination.value.current_page = response.data.current_page;
  pagination.value.last_page = response.data.last_page;
  pagination.value.total = response.data.total;

  loading.value = false;
}

async function fetchCategories() {
  const { data } = await newService.getCategories();
  categories.value = data.data || [];
}

async function fetchTags() {
  const { data } = await newService.getTags();
  tagLists.value = data.data || [];
}

/* -----------------------------
  Methods: Form / Submission
----------------------------- */
function buildFormData() {
  const formData = new FormData();

  formData.append('title', title.value);
  formData.append('content', content.value);
  formData.append('category_id', category_id.value.value);
  tags.value.forEach((tg) => formData.append('tags[]', tg.value));

  if (image.value) {
    formData.append('image', image.value);
  }
  formData.append('type', type.value === true ? 'paid' : 'free');
  formData.append('meta_title', meta_title.value || '');
  formData.append('meta_description', meta_description.value || '');
  formData.append('meta_keywords', meta_keywords.value || '');
  formData.append('is_pin', is_pin.value || false);
  formData.append('status', status.value.value || 'draft');

  return formData;
}

const onSubmit = handleSubmit(async () => {
  const formData = buildFormData();

  if (isEditing.value) {
    formData.append('_method', 'PUT');
    await newService.updatePost(editedPost.value.id, formData);
    notifySuccess('Post updated successfully!');
  } else {
    await newService.addPost(formData);
    notifySuccess('Post added successfully!');
  }

  await fetchPosts();
  resetForm();
});

/* -----------------------------
  Methods: Image Handling
----------------------------- */
function handleImageUploadPost(event) {
  const file = event.target.files[0];
  if (!file) {
    console.error('No file selected');
    return;
  }
  image.value = file;

  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
}

function removeImage() {
  image.value = null;
  imagePreview.value = null;
}

/* -----------------------------
  Methods: Post Actions (Edit, Delete)
----------------------------- */
function openCreateModal() {
  // Just show the modal for a brand-new post
  resetForm();
  showModal.value = true;
}

function openEditModal(post) {
  // Populate form with existing post data
  resetForm();
  editedPost.value = post;
  isEditing.value = true;
  showModal.value = true;

  title.value = post.title;
  content.value = post.content;
  category_id.value = { label: post.category.name, value: post.category.id };
  tags.value = post.tags.map((tg) => ({
    label: tg.name,
    value: tg.id,
  }));
  type.value = post.type === 'paid';
  imagePreview.value = `${image_url}/${post.image}`;
}

async function removePost(post) {
  if (confirm(`Are you sure you want to delete "${post.id}"?`)) {
    await newService.deletePost(post.id);
    notifySuccess('Post deleted successfully!');
    await fetchPosts();
  }
}

async function images_upload_handler(blobInfo) {
  return new Promise(async (resolve, reject) => {
    try {
      // Validate file size (optional, adjust max size as needed)
      const maxSize = 5 * 1024 * 1024; // 5MB
      if (blobInfo.blob().size > maxSize) {
        throw new Error(`File size too large. Max size is ${maxSize / (1024 * 1024)}MB`);
      }

      const formData = new FormData();
      formData.append('file', blobInfo.blob(), blobInfo.filename());

      // Get auth token
      const authToken = localStorage.getItem('authToken');
      if (!authToken) {
        throw new Error('Authentication token not found');
      }

      const response = await axios.post(
        'http://localhost:8000/api/media/upload',
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data',
            'Authorization': `Bearer ${authToken}`,
          },
          onUploadProgress: (progressEvent) => {
            if (progressEvent.total) {
              const percent = Math.round((progressEvent.loaded / progressEvent.total) * 100);
              console.log('Upload progress:', percent + '%');
            }
          },
          // Add timeout and response type options
          timeout: 30000, // 30 seconds
          responseType: 'json'
        }
      );

      // Validate response
      if (!response.data) {
        throw new Error('No response data received');
      }

      if (!response.data.location) {
        throw new Error(`Invalid response format: ${JSON.stringify(response.data)}`);
      }

      // Log success and resolve with location
      console.log('Image uploaded successfully:', response.data.location);
      resolve(`${process.env.VUE_APP_BASE}${response.data.location}`);

    } catch (error) {
      // Handle specific axios errors
      let errorMessage = 'Image upload failed: ';

      if (error.response) {
        // Server responded with error status
        errorMessage += `Server error ${error.response.status}: ${error.response.data?.message || 'Unknown error'}`;
      } else if (error.request) {
        // Request made but no response received
        errorMessage += 'No response from server';
      } else {
        // Error in request configuration
        errorMessage += error.message;
      }

      console.error(errorMessage);
      reject(errorMessage);
    }
  });
}
/* -----------------------------
  Methods: Modal / Reset
----------------------------- */
function handleClose() {
  resetForm();
}

function resetForm() {
  title.value = '';
  content.value = '';
  category_id.value = null;
  tags.value = [];
  type.value = false;
  meta_title.value = '';
  meta_description.value = '';
  meta_keywords.value = '';
  is_pin.value = false;
  status.value = null;

  image.value = null;
  imagePreview.value = null;

  isEditing.value = false;
  editedPost.value = null;
  showModal.value = false;
}

/* -----------------------------
  Helpers
----------------------------- */
function notifySuccess(message) {
  notify({
    title: 'Post',
    text: message,
  });
}
</script>

<style scoped>
.image-preview {
  max-width: 100px;
  height: 100px;
  object-fit: cover;
}

.ql-container {
  height: 300px;
}

label {
  font-weight: bold;
}
</style>
