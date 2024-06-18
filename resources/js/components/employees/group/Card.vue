<script setup>
import useAllRecord from "../../../composables/admin/all";

import { ref, onMounted } from "vue";
import { FwbListGroup, FwbListGroupItem } from 'flowbite-vue'

const groups = ref([]);

const user = JSON.parse(localStorage.getItem("user"));

const { allRecord } = useAllRecord("/api/employees/groups/"+user.id);

const fetchRecord = async () => {
    groups.value = await allRecord();
}

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
                        <img src="/public/assets/icons/group.svg" class="w-4 h-4">
                        Groups
                    </a>
                </li>
            </ol>
        </nav>

        <div>
            <div class="px-4 lg:px-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div v-for="group in groups" :key="group.id" class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                        <p>
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white capitalize">
                                {{ group.name }}
                            </h5>
                        </p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 capitalize">
                            {{ group.description }}
                        </p>
                        <div class="flex flex-col gap-3 mt-3">
                            <p class="font-normal bg-blue-400 text-white inline-block px-3 py-2 rounded">
                                Group Tasks
                            </p>
                            <fwb-list-group class="w-full">
                                <fwb-list-group-item v-for="task in group.tasks" :key="task.id">{{ task.title }}</fwb-list-group-item>
                            </fwb-list-group>
                        </div>
                        <div class="flex flex-col gap-3 mt-3">
                            <p class="font-normal bg-orange-400 text-white inline-block px-3 py-2 rounded">
                                Group Members
                            </p>
                            <fwb-list-group class="w-full">
                                <fwb-list-group-item v-for="member in group.users" :key="member.id">{{ member.name }}</fwb-list-group-item>
                            </fwb-list-group>
                        </div>
                        <p class="flex justify-between mt-4">
                            <span class="block">
                                Group Created
                            </span>
                            <span
                                class="block">
                                {{ group.created_at }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="groups.length === 0">
            <p class="text-center my-5 text-xl">No group found</p>
        </div>
   </div>
</template>
