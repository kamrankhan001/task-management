<script setup>
import useUpdateRecord from "../../../composables/admin/update";

import { ref, reactive } from "vue";
import { FwbInput, FwbButton } from "flowbite-vue";
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
    }
    if(response.error) {
      errors.value = response.error;
    }
  } catch (err) {
    errors.value = err;
  }
}

</script>

<template>
    <div>
        <div class="mb-5">
            <h4 class="text-xl">Change Password</h4>
        </div>
        <form @submit.prevent="changePassword">
            <div class="mb-5">
                <fwb-input
                    label="Old Password"
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
                    label="New Password"
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
            <div>
                <fwb-button color="purple" type="submit">Change</fwb-button>
            </div>
        </form>
    </div>
</template>
