<script setup>
import useUpdateRecord from "../../../composables/admin/update";

import { ref, reactive } from "vue";
import { FwbInput, FwbButton, FwbModal } from "flowbite-vue";
import { useToast } from "vue-toastification";

const props = defineProps(['id']);

const {updateRecord} = useUpdateRecord('/api/admin/reset-password/'+props.id);

const toast = useToast();
const errors = ref('');

const formFields = reactive({
    oldPassword: "",
    password: "",
    password_confirmation: "",
});

const changePassword = async () => {
  try {
    const response = await updateRecord(formFields);
    if(response.success) {
      toast.success('Password Updated Successfully');
      formFields.oldPassword = '';
      formFields.password = '';
      formFields.password_confirmation = "";
      closeModal();
    }
    if(response.error) {
      errors.value = response.error;
    }
  } catch (err) {
    errors.value = err.error;
  }
}

const isShowModal = ref(false);

const closeModal = () => {
  isShowModal.value = false;
    errors.value = "";
    formFields.oldPassword = '';
    formFields.password = '';
    formFields.password_confirmation = "";
}

const showModal = () => {
    isShowModal.value = true;
}

</script>

<template>
    <div>
      <a href="#" color="purple" @click="showModal" class="underline text-blue-800">
          Change Password
      </a>
      <fwb-modal v-if="isShowModal" @close="closeModal">
        <template #header>
          <div class="flex items-center text-lg uppercase">
              Change Password
          </div>
        </template>
        <template #body>
          <form @submit.prevent="changePassword" id="passwordChange" class="text-left">
              <div class="mb-5">
                  <fwb-input
                      label="Old Password *"
                      v-model="formFields.oldPassword"
                      type="password"
                      placeholder="Old Password"
                  />
                  <span v-if="errors.oldPassword" class="text-red-600">
                    {{ errors.oldPassword[0] }}
                  </span>
              </div>
              <div class="mb-5">
                  <fwb-input
                      label="New Password *"
                      v-model="formFields.password"
                      type="password"
                      placeholder="New Password"
                  />
                  <span v-if="errors.password" class="text-red-600">
                    {{ errors.password[0] }}
                  </span>
              </div>
              <div class="mb-5">
                  <fwb-input
                      label="Password Confirm"
                      v-model="formFields.password_confirmation"
                      type="password"
                      placeholder="Password Confirm"
                  />
                  <span v-if="errors.password_confirmation" class="text-red-600">
                    {{ errors.password_confirmation[0] }}
                  </span>
              </div>
          </form>
        </template>
        <template #footer>
          <div class="flex justify-between">
              <fwb-button @click="closeModal" color="alternative">
                  Cancel
              </fwb-button>
              <fwb-button type="submit" form="passwordChange" color="purple">
                Change
              </fwb-button>
          </div>
        </template>
      </fwb-modal>
    </div>
</template>
