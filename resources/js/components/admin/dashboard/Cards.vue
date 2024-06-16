<script setup>
import useStats from "../../../composables/admin/dashboard";
import { ref, onMounted } from "vue";
import { RouterLink } from "vue-router";

const { stats } = useStats("/api/admin/dashboard");

const records = ref({
    employees: 0,
    tasks: 0,
    categories: 0,
    groups: 0,
});
const loading = ref(true);

const fetchRecord = async () => {
    try {
        const data = await stats();
        records.value = { ...data };
    } catch (error) {
        console.error(error);
        alert("Failed to fetch records");
    } finally {
        loading.value = false;
    }
};

onMounted(fetchRecord);
</script>

<template>
    <div>
        <div v-if="loading" class="text-center">Loading...</div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <RouterLink
                :to="{ name: 'adminUsers' }"
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 w-full"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <h5
                            class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                        >
                            Total Employees
                        </h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">
                            {{ records.employees }}
                        </p>
                    </div>
                    <div>
                        <img
                            src="/resources/js/assets/icons/employees.svg"
                            alt="employee icon"
                            class="w-12 h-12"
                        />
                    </div>
                </div>
            </RouterLink>

            <RouterLink
                :to="{ name: 'adminCategories' }"
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 w-full"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <h5
                            class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                        >
                            Total Categories
                        </h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">
                            {{ records.categories }}
                        </p>
                    </div>
                    <div>
                        <img
                            src="/resources/js/assets/icons/category.svg"
                            alt="category icon"
                            class="w-12 h-12"
                        />
                    </div>
                </div>
            </RouterLink>

            <RouterLink
                :to="{ name: 'adminGroup' }"
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 w-full"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <h5
                            class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                        >
                            Total Groups
                        </h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">
                            {{ records.groups }}
                        </p>
                    </div>
                    <div>
                        <img
                            src="/resources/js/assets/icons/group.svg"
                            alt="group icon"
                            class="w-12 h-12"
                        />
                    </div>
                </div>
            </RouterLink>

            <RouterLink
                :to="{ name: 'adminTasks' }"
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 w-full"
            >
                <div class="flex justify-between items-center">
                    <div>
                        <h5
                            class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                        >
                            Total Tasks
                        </h5>
                        <p class="font-normal text-gray-700 dark:text-gray-400">
                            {{ records.tasks }}
                        </p>
                    </div>
                    <div>
                        <img
                            src="/resources/js/assets/icons/task.svg"
                            alt="task icon"
                            class="w-12 h-12"
                        />
                    </div>
                </div>
            </RouterLink>
        </div>
    </div>
</template>
