<script setup>
import { RouterLink } from "vue-router";
import { ref, onBeforeMount, watch, onMounted } from "vue";
import { useRoute } from "vue-router";
import { initFlowbite } from "flowbite";

const route = useRoute();
let user = ref(null);

onBeforeMount(() => {
    user.value = JSON.parse(localStorage.getItem("user")) || null;
});

onMounted(() => {
    initFlowbite();
});

watch(route, () => {
    user.value = JSON.parse(localStorage.getItem("user")) || null;
});
</script>

<template>
    <nav
        class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700"
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

                        <svg
                            class="w-6 h-6"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                            ></path>
                        </svg>
                    </button>
                    <RouterLink
                        :to="{ name: 'employeeDashboard' }"
                        class="flex ms-2 md:me-24"
                    >
                        <img
                            src="/public/assets/images/oop.jpeg"
                            class="h-8 me-3 rounded-full"
                            alt="OOP Logo"
                        />
                        <span
                            class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white"
                            >The OOP</span
                        >
                    </RouterLink>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <div>
                            <button
                                type="button"
                                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                aria-expanded="false"
                                data-dropdown-toggle="dropdown-user"
                            >
                                <span class="sr-only">Open user menu</span>
                                <img
                                    class="w-8 h-8 rounded-full"
                                    src="/public/assets/images/oop.jpeg"
                                    alt="OOP image"
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
                                        :to="{ name: 'logout' }"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem"
                                    >
                                        <div class="flex gap-2">
                                            <img
                                                src="/public/assets/icons/signout.svg"
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
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
        aria-label="Sidebar"
    >
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <RouterLink
                        :to="{ name: 'employeeDashboard' }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="/public/assets/icons/dashboard.svg"
                            alt="dashboard icon"
                            class="w-5 h-5"
                        />
                        <span class="ms-3">Dashboard</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink
                        :to="{ name: 'employeeTask' }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="/public/assets/icons/task.svg"
                            alt="task icon"
                            class="w-5 h-5"
                        />
                        <span class="flex-1 ms-3 whitespace-nowrap">Taks</span>
                    </RouterLink>
                </li>
                <li>
                    <RouterLink
                        :to="{ name: 'employeeGroups' }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="/public/assets/icons/group.svg"
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
                        :to="{ name: 'logout' }"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                    >
                        <img
                            src="/public/assets/icons/signout.svg"
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
