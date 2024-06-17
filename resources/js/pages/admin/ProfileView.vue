<script setup>
import { defineAsyncComponent } from "vue";

const ProfileUpdate = defineAsyncComponent(() =>
    import("../../components/admin/profile/ProfileUpdate.vue")
);
const PasswordChange = defineAsyncComponent(() =>
    import("../../components/admin/profile/PasswordChange.vue")
);
</script>

<template>
    <div>
        <div class="my-20 mx-auto">
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
                                src="/public/assets/icons/profile.svg"
                                alt="group icon"
                                class="w-4 h-4"
                            />
                            Profile
                        </a>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-center">
                <div style="width: 500px;">
                    <ProfileUpdate :id="$route.params.id" />
                </div>
            </div>
        </div>
    </div>
</template>
