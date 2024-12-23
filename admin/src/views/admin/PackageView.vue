<template>
  <div>
    <div class="d-flex justify-content-between">
      <h4>Packages</h4>
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
          <th>Duration</th>
          <th>Price</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in packages" :key="index">
          <td>{{ item.id }}</td>
          <td>{{ item.name }}</td>
          <td>{{ item.duration }} days</td>
          <td>${{ item.price }}</td>
          <td>
            <button class="btn btn-danger btn-sm" @click="removePackage(item)">Delete</button>
            <button class="btn btn-info btn-sm" @click="editPackage(item)">Edit</button>
          </td>
        </tr>
      </tbody>
    </table>
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
            <label for="name">Duration</label>
            <input class="form-control" id="duration" v-model="duration" v-bind="durationAttrs" />
            <span class="text-danger">{{ errors.duration }}</span>
          </div>
          <div>
            <label for="name">Price</label>
            <input class="form-control" id="price" v-model="price" v-bind="priceAttrs" />
            <span class="text-danger">{{ errors.price }}</span>
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


const packages = ref([]);
const newPackage = ref('');
const showModal = ref(false)
const loading = ref(false)
const isEditing = ref(false)
const modalTitle = computed(() => (isEditing.value ? 'Edit User' : 'Create Post'));
const selectedPackage = ref(null);

const { handleSubmit, errors, defineField } = useForm({
  validationSchema: yup.object({
    name: yup.string().required(),
    duration: yup.number().min(30).required(),
    price: yup.number().min(10).required(),
  }),
});

const [name, nameAttrs] = defineField('name');
const [duration, durationAttrs] = defineField('duration');
const [price, priceAttrs] = defineField('price');

onMounted(async () => {
  fetchPackages();
});

const onSubmit = handleSubmit(async (formValues) => {
  if (isEditing.value) {
    await newService.updatePackages(selectedPackage.value.id, formValues);
    await fetchPackages();
    notify({
      title: "Package",
      text: "Package updated successfully!",
    });
    resetForm();
    return;
  } else {
    await newService.addPackages(formValues);
    await fetchPackages();
    newPackage.value = '';
    notify({
      title: "Package",
      text: "added successfully!",
      type: "success",
    });
    resetForm();
  }


});

const removePackage = async (item) => {
  if (confirm(`Are you sure you want to delete "${item.name}"?`)) {
    await newService.deletePackages(item.id);
    await fetchPackages();
    notify({
      title: "Package",
      text: "Package deleted successfully!",
    });
  }
};

const editPackage = async (packages) => {

  // const newName = prompt('Enter new name', packages.name);
  // if (newName) {
  //   await newService.updatePackage(packages.id, {name: newName});
  //   await fetchPackages();
  //   notify({
  //     title: "Package",
  //     text: "Package updated successfully!",
  //   });
  // }

  isEditing.value = true
  showModal.value = true;
  selectedPackage.value = packages;
  name.value = packages.name;
  duration.value = packages.duration;
  price.value = packages.price;


};

const handleClose = () => {
  resetForm();
}

const fetchPackages = async (page) => {
  loading.value = true;
  const response = await newService.getPackages();
  packages.value = response.data;
  loading.value = false;
}


const resetForm = () => {
  name.value = '';
  duration.value = '';
  price.value = '';
  isEditing.value = false;
  selectedPackage.value = null;
  showModal.value = false;

}

</script>
