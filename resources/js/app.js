import { createApp } from "vue";
import App from "./App.vue";

import "flowbite";
import Toast, { POSITION } from "vue-toastification";

import "../../node_modules/flowbite-vue/dist/index.css";
import "vue-toastification/dist/index.css";

import router from "./router/index.js";

import useStates from "./composables/store.js";

router.beforeEach((to, from, next) => {
    document.title = to.meta.title || "Title";
    next();
});

const app = createApp(App);
const {userData, storeCategory, storeTask, storeUser, storeGroup} = useStates();
app.use(router);
app.use(Toast, {
    position: POSITION.TOP_LEFT,
    timeout: 2000,
});

app.provide('storeCategory',storeCategory);
app.provide('storeTask',storeTask);
app.provide('storeUser',storeUser);
app.provide('storeGroup',storeGroup);
app.provide('user',userData);

app.mount("#app");
