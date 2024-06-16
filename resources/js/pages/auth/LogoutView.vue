<script setup>
import useSignout from '../../composables/auth/singout';

import { onMounted } from 'vue';

const {signout} = useSignout();

onMounted(()=>{
    signout();
});
</script>

<template>
 
</template>