<script setup>
import useSingleRecord from "../../../composables/admin/single.js";
import useUpdateRecord from "../../../composables/admin/update.js";

import { ref, reactive, onMounted, inject, defineAsyncComponent } from "vue";
import { FwbInput, FwbButton } from "flowbite-vue";
import { useToast } from "vue-toastification";

const ChangePassword = defineAsyncComponent(() =>
    import("./PasswordChange.vue")
);

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

const fetchRecord = async () => {
    record.value = await singleRecord();
    formFields.name = record.value.data.name;
    formFields.email = record.value.data.email;
};

const updateProfile = async () => {
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
};

onMounted(() => {
    fetchRecord();
});
</script>

<template>
    <div v-if="formFields.email !== 'demomanager@kamrankhan.dev'">
        <form @submit.prevent="updateProfile">
            <div class="mb-5">
                <fwb-input
                    v-model="formFields.name"
                    placeholder="Enter your name"
                    label="Name"
                />
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
                <span v-if="errors.email" class="text-red-600">{{
                    errors.email[0]
                }}</span>
            </div>
            <div>
                <fwb-button color="purple" type="submit">Save</fwb-button>
            </div>
        </form>
        <div class="mt-5 text-center">
            <ChangePassword :id="props.id"/>
        </div>
    </div>
    <div v-else class="text-center">
        <p class="text-gray-500">You cannot update the demo user profile.</p>
    </div>
</template>
