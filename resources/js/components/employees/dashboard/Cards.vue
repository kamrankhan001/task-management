<script setup>
import useAllRecord from "../../../composables/admin/all";

import { ref, onMounted } from "vue";

const dashboardData = ref({});
const user = JSON.parse(localStorage.getItem("user"));

const { allRecord } = useAllRecord("/api/employees/dashboard/"+user.id);

const fetchDashboardData = async () => {
    dashboardData.value = await allRecord();
};

onMounted(() => {
    fetchDashboardData();
});
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 w-full">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Tasks Completed
                    </h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">
                        {{ dashboardData.completed_tasks }}
                    </p>
                </div>
                <div>
                    <img src="/public/assets/icons/complete.svg" style="width: 45px; height: 45px;">
                </div>
            </div>
        </div>

        <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 w-full">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Task Pending
                    </h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">
                        {{ dashboardData.pending_tasks }}
                    </p>
                </div>
                <div>
                    <img src="/public/assets/icons/waiting.svg" style="width: 45px; height: 45px;">
                </div>
            </div>
        </div>

        <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 w-full">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Individual Tasks
                    </h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">
                        {{ dashboardData.individual_tasks }}
                    </p>
                </div>
                <div>
                    <img src="/public/assets/icons/individual.svg" style="width: 45px; height: 45px;">
                </div>
            </div>
        </div>

        <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 w-full">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Group Tasks
                    </h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">
                        {{ dashboardData.group_tasks }}
                    </p>
                </div>
                <div>
                    <img src="/public/assets/icons/group.svg" style="width: 45px; height: 45px;">
                </div>
            </div>
        </div>
    </div>
</template>
