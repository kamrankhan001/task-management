<template>
    <div class="h-[90vh] flex justify-center items-center">
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