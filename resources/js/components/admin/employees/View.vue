<script setup>
import useSingleRecord from "../../../composables/admin/single.js";
import { FwbButton, FwbModal, FwbListGroup, FwbListGroupItem } from "flowbite-vue";
import { ref, onMounted, inject, watch } from "vue";

const props = defineProps(["id"]);
const storeUser = inject("storeUser");

const { singleRecord } = useSingleRecord("/api/admin/users/" + props.id);

const record = ref({});
const isShowModal = ref(false);

const fetchRecord = async () => {
    try {
        record.value = await singleRecord();
    } catch (error) {
        console.error("Failed to fetch the employee record:", error);
    }
};

watch(
    () => storeUser.value,
    (newVal) => {
        if (newVal) {
            fetchRecord();
            storeUser.value = false;
        }
    }
);

onMounted(fetchRecord);

const closeModal = () => {
    isShowModal.value = false;
};

const showModal = () => {
    isShowModal.value = true;
};
</script>
<template>
    <div>
        <div class="text-right">
            <button
                type="button"
                class="px-4 py-2 text-sm font-medium bg-blue-500 border border-blue-200 rounded-s-lg hover:bg-blue-600 focus:z-10 focus:ring-2 focus:ring-gray-700 focus:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white"
                @click="showModal"
            >
                <img
                    src="/resources/js/assets/icons/show.svg"
                    alt="show icon"
                    class="w-5 h-4"
                />
            </button>
            <fwb-modal v-if="isShowModal" @close="closeModal">
                <template #header>
                    <div class="flex items-center text-lg uppercase">View Employee</div>
                </template>
                <template #body>
                    <div class="border-b-2 border-gray-200 mb-2 flex justify-between py-2">
                        <h3 class="font-semibold">Employee Name</h3>
                        <p>{{ record?.data?.name }}</p>
                    </div>
                    <div class="border-b-2 border-gray-200 mb-2 flex justify-between py-2">
                        <h3 class="font-semibold">Employee Email</h3>
                        <p>{{ record?.data?.email }}</p>
                    </div>
                    <div class="border-b-2 border-gray-200 mb-2 py-2 text-left">
                        <h3 class="font-semibold mb-3">Tasks</h3>
                        <fwb-list-group class="w-full" v-if="record.data.tasks.length > 0">
                            <fwb-list-group-item v-for="task in record.data.tasks" :key="task.id">{{ task.title }}</fwb-list-group-item>
                        </fwb-list-group>
                        <p v-else>No task Assign</p>
                    </div>
                    <div class="border-b-2 border-gray-200 mb-2 flex justify-between py-2">
                        <h3 class="font-semibold">Created At</h3>
                        <p>{{ record?.data?.created_at }}</p>
                    </div>
                </template>
                <template #footer>
                    <div class="flex justify-between">
                        <fwb-button @click="closeModal" color="alternative">Close</fwb-button>
                    </div>
                </template>
            </fwb-modal>
        </div>
    </div>
</template>
