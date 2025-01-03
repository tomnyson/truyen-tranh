<template>
  <div>
    <div class="d-flex justify-content-between">
      <h4>Categories</h4>
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
          <th>Image</th>
          <th>description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(category, index) in categories" :key="index">
          <td>{{ category.id }}</td>
          <td>{{ category.name }}</td>
          <td>
            <img v-show="category.image !== null" :src="category.image" alt="file" style="width: 50px; height: 50px" />
          </td>
          <td>{{ category.description }}</td>
          <td>
            <button class="btn btn-danger btn-sm" @click="removeCategory(category)">Delete</button>
            <button class="btn btn-info btn-sm" @click="editCategory(category)">Edit</button>
          </td>
        </tr>
      </tbody>
    </table>
    <nav v-if="pagination.last_page > 1" aria-label="Page pagination example">
      <ul v-show="!navigation" class="pagination">
        <li class="page-item">
          <a class="page-link" href="#" @click.prevent="fetchCategories(pagination.current_page - 1)">Previous</a>
        </li>
        <li class="page-item" v-for="page in pagination.last_page" :key="page">
          <a class="page-link" href="#" @click.prevent="fetchCategories(page)">{{ page }}</a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#" @click.prevent="fetchCategories(pagination.current_page + 1)">Next</a>
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
          </div>
          <div>
            <label for="name">Description</label>
            <input class="form-control" id="name" v-model="description" v-bind="descriptionAttrs" />
            <span class="text-danger">{{ errors.description }}</span>
          </div>
          <div>
            <label for="name">Image</label>
            <input class="form-control" id="name" v-model="image" v-bind="imageAttrs" />
            <span class="text-danger">{{ errors.image }}</span>
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

const categories = ref([]);
const showModal = ref(false)
const loading = ref(false)
const limit = ref(10)
const isEditing = ref(false)
const modalTitle = computed(() => (isEditing.value ? 'Edit Category' : 'Create Category'));
const selectCategory = ref(null);

const pagination = ref({
  current_page: 1,
  last_page: 1,
  next_page_url: null,
  prev_page_url: null,
  links: [],
})

const { handleSubmit, errors, defineField } = useForm({
  validationSchema: yup.object({
    name: yup.string().required("Name is required."),
    image: yup.string().nullable().optional().default(null),
    description: yup.string().nullable().optional().max(255, "Description must be at most 255 characters.").default(null),
  }),
});

const [name, nameAttrs] = defineField('name');
const [image, imageAttrs] = defineField('image');
const [description, descriptionAttrs] = defineField('description');

onMounted(async () => {
  fetchCategories();
});

const onSubmit = handleSubmit(async (formValues) => {

  if (isEditing.value) {
    await newService.updateCategory(selectCategory.value.id, formValues);
    await fetchCategories();
    notify({
      title: "Category",
      text: "Category updated successfully!",
    });
  } else {
    await newService.addCategory(formValues);
    await fetchCategories();
    notify({
      title: "Category",
      text: "Category added successfully!",
    });
  }
  resetForm();
});

const removeCategory = async (category) => {
  if (confirm(`Are you sure you want to delete "${category.name}"?`)) {
    await newService.deleteCategory(category.id);
    await fetchCategories();
    notify({
      title: "Category",
      text: "Category deleted successfully!",
    });
  }
};

const editCategory = async (category) => {
  isEditing.value = true;
  selectCategory.value = category;
  name.value = category.name;
  description.value = category.description;
  image.value = category.image;
  showModal.value = true;
};

const handleClose = () => {
  resetForm();
}

const fetchCategories = async (page) => {
  loading.value = true;
  const queryString = {
    limit: limit.value,
    page: page || pagination.value.current_page,
  }
  const response = await newService.getCategories(queryString);
  categories.value = response.data.data;
  pagination.value = {
    current_page: response.data.current_page,
    last_page: response.data.last_page,
    total: response.data.total,
  };
  loading.value = false;
}

const resetForm = () => {
  name.value = '';
  description.value = '';
  image.value = '';
  isEditing.value = false;
  selectCategory.value = null;
  showModal.value = false;


}

</script>
