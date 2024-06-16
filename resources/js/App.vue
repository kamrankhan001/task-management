<script setup>
import Admin from './components/layouts/Admin.vue';
import Employee from './components/layouts/Employee.vue';

import { RouterView, useRoute } from 'vue-router';

const route = useRoute()
</script>

<template>
    <div>
        <div v-if="route.meta.layout == 'default'">
            <RouterView/>
        </div>
        <div v-if="route.meta.layout == 'admin'">
            <Admin>
                <RouterView/>
            </Admin>
        </div>

        <div v-if="route.meta.layout == 'user'">
            <Employee>
                <RouterView/>
            </Employee>
        </div>
    </div>
</template>