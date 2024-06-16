<script setup>
import useAllRecord from "../../../composables/admin/all";
import useTaskComplete from "../../../composables/employee/taskComplate";

import { ref, onMounted } from "vue";
import { useToast } from "vue-toastification";

const toast = useToast();
const tasks = ref([]);

const user = JSON.parse(localStorage.getItem("user"));

const { allRecord } = useAllRecord("/api/employees/tasks/"+user.id);

const fetchRecord = async () => {
    tasks.value = await allRecord();
}

const markTaskAsComplete = async (taskId) => {
    const { taskComplete } = useTaskComplete(`/api/employees/tasks/${taskId}/complete`);
    
    const response = await taskComplete();

    if (response.message === 'Task status updated to review') {
        const task = tasks.value.find(task => task.id === taskId);
        if (task) {
            task.status = 'review';
        }
        toast.success('Task status updated to review');
    }
};

onMounted(() => {
    fetchRecord();
});
</script>

<template>
    <div>
        <nav class="flex px-4 lg:px-12 mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center" aria-current="page">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white uppercase">
                        <img src="/resources/js/assets/icons/task.svg" alt="task icon" class="w-4 h-4" />
                        Tasks
                    </a>
                </li>
            </ol>
        </nav>

        <div v-if="tasks.length">
            <div class="px-4 lg:px-12">
                <div class="grid grid-cols-2 gap-5">
                    <div
                        class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
                        v-for="task in tasks" :key="task.id"
                    >
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white capitalize">
                                {{ task.title }}
                            </h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                            {{ task.description }}
                        </p>
                        <div class="flex gap-3">
                            <p class="mb-3 font-normal bg-purple-600 text-white inline-block px-3 py-2 rounded-xl">
                                {{ task.category.name }}
                            </p>
                            <p class="mb-3 font-normal bg-slate-600 text-white inline-block px-3 py-2 rounded-xl" v-if="!task.group_id">
                               Individual
                            </p>
                            <p class="mb-3 font-normal bg-cyan-500 text-white inline-block px-3 py-2 rounded-xl" v-else>
                               Group
                            </p>
                        </div>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <tr>
                                <th>Priority</th>
                                <td :class="{'text-red-700': task.priority == 'high', 'text-orange-400': task.priority == 'medium', 'text-green-500': task.priority == 'low'}" class="mb-3 font-normal text-gray-700 dark:text-gray-400 text-right">
                                    {{ task.priority }}
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td :class="{'text-gray-500': task.status == 'pending', 'text-blue-500': task.status == 'review', 'text-green-500': task.status == 'complete'}" class="mb-3 font-normal text-gray-700 dark:text-gray-400 text-right">
                                    {{ task.status }}
                                </td>
                            </tr>
                            <tr>
                                <th>Due Date</th>
                                <td class="mb-3 font-normal text-gray-700 dark:text-gray-400 text-right">
                                    {{ task.due_date }}
                                </td>
                            </tr>
                        </table>
                        <form @submit.prevent="markTaskAsComplete(task.id)">
                            <button
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mt-5"
                                type="submit"
                                v-if="task.status !== 'complete'"
                            >
                                Completed
                                <svg
                                    class="rtl:rotate-180 w-3.5 h-3.5 ms-2"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 14 10"
                                >
                                    <path
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M1 5h12m0 0L9 1m4 4L9 9"
                                    />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <p class="text-center my-5 text-xl">No task assigned yet</p>
        </div>
    </div>
</template>

