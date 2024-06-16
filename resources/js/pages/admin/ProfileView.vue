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
        <div class="my-20 w-[1536px] mx-auto h-[90vh]">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <ProfileUpdate :id="$route.params.id"/>
                </div>
                <div>
                    <PasswordChange :id="$route.params.id"/>
                </div>
            </div>
        </div>
    </div>
</template>
