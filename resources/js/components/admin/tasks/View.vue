<script setup>
import useSingleRecord from "../../../composables/admin/single";
import { ref, onMounted, inject, watch } from "vue";
import { FwbButton, FwbModal } from "flowbite-vue";

const props = defineProps(["id"]);

const { singleRecord } = useSingleRecord(`/api/admin/tasks/${props.id}`);
const storeTask = inject('storeTask');
const record = ref(null);
const isShowModal = ref(false);

const fetchRecord = async () => {
    try {
        record.value = await singleRecord();
    } catch (error) {
        console.error("Failed to fetch the task record:", error);
    }
};

watch(() => storeTask.value, (newVal) => {
    if (newVal) {
        fetchRecord();
        storeTask.value = false;
    }
});

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
                    <div class="flex items-center text-lg uppercase">
                        View Tasks
                    </div>
                </template>
                <template #body>
                    <div class="text-left">
                        <div class="border-b-2 border-gray-200 mb-2 flex justify-between py-2">
                            <h3 class="font-semibold">Task Title</h3>
                            <p class="capitalize">{{ record?.data?.title }}</p>
                        </div>
                        <div class="border-b-2 border-gray-200 mb-2 flex justify-between py-2">
                            <h3 class="font-semibold">Task Category</h3>
                            <p class="capitalize">{{ record?.data?.category?.name }}</p>
                        </div>
                        <div class="border-b-2 border-gray-200 mb-2 flex justify-between py-2">
                            <h3 class="font-semibold">Task Assign To</h3>
                            <p v-if="record?.data?.user" class="capitalize">{{ record?.data?.user?.name }}</p>
                            <p v-if="record?.data?.group" class="capitalize">{{ record?.data?.group?.name }}</p>
                        </div>
                        <div class="border-b-2 border-gray-200 mb-2 flex justify-between py-2">
                            <h3 class="font-semibold">Task Due Date</h3>
                            <p>{{ record?.data?.due_date }}</p>
                        </div>
                        <div class="border-b-2 border-gray-200 mb-2 flex justify-between py-2">
                            <h3 class="font-semibold">Task Priority</h3>
                            <p v-if="record?.data?.priority === 'high'" class="bg-red-700 px-3 py-1 rounded text-white">
                                {{ record?.data?.priority }}
                            </p>
                            <p v-if="record?.data?.priority === 'medium'" class="bg-orange-400 px-3 py-1 rounded text-white">
                                {{ record?.data?.priority }}
                            </p>
                            <p v-if="record?.data?.priority === 'low'" class="bg-green-500 px-3 py-1 rounded text-white">
                                {{ record?.data?.priority }}
                            </p>
                        </div>
                        <div class="border-b-2 border-gray-200 mb-2 flex justify-between py-2">
                            <h3 class="font-semibold">Task Status</h3>
                            <p v-if="record?.data?.status === 'pending'" class="bg-gray-200 px-3 py-1 rounded text-black">
                                {{ record?.data?.status }}
                            </p>
                            <p v-if="record?.data?.status === 'review'" class="bg-blue-400 px-3 py-1 rounded text-white">
                                {{ record?.data?.status }}
                            </p>
                            <p v-if="record?.data?.status === 'complete'" class="bg-green-500 px-3 py-1 rounded text-white">
                                {{ record?.data?.status }}
                            </p>
                        </div>
                        <div class="border-b-2 border-gray-200 flex justify-between py-2">
                            <h3 class="font-semibold">Task Created Date</h3>
                            <p>{{ record?.data?.created_at }}</p>
                        </div>
                        <div class="py-2">
                            <h3 class="font-semibold mb-2">Task Description</h3>
                            <p>{{ record?.data?.description }}</p>
                        </div>
                    </div>
                </template>
                <template #footer>
                    <div class="flex justify-between">
                        <fwb-button @click="closeModal" color="alternative">
                            Close
                        </fwb-button>
                    </div>
                </template>
            </fwb-modal>
        </div>
    </div>
</template>
