<script setup>
import useSignin from "../../composables/auth/signin";
import { ref, reactive } from "vue";
import { useRouter, useRoute } from "vue-router";
import { FwbCard, FwbInput, FwbButton, FwbA, FwbToast } from "flowbite-vue";
import { useToast } from "vue-toastification";

const { signin, isLoading, error } = useSignin();
const router = useRouter();
const route = useRoute();

const formFields = reactive({
    email: "",
    password: "",
});

const toast = useToast();
const errors = ref({});

const login = async () => {
    try {
        const res = await signin(formFields.email, formFields.password);
        if (res && res.success) {
            localStorage.setItem("accessToken", res.token);
            localStorage.setItem("user", JSON.stringify(res.user));
            if (res.user.is_admin) {
                router.push({ name: "adminDashboard" });
            } else {
                router.push({ name: "employeeDashboard" });
            }
        } else {
            if (error.value && typeof error.value.error === "object") {
                errors.value = error.value.error;
            } else {
                errors.value.general =
                    error.value || "An error occurred during login.";
                toast.error(errors.value.general);
            }
        }
    } catch (err) {
        console.error("Login error:", err);
        errors.value.general = "An error occurred during login.";
    }
};
</script>

<template>
    <div class="h-[90vh] flex justify-center items-center">
        <fwb-toast
            closable
            type="danger"
            v-show="route.query.unauthorized"
            class="absolute top-14 right-0"
        >
            You are unauthorized
        </fwb-toast>
        <div>
            <h2 class="text-3xl uppercase text-center mb-5">Login</h2>
            <fwb-card class="w-[500px]">
                <form @submit.prevent="login()">
                    <div class="p-5">
                        <div class="mb-5">
                            <fwb-input
                                v-model="formFields.email"
                                placeholder="Enter your email"
                                label="Email"
                                type="email"
                            />
                            <span v-if="errors.email" class="text-red-600">{{
                                errors.email[0]
                            }}</span>
                        </div>
                        <div class="mb-5">
                            <fwb-input
                                v-model="formFields.password"
                                placeholder="Enter your password"
                                label="Password"
                                type="password"
                            />
                            <span v-if="errors.password" class="text-red-600">{{
                                errors.password[0]
                            }}</span>
                        </div>
                        <div class="mb-5">
                            <p>
                                Forget Password?
                                <fwb-a class="text-purple-500 italic" href="#">
                                    Send Request to Reset
                                </fwb-a>
                            </p>
                        </div>
                        <div class="mb-5">
                            <fwb-button :disabled="isLoading" color="purple">
                                <span v-if="isLoading">Loading...</span>
                                <span v-else>Login</span>
                            </fwb-button>
                        </div>
                        <div class="mb-5">
                            <span v-if="errors.general" class="text-red-600">{{
                                errors.general
                            }}</span>
                        </div>
                    </div>
                </form>
            </fwb-card>
        </div>
    </div>
</template>
