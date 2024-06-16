<script setup>
import useSingleRecord from "../../../composables/admin/single.js";
import useUpdateRecord from "../../../composables/admin/update.js";
import useAllRecord from "../../../composables/admin/all.js";

import { FwbButton, FwbModal, FwbInput, FwbTextarea } from "flowbite-vue";
import { ref, reactive, onMounted, inject } from "vue";
import { useToast } from "vue-toastification";

const props = defineProps(["id"]);
const emit = defineEmits(["recordUpdated"]);
const storeGroup = inject("storeGroup");

const { singleRecord } = useSingleRecord("/api/admin/groups/" + props.id);
const { updateRecord } = useUpdateRecord("/api/admin/groups/" + props.id);
const { allRecord } = useAllRecord("/api/admin/users");

const formFields = reactive({
    name: "",
    description: "",
    users: [],
});

const toast = useToast();
const record = ref("");
const errors = ref("");
const isShowModal = ref(false);
const users = ref([]);

const fetchRecord = async () => {
    record.value = await singleRecord();
    formFields.name = record.value.data.name;
    formFields.description = record.value.data.description;
    formFields.users = record.value.data?.users?.map((user) => user.id);
};

const fetchEmployees = async () => {
    const response = await allRecord();
    users.value = response.data.map((user) => ({
        id: user.id,
        name: user.name,
    }));
};

const editRecord = async () => {
    try {
        const response = await updateRecord(formFields);
        if (response.success) {
            storeGroup.value = true;
            emit("recordUpdated", "Group Updated Successfully");
            closeModal();
        } else if (response.error) {
            errors.value = response.error;
        }
    } catch (error) {
        toast.error("Error updating group");
        console.error("Error fetching groups:", error);
    }
};

onMounted(() => {
    fetchRecord();
    fetchEmployees();
});

function closeModal() {
    isShowModal.value = false;
}

function showModal() {
    isShowModal.value = true;
}
</script>

<template>
    <div>
        <div class="text-right">
            <button
                type="button"
                class="px-4 py-2 text-sm font-medium bg-yellow-300 border-t border-b border-yellow-200 hover:bg-yellow-400 hover:text-yellow-700 focus:z-10 focus:ring-2 focus:ring-yellow-700 focus:text-yellow-700 dark:bg-yellow-800 dark:border-yellow-700 dark:hover:bg-gray-700 dark:focus:ring-yellow-500"
                @click="showModal"
            >
                <img
                    src="/resources/js/assets/icons/edit.svg"
                    alt="show icon"
                    class="w-5 h-4"
                />
            </button>
            <fwb-modal v-if="isShowModal" @close="closeModal">
                <template #header>
                    <div class="flex items-center text-lg uppercase">
                        Edit Group
                    </div>
                </template>
                <template #body>
                    <form
                        id="edit"
                        @submit.prevent="editRecord"
                        class="text-left"
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
                        <fwb-button type="submit" form="edit" color="yellow">
                            Update
                        </fwb-button>
                    </div>
                </template>
            </fwb-modal>
        </div>
    </div>
</template>
