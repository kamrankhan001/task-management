<script setup>
import { initCollapses, initDropdowns } from "flowbite";
import { RouterLink } from "vue-router";
import { onBeforeMount, watch, onMounted, inject } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const user = inject('user');

onBeforeMount(() => {
    user.value = JSON.parse(localStorage.getItem("user")) || null;
});

onMounted(() => {
    initCollapses();
    initDropdowns();
});

watch(route, () => {
    user.value = JSON.parse(localStorage.getItem("user")) || null;
});
</script>

<template>
    <nav
        class="fixed top-0 z-40 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700"
    >
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button
                        data-drawer-target="logo-sidebar"
                        data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar"
                        type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <img
                            src="../assets/icons/humburger.svg"
                            alt="humburder icon"
                        />
                    </button>
                    <a href="https://flowbite.com" class="flex ms-2 md:me-24">
                        <p
                            class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-red-700 text-center"
                            >Vision <span class="block text-sm font-normal">Marketing & Production</span></p>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button
                                type="button"
                                class="flex text-sm bg-gray-200 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                aria-expanded="false"
                                data-dropdown-toggle="dropdown-user"
                            >
                                <span class="sr-only">Open user menu</span>
                                <img
                                    class="w-8 h-8 rounded-full"
                                    src="/public/images/vision.png"
                                    alt="Company logo"
                                />
                            </button>
                        </div>
                        <div
                            class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                            id="dropdown-user"
                        >
                            <div class="px-4 py-3" role="none">
                                <p
                                    class="text-sm text-gray-900 dark:text-white"
                                    role="none"
                                >
                                    {{ user.name }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-900 truncate dark:text-gray-300"
                                    role="none"
                                >
                                    {{ user.email }}
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <RouterLink
                                        :to="{ name: 'adminProfile', params: {id: user.id}}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem"
                                    >
                                        <div class="flex gap-2">
                                            <img
                                                src="../assets/icons/profile.svg"
                                                alt="humburder icon"
                                                class="w-5 h-5"
                                            />
                                            Profile
                                        </div>
                                    </RouterLink>
                                </li>
                                <li>
                                    <RouterLink
                                        :to="{ name: 'logout' }"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem"
                                    >
                                        <div class="flex gap-2">
                                            <img
                                                src="../assets/icons/signout.svg"
                                                alt="humburder icon"
                                                class="w-5 h-5"
                                            />
                                            Sign out
                                        </div>
                                    </RouterLink>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside
        id="logo-sidebar"
        class="fixed top-0 left-0 z-30 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
        aria-label="Sidebar"
    >
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <RouterLink
                        :to="{ name: 'adminDashboard' }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="../assets/icons/dashboard.svg"
                            alt="dashboard icon"
                            class="w-5 h-5"
                        />
                        <span class="ms-3">Dashboard</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink
                        :to="{ name: 'adminTasks' }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="../assets/icons/task.svg"
                            alt="task icon"
                            class="w-5 h-5"
                        />
                        <span class="flex-1 ms-3 whitespace-nowrap">Taks</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink
                        :to="{ name: 'adminCategories' }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="../assets/icons/category.svg"
                            alt="category icon"
                            class="w-5 h-5"
                        />
                        <span class="flex-1 ms-3 whitespace-nowrap"
                            >Categories</span
                        >
                    </RouterLink>
                </li>
                <li>
                    <RouterLink
                        :to="{ name: 'adminUsers' }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="../assets/icons/employees.svg"
                            alt="employyees icon"
                            class="w-5 h-5"
                        />
                        <span class="flex-1 ms-3 whitespace-nowrap"
                            >Employees</span
                        >
                    </RouterLink>
                </li>
                <li>
                    <RouterLink
                        :to="{ name: 'adminGroup' }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="../assets/icons/group.svg"
                            alt="group icon"
                            class="w-5 h-5"
                        />
                        <span class="flex-1 ms-3 whitespace-nowrap"
                            >Groups</span
                        >
                    </RouterLink>
                </li>
                <li>
                    <RouterLink
                        :to="{ name: 'adminProfile', params: {id: user.id} }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="../assets/icons/profile.svg"
                            alt="group icon"
                            class="w-5 h-5"
                        />
                        <span class="flex-1 ms-3 whitespace-nowrap"
                            >Profile</span
                        >
                    </RouterLink>
                </li>
                <li>
                    <RouterLink
                        :to="{ name: 'logout' }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="../assets/icons/signout.svg"
                            alt="signout icon"
                            class="w-5 h-5"
                        />
                        <span class="flex-1 ms-3 whitespace-nowrap"
                            >Sign Out</span
                        >
                    </RouterLink>
                </li>
            </ul>
        </div>
    </aside>

    <main class="p-4 sm:ml-64">
        <div class="px-4 mt-14">
            <slot />
        </div>
    </main>
</template>

<style>
.router-link-exact-active {
    background-color: #f3f4f6;
}
</style>
