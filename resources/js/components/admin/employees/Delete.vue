<script setup>
import useDeleteRecord from "../../../composables/admin/delete";
import { FwbButton, FwbModal } from "flowbite-vue";
import { ref } from "vue";
import { useToast } from "vue-toastification";

const props = defineProps(["id"]);
const emit = defineEmits(["recordDeleted"]);

const { deleteRecord } = useDeleteRecord("/api/admin/users/" + props.id);
const isShowModal = ref(false);
const toast = useToast();

const closeModal = () => {
    isShowModal.value = false;
};

const showModal = () => {
    isShowModal.value = true;
};

const destroyRecord = async () => {
    try {
        const response = await deleteRecord();
        if (response.success) {
            emit("recordDeleted", "Employee deleted successfully");
            closeModal();
        } else if (response.error) {
            toast.error(response.error);
        }
    } catch (error) {
        toast.error("An error occurred while deleting the employee");
        console.error("Error deleting record:", error);
    }
};
</script>
<template>
    <div>
        <div class="text-right">
            <button
                type="button"
                class="px-4 py-2 text-sm font-medium bg-red-500 border border-red-200 rounded-e-lg hover:bg-red-600 focus:z-10 focus:ring-2 focus:ring-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-blue-500"
                @click="showModal"
            >
                <img
                    src="/public/assets/icons/delete.svg"
                    alt="delete icon"
                    class="w-5 h-4"
                />
            </button>
            <fwb-modal v-if="isShowModal" @close="closeModal">
                <template #header>
                    <div class="flex items-center text-lg uppercase">Delete Employee</div>
                </template>
                <template #body>
                    <p class="text-left">
                        Are you sure you want to delete this employee permanently?
                    </p>
                </template>
                <template #footer>
                    <div class="flex justify-between">
                        <fwb-button @click="closeModal" color="alternative">
                            Cancel
                        </fwb-button>
                        <fwb-button
                            type="button"
                            @click="destroyRecord"
                            color="red"
                        >
                            Delete
                        </fwb-button>
                    </div>
                </template>
            </fwb-modal>
        </div>
    </div>
</template>
