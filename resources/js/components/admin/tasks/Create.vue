<script setup>
import useCreateRecord from "../../../composables/admin/create.js";
import useAllRecord from "../../../composables/admin/all.js";
import {
    FwbButton,
    FwbModal,
    FwbInput,
    FwbSelect,
    FwbTextarea,
    FwbRadio,
} from "flowbite-vue";
import { ref, reactive, onMounted, computed } from "vue";
import { useToast } from "vue-toastification";

const props = defineProps(["id"]);
const emit = defineEmits(["recordCreated"]);

const { createRecord } = useCreateRecord("/api/admin/tasks");
const { allRecord: allCategories } = useAllRecord("/api/admin/categories");
const { allRecord: allEmployees } = useAllRecord("/api/admin/users");
const { allRecord: allGroups } = useAllRecord("/api/admin/groups");

const formFields = reactive({
    title: "",
    description: "",
    due_date: "",
    priority: "high",
    status: "pending",
    category_id: "",
    user_id: "",
    group_id: "",
});

const priorities = [
    { value: "high", name: "High" },
    { value: "medium", name: "Medium" },
    { value: "low", name: "Low" },
];

const statusOptions = [
    { value: "pending", name: "Pending" },
    { value: "review", name: "Review" },
    { value: "complete", name: "Complete" },
];

const toast = useToast();
const errors = ref({});
const categories = ref([]);
const assign = ref(null);
const groups = ref([]);
const individuals = ref([]);

const fetchCategories = async () => {
    const response = await allCategories();
    categories.value = response.data.map((category) => ({
        value: category.id,
        name: category.name,
    }));
};

const fetchEmployees = async () => {
    const response = await allEmployees();
    individuals.value = response.data.map((individual) => ({
        value: individual.id,
        name: individual.name,
    }));
};

const fetchGroups = async () => {
    const response = await allGroups();
    groups.value = response.data.map((group) => ({
        value: group.id,
        name: group.name,
    }));
};

const minDate = computed(() => {
    return new Date().toISOString().split("T")[0];
});

const storeRecord = async () => {
    try {
        const response = await createRecord(formFields);
        if (response.success) {
            emit("recordCreated", "New Task Created Successfully");
            closeModal();
            Object.keys(formFields).forEach((key) => {
                formFields[key] = "";
            });
        } else {
            errors.value = response.errors;
        }
    } catch (error) {
        if (error.errors) {
            errors.value = error.errors;
        } else {
            toast.error("Error creating task");
            console.error("Error creating task:", error);
        }
    }
};

onMounted(() => {
    fetchCategories();
    fetchEmployees();
    fetchGroups();
});

const isShowModal = ref(false);

const closeModal = () => {
    errors.value = "";
    Object.keys(formFields).forEach((key) => {
        formFields[key] = "";
    });
    isShowModal.value = false;
}

const showModal = () => {
    isShowModal.value = true;
}
</script>

<template>
    <div>
        <div class="text-right">
            <fwb-button color="purple" @click="showModal" size="xs">
                <img
                    src="/public/assets/icons/pluse.svg"
                    alt="plus icon"
                    class="w-5 h-5"
                />
            </fwb-button>
            <fwb-modal v-if="isShowModal" @close="closeModal" size="5xl">
                <template #header>
                    <div class="flex items-center text-lg uppercase">
                        Create new task
                    </div>
                </template>
                <template #body>
                    <form
                        class="text-left"
                        id="create"
                        @submit.prevent="storeRecord"
                    >
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-2">
                            <div>
                                <div class="mb-5">
                                    <fwb-input
                                        placeholder="Enter Title"
                                        label="Title *"
                                        v-model="formFields.title"
                                        required
                                    />
                                    <span
                                        v-if="errors.title"
                                        class="text-red-600"
                                        >{{ errors.title[0] }}</span
                                    >
                                </div>

                                <div class="mb-5">
                                    <fwb-input
                                        placeholder="Due Date"
                                        label="Due Date *"
                                        v-model="formFields.due_date"
                                        type="date"
                                        :min="minDate"
                                        required
                                    />
                                    <span
                                        v-if="errors.due_date"
                                        class="text-red-600"
                                        >{{ errors.due_date[0] }}</span
                                    >
                                </div>

                                <div class="mb-5">
                                    <fwb-select
                                        v-model="formFields.status"
                                        :options="statusOptions"
                                        label="Status"
                                    />
                                    <span
                                        v-if="errors.status"
                                        class="text-red-600"
                                        >{{ errors.status[0] }}</span
                                    >
                                </div>
                            </div>
                            <div>
                                <div class="mb-5">
                                    <fwb-select
                                        v-model="formFields.category_id"
                                        :options="categories"
                                        label="Category *"
                                        required
                                    />
                                    <span
                                        v-if="errors.category_id"
                                        class="text-red-600"
                                        >{{ errors.category_id[0] }}</span
                                    >
                                </div>
                                <div class="mb-5">
                                    <fwb-select
                                        v-model="formFields.priority"
                                        :options="priorities"
                                        label="Priority"
                                    />
                                    <span
                                        v-if="errors.priority"
                                        class="text-red-600"
                                        >{{ errors.priority[0] }}</span
                                    >
                                </div>
                                <div class="mb-5">
                                    <label
                                        for="assign"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                        >Assign to</label
                                    >
                                    <div class="flex px-5 items-center">
                                        <fwb-radio
                                            v-model="assign"
                                            name="assign"
                                            label="Individual"
                                            value="individual"
                                        />
                                        <fwb-radio
                                            v-model="assign"
                                            name="assign"
                                            label="Group"
                                            value="group"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div
                                class="mb-5 col-span-2"
                                v-if="assign == 'individual'"
                            >
                                <fwb-select
                                    v-model="formFields.user_id"
                                    :options="individuals"
                                    label="Employees"
                                />
                            </div>
                            <div
                                class="mb-5 col-span-2"
                                v-if="assign == 'group'"
                            >
                                <fwb-select
                                    v-model="formFields.group_id"
                                    :options="groups"
                                    label="Group"
                                />
                            </div>
                            <div class="col-span-2">
                                <fwb-textarea
                                    placeholder="Enter Description"
                                    label="Description"
                                    v-model="formFields.description"
                                />
                            </div>
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
