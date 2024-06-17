<script setup>
import useCreateRecord from "../../../composables/admin/create.js";
import { ref, reactive } from "vue";
import { FwbButton, FwbModal, FwbInput } from "flowbite-vue";
import { useToast } from "vue-toastification";

const emit = defineEmits(["recordCreated"]);

const { createRecord } = useCreateRecord("/api/admin/users");

const formFields = reactive({
    name: "",
    email: "",
    password: "",
});

const toast = useToast();
const errors = ref({});

const storeRecord = async () => {
    try {
        const response = await createRecord(formFields);
        if (response.success) {
            emit("recordCreated", "Employee Created Successfully");
            closeModal();
        } else {
            errors.value = response.errors;
        }
    } catch (error) {
        if (error.errors) {
            errors.value = error.errors;
        } else {
            toast.error("Error creating employee");
            console.error("Error creating employee:", error);
        }
    }
};

const isShowModal = ref(false);

function closeModal() {
    errors.value = {};
    formFields.name = "";
    formFields.email = "";
    formFields.password = "";
    isShowModal.value = false;
}

function showModal() {
    isShowModal.value = true;
}
</script>
<template>
    <div>
        <div class="text-right">
            <fwb-button color="purple" @click="showModal" size="xs">
                <img
                    src="/public/assets/icons/pluse.svg"
                    alt="plus icon"
                    class="w-5 h-5"
                />
            </fwb-button>
            <fwb-modal v-if="isShowModal" @close="closeModal">
                <template #header>
                    <div class="flex items-center text-lg uppercase">Create New Employee</div>
                </template>
                <template #body>
                    <form id="create" @submit.prevent="storeRecord" class="text-left">
                        <div class="mb-5">
                            <fwb-input
                                placeholder="Enter Name"
                                label="Name *"
                                v-model="formFields.name"
                                required
                            />
                            <span v-if="errors.name" class="text-red-600">
                                {{ errors.name[0] }}
                            </span>
                        </div>
                        <div class="mb-5">
                            <fwb-input
                                placeholder="Enter Email"
                                label="Email *"
                                v-model="formFields.email"
                                type="email"
                                required
                            />
                            <span v-if="errors.email" class="text-red-600">
                                {{ errors.email[0] }}
                            </span>
                        </div>
                        <div class="mb-5">
                            <fwb-input
                                placeholder="Enter Password"
                                label="Password *"
                                v-model="formFields.password"
                                type="password"
                                required
                            />
                            <span v-if="errors.password" class="text-red-600">
                                {{ errors.password[0] }}
                            </span>
                        </div>
                    </form>
                </template>
                <template #footer>
                    <div class="flex justify-between">
                        <fwb-button @click="closeModal" color="alternative">
                            Cancel
                        </fwb-button>
                        <fwb-button type="submit" form="create" color="purple">
                            Create
                        </fwb-button>
                    </div>
                </template>
            </fwb-modal>
        </div>
    </div>
</template>
