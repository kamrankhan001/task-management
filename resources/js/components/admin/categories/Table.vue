<script setup>
import useAllRecord from "../../../composables/admin/all.js";

import { ref, onMounted, defineAsyncComponent } from "vue";
import { useToast } from "vue-toastification";

import Search from "../Search.vue";

const ViewCategory = defineAsyncComponent(() => import("./View.vue"));
const CreateCategory = defineAsyncComponent(() => import("./Create.vue"));
const EditCategory = defineAsyncComponent(() => import("./Edit.vue"));
const DeleteCategory = defineAsyncComponent(() => import("./Delete.vue"));

const { allRecord } = useAllRecord("/api/admin/categories");

const emit = defineEmits(["recordCreated", "recordUpdated", "recordDeleted"]);

const toast = useToast();
const records = ref({});
const currentPage = ref(1);
const links = ref([]);
const query = ref("");

const fetchRecords = async (page, qry) => {
    currentPage.value = page;
    if (qry != "") {
        qry = "&name=" + qry;
    }
    try {
        const data = await allRecord(page, qry);
        records.value = data;
        links.value = records.value.meta.links.filter((link, index) => index !== 0 && index !== records.value.meta.links.length - 1);
    } catch (error) {
        toast.error("Failed to fetch records");
    }
};

const handleRecords = (msg) => {
    fetchRecords(currentPage.value, query.value);
    toast.success(msg);
};

const searchResult = (qry) => {
    currentPage.value = 1;
    query.value = qry;
    fetchRecords(currentPage.value, query.value);
};

onMounted(() => {
    fetchRecords(currentPage.value, query.value);
});
</script>
<template>
    <div>
        <nav class="flex px-4 lg:px-12 mb-5" aria-label="Breadcrumb">
            <ol
                class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse"
            >
                <li class="inline-flex items-center" aria-current="page">
                    <a
                        href="#"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white uppercase"
                    >
                        <img
                            src="/resources/js/assets/icons/category.svg"
                            alt="task icon"
                            class="w-4 h-4"
                        />

                        categories
                    </a>
                </li>
            </ol>
        </nav>

        <div class="px-4 lg:px-12">
            <div
                class="bg-white dark:bg-gray-800 relative sm:rounded-lg overflow-hidden"
            >
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                >
                    <Search @search-result="searchResult" />
                    <CreateCategory @record-created="handleRecords" />
                </div>

                <div class="overflow-x-auto">
                    <table
                        class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                        v-if="records?.data?.length > 0"
                    >
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                        >
                            <tr>
                                <th scope="col" class="px-4 py-3">
                                    Category Name
                                </th>
                                <th scope="col" class="px-4 py-3">
                                    Created Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                class="border-b dark:border-gray-700"
                                v-for="record in records.data"
                                :key="record.id"
                            >
                                <th
                                    scope="row"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white capitalize"
                                >
                                    {{ record.name }}
                                </th>
                                <td
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white capitalize"
                                >
                                    {{ record.created_at }}
                                </td>
                                <td
                                    class="px-4 py-3 flex items-center justify-end"
                                >
                                    <div
                                        class="inline-flex rounded-md shadow-sm"
                                        role="group"
                                    >
                                        <ViewCategory :id="record.id" />
                                        <EditCategory
                                            :id="record.id"
                                            @record-updated="handleRecords"
                                        />
                                        <DeleteCategory
                                            :id="record.id"
                                            @record-deleted="handleRecords"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else class="my-10 text-center text-xl">
                        No Category Found
                    </div>
                </div>

                <nav
                    class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                    aria-label="Table navigation"
                    v-if="links.length > 1"
                >
                    <span
                        class="text-sm font-normal text-gray-500 dark:text-gray-400"
                    >
                        Showing
                        <span
                            class="font-semibold text-gray-900 dark:text-white"
                            >{{ records.meta.from }}-{{ records.meta.to }}</span
                        >
                        of
                        <span
                            class="font-semibold text-gray-900 dark:text-white"
                            >{{ records.meta.total }}</span
                        >
                    </span>

                    <ul class="inline-flex items-stretch -space-x-px">
                        <li>
                            <button
                                class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                :class="
                                    records.links.prev
                                        ? ''
                                        : 'cursor-not-allowed'
                                "
                                :disabled="records.links.prev ? false : true"
                                @click="fetchRecords(currentPage - 1, query)"
                            >
                                <span class="sr-only">Previous</span>
                                <img src="/resources/js/assets/icons/pagePrev.svg" alt="prev icon" class="w-5 h-5">
                            </button>
                        </li>

                        <li v-for="link in links" :key="link.label">
                            <button
                                @click="fetchRecords(link.label, query)"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:hover:text-white"
                                :class="
                                    link.active == true
                                        ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-white'
                                        : 'text-gray-500 bg-white dark:text-gray-400 dark:hover:bg-gray-700'
                                "
                            >
                                {{ link.label }}
                            </button>
                        </li>

                        <li>
                            <button
                                class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                                :class="
                                    records.links.next
                                        ? ''
                                        : 'cursor-not-allowed'
                                "
                                :disabled="records.links.next ? false : true"
                                @click="fetchRecords(currentPage + 1, query)"
                            >
                                <span class="sr-only">Next</span>
                                <img src="/resources/js/assets/icons/pageNext.svg" alt="next icon" class="w-5 h-5">
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>
