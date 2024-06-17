<script setup>
import useCreateRecord from "../../../composables/admin/create.js";
import useAllRecord from "../../../composables/admin/all.js";
import {
    FwbButton,
    FwbModal,
    FwbInput,
    FwbTextarea,
} from "flowbite-vue";
import { ref, reactive, onMounted } from "vue";
import { useToast } from "vue-toastification";

const props = defineProps(["id"]);
const emit = defineEmits(["recordCreated"]);

const { createRecord } = useCreateRecord("/api/admin/groups");
const { allRecord } = useAllRecord("/api/admin/users");

const formFields = reactive({
    name: "",
    description: "",
    users: [],
});

const toast = useToast();
const errors = ref("");
const users = ref({});

const fetchUsers = async () => {
    const response = await allRecord();
    users.value = response.data.map((user) => ({
        id: user.id,
        name: user.name,
    }));
};

const storeRecord = async () => {
    try {
        const response = await createRecord(formFields);
        if (response.success) {
            emit("recordCreated", "Grouop Created Successfully");
            closeModal();
            formFields.name = "";
            formFields.description = "";
            formFields.users = [];
        } else {
            errors.value = response.error;
        }
    } catch (error) {
        if (error.errors) {
            errors.value = error.errors;
        } else {
            toast.error("Error creating group");
            console.error("Error creating group:", error);
        }
    }
};

onMounted(() => {
    fetchUsers();
});

const isShowModal = ref(false);

const closeModal = () => {
    errors.value = "";
    formFields.name = "";
    formFields.description = "";
    formFields.users = [];
    isShowModal.value = false;
}

const showModal = () => {
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
                    <div class="flex items-center text-lg uppercase">
                        Create new Group
                    </div>
                </template>
                <template #body>
                    <form
                        class="text-left"
                        id="create"
                        @submit.prevent="storeRecord"
                    >
                        <div class="mb-5">
                            <fwb-input
                                placeholder="Enter Name"
                                label="Group Name *"
                                v-model="formFields.name"
                                required
                            />
                            <span v-if="errors.name" class="text-red-600">{{
                                errors.name[0]
                            }}</span>
                        </div>
                        <div class="mb-5">
                            <fwb-textarea
                                placeholder="Enter Description"
                                label="Description"
                                v-model="formFields.description"
                            />
                            <span
                                v-if="errors.description"
                                class="text-red-600"
                                >{{ errors.description[0] }}</span
                            >
                        </div>
                        <div class="mb-5">
                            <label
                                for="users_multiple"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Add Users</label
                            >
                            <select
                                multiple
                                id="users_multiple"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                v-model="formFields.users"
                            >
                                <option selected>Choose users</option>
                                <option
                                    :value="user.id"
                                    v-for="user in users"
                                    :key="user.id"
                                >
                                    {{ user.name }}
                                </option>
                            </select>
                            <span v-if="errors.users" class="text-red-600">{{
                                errors.users[0]
                            }}</span>
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
