<template>
  <div>
    <div class="d-flex justify-content-between">
      <h4>Media</h4>
      <button @click="showModal = true" class="btn btn-success mb-3">
        Add New <font-awesome-icon icon="plus" />
      </button>
    </div>
    <Loading :isLoading="loading" />
    <table v-show="!isLoading" class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>size</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(file, index) in media" :key="index">
          <td>{{ file.id }}</td>
          <td>{{ `${IMAGE_URL}/${file.file_name}` }}
            <font-awesome-icon @click="handleCopy(`${IMAGE_URL}/${file.path}`)" icon="fa-copy" color="#ccc" class="me-2 btn-copy" />
          </td>
          <td>{{ file.size }}</td>
          <td>
            <img :src="`${IMAGE_URL}/${file.path}`" alt="file" style="width: 50px; height: 50px"  />
          </td>
          <td>
            <button class="btn btn-danger btn-sm" @click="removeMedia(file)">Delete</button>
            <button class="btn btn-info btn-sm" @click="editMedia(file)">Edit</button>
          </td>
        </tr>
      </tbody>
    </table>
    <nav v-if="pagination.last_page > 1" aria-label="Page pagination example">
      <ul v-show="!navigation" class="pagination">
        <li class="page-item">
          <a class="page-link" href="#" @click.prevent="fetchMedia(pagination.current_page - 1)">Previous</a>
        </li>
        <li class="page-item" v-for="page in pagination.last_page" :key="page">
          <a class="page-link" href="#" @click.prevent="fetchMedia(page)">{{ page }}</a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#" @click.prevent="fetchMedia(pagination.current_page + 1)">Next</a>
        </li>
      </ul>
    </nav>
    <Modal v-model="showModal" :title="modalTitle" content="This is a simple modal demonstration!" size="small"
      :showDefaultActions="true" @confirm="handleConfirm" @close="handleClose">
      <template #default>
        <form @submit.prevent="handleSubmit">
          <div>
            <label for="name">Name</label>
            <input class="form-control" id="name" v-model="name" v-bind="nameAttrs" />
            <span class="text-danger">{{ errors.name }}</span>
            <div class="file-upload-wrapper">
              <input type="file" id="fileInput" @change="onFileChange" />
              <label for="fileInput" class="custom-file-upload">Choose a file</label>
              <span class="file-name">{{ selectedFileName }}</span>
            </div>
            <div class="position-relative">
                  <!-- Image -->
                  <img class="mt-3 img-thumbnail image-preview" v-if="imagePreview" :src="imagePreview" alt="Image preview"/>
                  <button
                    v-if="imagePreview"
                    type="button"
                    class="btn-close"
                    aria-label="Remove"
                    @click="removeImage(post)"
                  >
                  </button>
                </div>
          </div>
        </form>
      </template>
      <template #footer>
        <form @submit.prevent="onSubmit">
          <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary me-2" @click="handleClose">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary">{{ isEditing ? 'Update' : 'Add' }}</button>
          </div>
        </form>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import newService from '@/services/NewService';
import { notify } from "@kyvg/vue3-notification";
import Modal from '@/components/Modal.vue'
import Loading from "@/components/Loading.vue";
import { useForm } from "vee-validate";
import * as yup from "yup";

const media = ref([]);
const newMedia = ref('');
const oldMedia = ref('');
const showModal = ref(false)
const loading = ref(false)
const limit = ref(10)
const isEditing = ref(false)
const modalTitle = computed(() => (isEditing.value ? 'Edit User' : 'Create Post'));
const selectMedia = ref(null);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  next_page_url: null,
  prev_page_url: null,
  links: [],
})
const selectedFileName = ref(null);
const imagePreview = ref(null);
const IMAGE_URL =  process.env.VUE_APP_STATIC_URL;

const { handleSubmit, errors, defineField } = useForm({
  validationSchema: yup.object({
    name: yup.string(),
  }),
});

const [name, nameAttrs] = defineField('name');

onMounted(async () => {
  fetchMedia();
});

const onSubmit = handleSubmit(async (formValues) => {

  if (isEditing.value) {
    await newService.updateMedia(selectMedia.value.id, formValues);
    await fetchMedia();
    notify({
      title: "Media",
      text: "Media updated successfully!",
    });
  } else {
    const formData = new FormData();
    formData.append('media', selectMedia.value);
    await newService.addMedia(formData);
    await fetchMedia();
    notify({
      title: "Media",
      text: "Mediaadded successfully!",
    });
  }
  resetForm();
});

const removeMedia = async (category) => {
  console.log(category.id)
  if (confirm(`Are you sure you want to delete "${category.name}"?`)) {
    await newService.deleteMedia(category.id);
    await fetchMedia();
    notify({
      title: "Media",
      text: "Mediadeleted successfully!",
    });
  }
};

const editMedia = async (category) => {
  isEditing.value = true;
  selectMedia.value = category;
  name.value = category.name;
  showModal.value = true;
};

const handleClose = () => {
  resetForm();
}

const fetchMedia = async (page) => {
  loading.value = true;
  const queryString = {
    limit: limit.value,
    page: page || pagination.value.current_page,
  }
  const response = await newService.getMedia(queryString);
  media.value = response.data.data;
  pagination.value = {
    current_page: response.data.current_page,
    last_page: response.data.last_page,
    total: response.data.total,
  };
  loading.value = false;
}

const resetForm = () => {
  name.value = '';
  isEditing.value = false;
  selectMedia.value = null;
  showModal.value = false;

}

const onFileChange = (event) => {
  const file = event.target.files[0];
  selectMedia.value = file;
  name.value = file.name;

  if (!file) {
    console.error('No file selected');
    return;
  }
  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreview.value = e.target.result; // Set the preview value
    console.log(e.target.result); // Log the base64 encoded image
  };

  reader.readAsDataURL(file); // Read the file as a data URL
};

const handleCopy = async (file) => {
  try {
    await navigator.clipboard.writeText(file);
    notify({
      title: "Media",
      text: "Media copied successfully!",
    });
  } catch (err) {
    console.error('Failed to copy: ', err);
  }
}


</script>

<style scoped>

.file-upload-wrapper {
  position: relative;
  display: inline-block;
  margin-top: 10px;
}

.file-upload-wrapper input[type="file"] {
  /* Visually hide the file input, but keep it accessible. */
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
  width: 0;
  height: 0;
}

.custom-file-upload {
  background-color: #4f46e5; /* Your desired background color */
  color: #fff;
  padding: 10px 20px;
  cursor: pointer;
  border-radius: 4px;
  font-size: 14px;
  display: inline-block;
  border: none;
  transition: background-color 0.3s ease;
  margin-top: 20;
}

.custom-file-upload:hover {
  background-color: #4338ca; /* Darker shade on hover */
}

.file-name {
  margin-left: 10px;
  font-size: 14px;
  color: #333;
  vertical-align: middle;
 
}

/* Optional: you might want to handle cases when no file is chosen */
.file-name:empty::before {
  content: "";
  color: #999;
}

.image-preview {
  max-width: 100px;
  height: 100px;
  object-fit: cover;
}
.btn-copy {
  cursor: pointer;
}

</style>