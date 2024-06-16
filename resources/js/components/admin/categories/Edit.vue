<script setup>
import useSingleRecord from "../../../composables/admin/single.js";
import useUpdateRecord from "../../../composables/admin/update.js";

import { ref, reactive, onMounted, inject } from "vue";
import { FwbButton, FwbModal, FwbInput } from "flowbite-vue";
import { useToast } from "vue-toastification";

const props = defineProps(["id"]);
const emit = defineEmits(["recordUpdated"]);
const storeCategory = inject("storeCategory");

const { singleRecord } = useSingleRecord("/api/admin/categories/" + props.id);
const { updateRecord } = useUpdateRecord("/api/admin/categories/" + props.id);

const formFields = reactive({
    name: "",
});


const toast = useToast();
const record = ref("");
const errors = ref({});
const isShowModal = ref(false);

const fetchRecord = async () => {
    const response = await singleRecord();
    if (response) {
        record.value = response;
        formFields.name = record.value.data.name;
    } else {
        toast.error("Error fetching category");
    }
};

const editRecord = async () => {
    try {
        const response = await updateRecord(formFields);
        if (response.success) {
            storeCategory.value = true;
            emit("recordUpdated", "Category Updated Successfully");
            closeModal();
        } else {
            errors.value = response.errors;
        }
    } catch (error) {
        if (error.errors) {
            errors.value = error.errors;
        } else {
            toast.error("Error updating category");
            console.error("Error updating category:", error);
        }
    }
};

onMounted(() => {
    fetchRecord();
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
                    alt="edit icon"
                    class="w-5 h-4"
                />
            </button>
            <fwb-modal v-if="isShowModal" @close="closeModal">
                <template #header>
                    <div class="flex items-center text-lg uppercase">Edit Category</div>
                </template>
                <template #body>
                    <form id="edit" @submit.prevent="editRecord" class="text-left">
                        <div class="mb-5">
                            <fwb-input
                                placeholder="Enter Category"
                                label="Category *"
                                v-model="formFields.name"
                                required
                            />
                            <span v-if="errors.name" class="text-red-600">
                                {{ errors.name[0] }}
                            </span>
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
