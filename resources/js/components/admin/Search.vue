<script setup>
import { ref } from 'vue';

const search = ref('');

</script>

<template>
    <div class="w-full md:w-1/2">
        <form class="flex items-center mr-3">
            <label for="simple-search" class="sr-only">Search</label>
            <div class="relative w-full">
                <div
                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                >
                    <img
                        src="/resources/js/assets/icons/search.svg"
                        alt="show icon"
                        class="w-5 h-5"
                    />
                </div>
                <input
                    type="text"
                    id="simple-search"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Search"
                    v-model="search"
                    @input="$emit('searchResult', search)"
                />
            </div>
        </form>
    </div>
</template>
