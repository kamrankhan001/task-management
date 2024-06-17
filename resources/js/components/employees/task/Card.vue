<script setup>
    import useAllRecord from "../../../composables/admin/all";
    import useTaskComplete from "../../../composables/employee/taskComplate";

    import {
        ref,
        onMounted,
        computed
    } from "vue";
    import {
        FwbTab,
        FwbTabs
    } from "flowbite-vue";
    import {
        useToast
    } from "vue-toastification";

    const toast = useToast();
    const tasksPending = ref([]);
    const tasksReview = ref([]);
    const tasksComplete = ref([]);

    const user = JSON.parse(localStorage.getItem("user"));

    const {
        allRecord
    } = useAllRecord("/api/employees/tasks/" + user.id);
    const activeTab = ref("tasks");

    const fetchRecord = async () => {
        const response = await allRecord();
        tasksPending.value = response.pending;
        tasksReview.value = response.review;
        tasksComplete.value = response.completed;
    };

    const markTaskAsComplete = async (taskId) => {
        const {
            taskComplete
        } = useTaskComplete(
            `/api/employees/tasks/${taskId}/complete`
        );

        const response = await taskComplete();

        if (response.message === "Task status updated to review") {
            const task = tasksPending.value.find((task) => task.id === taskId);
            if (task) {
                task.status = "review";
                fetchRecord();
            }
            toast.success("Task status updated to review");
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
                    <a href="#"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white uppercase">
                        <img src="/public/assets/icons/task.svg" alt="task icon" class="w-4 h-4" />
                        Tasks
                    </a>
                </li>
            </ol>
        </nav>
        <div class="px-4 lg:px-12">
            <fwb-tabs v-model="activeTab" variant="underline" class="p-5">
                <fwb-tab name="tasks" title="Tasks">
                    <div v-if="tasksPending.length">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100"
                                v-for="task in tasksPending" :key="task.id">
                                <p>
                                <h5
                                    class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white capitalize">
                                    {{ task.title }}
                                </h5>
                                </p>
                                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                    {{ task.description }}
                                </p>
                                <div class="flex gap-3 mb-4">
                                    <p
                                        class="mb-3 font-normal bg-slate-700 text-white inline-block px-3 py-2 rounded-xl">
                                        {{ task.category.name }}
                                    </p>
                                </div>
                                <div>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">
                                            Task Type
                                        </span>
                                        <span v-if="!task.group_id"
                                            class="block px-2 py-1 bg-orange-400 rounded text-white">
                                            Individual
                                        </span>
                                        <span v-else class="block px-2 py-1 bg-blue-400 rounded text-white">
                                            Group
                                        </span>
                                    </p>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">Priority</span>
                                        <span class="block px-2 py-1 rounded text-white capitalize"
                                            :class="{
                                                'bg-red-700': task.priority == 'high',
                                                'bg-orange-400': task.priority == 'medium',
                                                'bg-green-500': task.priority == 'low',
                                            }">{{ task.priority }}</span>

                                    </p>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">Status</span>
                                        <span class="block px-2 py-1 rounded text-white capitalize"
                                            :class="{
                                                'bg-gray-500': task.status == 'pending',
                                                'bg-blue-500': task.status == 'review',
                                                'bg-green-500': task.status == 'complete',
                                            }">{{ task.status }}</span>

                                    </p>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">Due Date</span>
                                        <span class="block px-2 py-1 rounded capitalize">
                                            {{ task.due_date }}
                                        </span>

                                    </p>
                                    <p class="flex justify-between border-slate-500">
                                        <span class="block">Assign Date</span>
                                        <span class="block px-2 py-1 rounded capitalize">
                                            {{ task.created_at }}
                                        </span>

                                    </p>
                                </div>
                                <form
                                    @submit.prevent="
                                        markTaskAsComplete(task.id)
                                    "
                                    class="text-right">
                                    <button
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mt-5"
                                        type="submit">
                                        Completed
                                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <p class="text-center my-5 text-xl">
                            No task assigned yet
                        </p>
                    </div>
                </fwb-tab>
                <fwb-tab name="review" title="In Review">
                    <div v-if="tasksReview.length">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100"
                                v-for="task in tasksReview" :key="task.id">
                                <p>
                                <h5
                                    class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white capitalize">
                                    {{ task.title }}
                                </h5>
                                </p>
                                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                    {{ task.description }}
                                </p>
                                <div class="flex gap-3 mb-4">
                                    <p
                                        class="mb-3 font-normal bg-slate-700 text-white inline-block px-3 py-2 rounded-xl">
                                        {{ task.category.name }}
                                    </p>
                                </div>
                                <div>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">
                                            Task Type
                                        </span>
                                        <span v-if="!task.group_id"
                                            class="block px-2 py-1 bg-orange-400 rounded text-white">
                                            Individual
                                        </span>
                                        <span v-else class="block px-2 py-1 bg-blue-400 rounded text-white">
                                            Group
                                        </span>
                                    </p>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">Priority</span>
                                        <span class="block px-2 py-1 rounded text-white capitalize"
                                            :class="{
                                                'bg-red-700': task.priority == 'high',
                                                'bg-orange-400': task.priority == 'medium',
                                                'bg-green-500': task.priority == 'low',
                                            }">{{ task.priority }}</span>

                                    </p>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">Status</span>
                                        <span class="block px-2 py-1 rounded text-white capitalize"
                                            :class="{
                                                'bg-gray-500': task.status == 'pending',
                                                'bg-blue-500': task.status == 'review',
                                                'bg-green-500': task.status == 'complete',
                                            }">{{ task.status }}</span>

                                    </p>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">Due Date</span>
                                        <span class="block px-2 py-1 rounded capitalize">
                                            {{ task.due_date }}
                                        </span>

                                    </p>
                                    <p class="flex justify-between border-slate-500">
                                        <span class="block">Assign Date</span>
                                        <span class="block px-2 py-1 rounded capitalize">
                                            {{ task.created_at }}
                                        </span>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <p class="text-center my-5 text-xl">
                            No task in review
                        </p>
                    </div>
                </fwb-tab>
                <fwb-tab name="complete" title="Completed">
                    <div v-if="tasksComplete.length">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100"
                                v-for="task in tasksComplete" :key="task.id">
                                <p>
                                <h5
                                    class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white capitalize">
                                    {{ task.title }}
                                </h5>
                                </p>
                                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                    {{ task.description }}
                                </p>
                                <div class="flex gap-3 mb-4">
                                    <p
                                        class="mb-3 font-normal bg-slate-700 text-white inline-block px-3 py-2 rounded-xl">
                                        {{ task.category.name }}
                                    </p>
                                </div>
                                <div>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">
                                            Task Type
                                        </span>
                                        <span v-if="!task.group_id"
                                            class="block px-2 py-1 bg-orange-400 rounded text-white">
                                            Individual
                                        </span>
                                        <span v-else class="block px-2 py-1 bg-blue-400 rounded text-white">
                                            Group
                                        </span>
                                    </p>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">Priority</span>
                                        <span class="block px-2 py-1 rounded text-white capitalize"
                                            :class="{
                                                'bg-red-700': task.priority == 'high',
                                                'bg-orange-400': task.priority == 'medium',
                                                'bg-green-500': task.priority == 'low',
                                            }">{{ task.priority }}</span>

                                    </p>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">Status</span>
                                        <span class="block px-2 py-1 rounded text-white capitalize"
                                            :class="{
                                                'bg-gray-500': task.status == 'pending',
                                                'bg-blue-500': task.status == 'review',
                                                'bg-green-500': task.status == 'complete',
                                            }">{{ task.status }}</span>

                                    </p>
                                    <p class="flex justify-between mb-2">
                                        <span class="block">Due Date</span>
                                        <span class="block px-2 py-1 rounded capitalize">
                                            {{ task.due_date }}
                                        </span>

                                    </p>
                                    <p class="flex justify-between border-slate-500">
                                        <span class="block">Assign Date</span>
                                        <span class="block px-2 py-1 rounded capitalize">
                                            {{ task.created_at }}
                                        </span>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <p class="text-center my-5 text-xl">
                            No task completed
                        </p>
                    </div>
                </fwb-tab>
            </fwb-tabs>
        </div>
    </div>
</template>
