<script setup>
import useSingleRecord from "../../../composables/admin/single.js";
import { FwbButton, FwbModal } from "flowbite-vue";
import { ref, onMounted, inject, watch } from "vue";

const props = defineProps(["id"]);

const { singleRecord } = useSingleRecord(`/api/admin/groups/${props.id}`);

const storeGroup = inject("storeGroup");
const record = ref("");
const isShowModal = ref(false);

const fetchRecord = async () => {
    try {
        const data = await singleRecord();
        record.value = data.data

    } catch (error) {
        onsole.error("Failed to fetch the task record:", error);
    }
};

watch(
    () => storeGroup.value,
    (newVal) => {
        if (newVal) {
            fetchRecord();
            storeGroup.value = false;
        }
    }
);

onMounted(() => {
    fetchRecord();
});

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
                    src="/public/assets/icons/show.svg"
                    alt="show icon"
                    class="w-5 h-4"
                />
            </button>
            <fwb-modal v-if="isShowModal" @close="closeModal">
                <template #header>
                    <div class="flex items-center text-lg uppercase">
                        View Group
                    </div>
                </template>
                <template #body>
                    <div class="border-b-2 border-gray-200 mb-2 flex justify-between py-2">
                        <h3 class="font-semibold capitalize">Group Name</h3>
                        <p class="capitalize">{{ record.name }}</p>
                    </div>
                    <div class="mb-2 text-left">
                        <h3 class="font-semibold">Employees</h3>
                        <div class="max-h-52 overflow-auto">
                            <p class="py-2 px-1" v-for="user in record.users" :key="user.id">
                                {{ user.name }}
                            </p>
                        </div>
                    </div>
                    <div class="border-b-2 border-gray-200 py-2 text-left">
                        <h3 class="font-semibold capitalize">Group Description</h3>
                        <p class="capitalize">{{ record.description }}</p>
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
