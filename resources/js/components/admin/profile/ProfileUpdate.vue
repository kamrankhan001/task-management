<script setup>
import useSingleRecord from "../../../composables/admin/single.js";
import useUpdateRecord from "../../../composables/admin/update.js";

import { ref, reactive, onMounted, inject } from "vue";
import { useVuelidate } from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import { FwbInput, FwbButton } from "flowbite-vue";
import { useToast } from "vue-toastification";

const props = defineProps(["id"]);
const user = inject("user");

const { singleRecord } = useSingleRecord("/api/admin/users/" + props.id);
const { updateRecord } = useUpdateRecord("/api/admin/users/" + props.id);

const toast = useToast();
const errors = ref("");
const record = ref({});

const formFields = reactive({
    name: "",
    email: "",
});

const rules = {
    name: { required },
    email: { required, email },
};

const v$ = useVuelidate(rules, formFields);

const fetchRecord = async () => {
    record.value = await singleRecord();
    formFields.name = record.value.name;
    formFields.email = record.value.email;
};

const updateProfile = async () => {
    const validateResult = await v$.value.$validate();
    if (validateResult) {
        try {
            const response = await updateRecord(formFields);
            if (response.success) {
                user.value.name = formFields.name;
                user.value.email = formFields.email;

                toast.success("Profile Updated Successfully");

                let oldData = localStorage.getItem("user");
                if (oldData) {
                    oldData = JSON.parse(oldData);

                    oldData.name = user.value.name;
                    oldData.email = user.value.email;

                    localStorage.setItem("user", JSON.stringify(oldData));
                }
            }
        } catch (error) {
            errors.value = error.message;
        }
    }
};

onMounted(() => {
    fetchRecord();
});
</script>

<template>
    <div>
        <div class="mb-5">
            <h4 class="text-xl">Profile</h4>
        </div>
        <form @submit.prevent="updateProfile">
            <div class="mb-5">
                <fwb-input
                    v-model="formFields.name"
                    placeholder="Enter your name"
                    label="Name"
                />
                <span v-if="v$.name.$error" class="text-red-600">
                    Name
                    {{ v$.name.$errors[0] ? v$.name.$errors[0].$message : "" }}
                </span>
                <span v-if="errors.name" class="text-red-600">{{
                    errors.name[0]
                }}</span>
            </div>
            <div class="mb-5">
                <fwb-input
                    v-model="formFields.email"
                    placeholder="Enter your email"
                    label="Email"
                    type="email"
                />
                <span v-if="v$.email.$error" class="text-red-600">
                    Email
                    {{
                        v$.email.$errors[0] ? v$.email.$errors[0].$message : ""
                    }}
                </span>
                <span v-if="errors.email" class="text-red-600">{{
                    errors.email[0]
                }}</span>
            </div>
            <div>
                <fwb-button color="purple" type="submit">Save</fwb-button>
            </div>
        </form>
    </div>
</template>
