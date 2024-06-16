<script setup>
import useCreateRecord from "../../../composables/admin/create.js";
import { ref, reactive } from "vue";
import { FwbButton, FwbModal, FwbInput } from "flowbite-vue";
import { useToast } from "vue-toastification";

const emit = defineEmits(["recordCreated"]);

const { createRecord } = useCreateRecord("/api/admin/categories");

const formFields = reactive({
    name: "",
});

const toast = useToast();
const errors = ref({});

const storeRecord = async () => {
    try {
        const response = await createRecord(formFields);
        if (response.success) {
            emit("recordCreated", "Category Created Successfully");
            closeModal();
            formFields.name = "";
        } else {
            errors.value = response.errors;
        }
    } catch (error) {
        if (error.errors) {
            errors.value = error.errors;
        } else {
            toast.error("Error creating category");
            console.error("Error creating category:", error);
        }
    }
};

const isShowModal = ref(false);

function closeModal() {
    errors.value = '';
    formFields.name = '';
    isShowModal.value = false;
}

function showModal() {
    isShowModal.value = true;
}
</script>
<template>
    <div>
        <div class="text-right">
            <fwb-button color="purple" @click="showModal" size="xs">
                <img
                    src="/resources/js/assets/icons/pluse.svg"
                    alt="plus icon"
                    class="w-5 h-5"
                />
            </fwb-button>
            <fwb-modal v-if="isShowModal" @close="closeModal">
                <template #header>
                    <div class="flex items-center text-lg uppercase">
                        Create New Category
                    </div>
                </template>
                <template #body>
                    <form
                        id="create"
                        @submit.prevent="storeRecord"
                        class="text-left"
                    >
                        <div class="mb-5">
                            <fwb-input
                                placeholder="Enter Category"
                                label="Category *"
                                v-model="formFields.name"
                                required
                            />
                            <span v-if="errors.name" class="text-red-600">
                                {{ errors.name[0] }}
                            </span>
                        </div>
                    </form>
                </template>
                <template #footer>
                    <div class="flex justify-between">
                        <fwb-button @click="closeModal" color="alternative">
                            Cancel
                        </fwb-button>
                        <fwb-button type="submit" form="create" color="purple">
                            Create
                        </fwb-button>
                    </div>
                </template>
            </fwb-modal>
        </div>
    </div>
</template>
