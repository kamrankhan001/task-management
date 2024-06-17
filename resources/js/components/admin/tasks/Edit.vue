<script setup>
import { ref, reactive, onMounted, inject, computed } from "vue";
import {
    FwbButton,
    FwbModal,
    FwbInput,
    FwbSelect,
    FwbTextarea,
    FwbRadio,
} from "flowbite-vue";
import { useToast } from "vue-toastification";

import useSingleRecord from "../../../composables/admin/single.js";
import useUpdateRecord from "../../../composables/admin/update.js";
import useAllRecord from "../../../composables/admin/all.js";

const props = defineProps(["id"]);
const emit = defineEmits(["recordUpdated"]);
const storeTask = inject('storeTask');

const { singleRecord } = useSingleRecord(`/api/admin/tasks/${props.id}`);
const { updateRecord } = useUpdateRecord(`/api/admin/tasks/${props.id}`);
const { allRecord: allCategories } = useAllRecord("/api/admin/categories");
const { allRecord: allEmployees } = useAllRecord("/api/admin/users");
const { allRecord: allGroups } = useAllRecord("/api/admin/groups");

const formFields = reactive({
    title: "",
    description: "",
    due_date: "",
    priority: "",
    status: "",
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
const individuals = ref([]);
const groups = ref([]);
const assign = ref("");

const fetchCategories = async () => {
    try {
        const response = await allCategories();
        categories.value = response.data.map(category => ({
            value: category.id,
            name: category.name,
        }));
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
};

const fetchEmployees = async () => {
    try {
        const response = await allEmployees();
        individuals.value = response.data.map(individual => ({
            value: individual.id,
            name: individual.name,
        }));
    } catch (error) {
        console.error("Error fetching users:", error);
    }
};

const fetchGroups = async () => {
    try {
        const response = await allGroups();
        groups.value = response.data.map(group => ({
            value: group.id,
            name: group.name,
        }));
    } catch (error) {
        console.error("Error fetching groups:", error);
    }
};

const minDate = computed(()=> {
    return new Date().toISOString().split('T')[0];
})

const fetchRecord = async () => {
    try {
        const record = await singleRecord();
        formFields.title = record.data.title;
        formFields.description = record.data.description;
        formFields.due_date = formatDate(record.data.due_date);
        formFields.priority = record.data.priority;
        formFields.status = record.data.status;
        formFields.category_id = record.data.category.id;
        if (record.data.user) {
            assign.value = "individual";
            formFields.user_id = record.data.user.id;
        } else if (record.data.group) {
            assign.value = "group";
            formFields.group_id = record.data.group.id;
        }
    } catch (error) {
        toast.error("Error updating task");
        console.error("Error fetching tasks:", error);
    }
};

const editRecord = async () => {
    try {
        const response = await updateRecord(formFields);
        if (response.success) {
            storeTask.value = true;
            emit("recordUpdated", "Task updated successfully");
            closeModal();
        } else {
            errors.value = response.error;
        }
    } catch (error) {
        if (error.errors) {
            errors.value = error.errors;
            console.log(errors.value)
        } else {
            toast.error("Error updating task");
            console.error("Error updating task:", error);
        }
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
}

onMounted(() => {
    fetchRecord();
    fetchCategories();
    fetchEmployees();
    fetchGroups();
});

const isShowModal = ref(false);

const closeModal = () => {
    isShowModal.value = false;
};

const showModal = () => {
    isShowModal.value = true;
};
</script>

<template>
    <div>
        <div class="text-right">
            <button
                type="button"
                class="px-4 py-2 text-sm font-medium bg-yellow-300 border-t border-b border-yellow-200 hover:bg-yellow-400 hover:text-yellow-700 focus:z-10 focus:ring-2 focus:ring-yellow-700 focus:text-yellow-700 dark:bg-yellow-800 dark:border-yellow-700 dark:hover:bg-gray-700 dark:focus:ring-yellow-500"
                @click="showModal"
            >
                <img
                    src="/public/assets/icons/edit.svg"
                    alt="edit icon"
                    class="w-5 h-4"
                />
            </button>
            <fwb-modal v-if="isShowModal" @close="closeModal" size="5xl">
                <template #header>
                    <div class="flex items-center text-lg uppercase">Edit Task</div>
                </template>
                <template #body>
                    <form class="text-left" id="edit" @submit.prevent="editRecord">
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-2">
                            <div>
                                <div class="mb-5">
                                    <fwb-input
                                        placeholder="Enter Title"
                                        label="Title *"
                                        v-model="formFields.title"
                                        required
                                    />
                                    <span v-if="errors?.title" class="text-red-600">{{ errors.title[0] }}</span>
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
                                    <span v-if="errors?.due_date" class="text-red-600">{{ errors.due_date[0] }}</span>
                                </div>
                                <div class="mb-5">
                                    <fwb-select
                                        v-model="formFields.status"
                                        :options="statusOptions"
                                        label="Status"
                                    />
                                    <span v-if="errors?.status" class="text-red-600">{{ errors.status[0] }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="mb-5">
                                    <fwb-select
                                        v-model="formFields.category_id"
                                        :options="categories"
                                        label="Category *"
                                    />
                                    <span v-if="errors?.category_id" class="text-red-600">{{ errors.category_id[0] }}</span>
                                </div>
                                <div class="mb-5">
                                    <fwb-select
                                        v-model="formFields.priority"
                                        :options="priorities"
                                        label="Priority"
                                    />
                                    <span v-if="errors?.priority" class="text-red-600">{{ errors.priority[0] }}</span>
                                </div>
                                <div class="mb-5">
                                    <label for="assign" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Assign to</label>
                                    <div class="flex px-5 items-center">
                                        <fwb-radio v-model="assign" name="assign" label="Individual" value="individual" />
                                        <fwb-radio v-model="assign" name="assign" label="Group" value="group" />
                                    </div>
                                </div>
                            </div>
                            <div class="mb-5 col-span-2" v-if="assign === 'individual'">
                                <fwb-select
                                    v-model="formFields.user_id"
                                    :options="individuals"
                                    label="User"
                                />
                            </div>
                            <div class="mb-5 col-span-2" v-if="assign === 'group'">
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
                                <span v-if="errors?.description" class="text-red-600">{{ errors.description[0] }}</span>
                            </div>
                        </div>
                    </form>
                </template>
                <template #footer>
                    <div class="flex justify-between">
                        <fwb-button @click="closeModal" color="alternative">Cancel</fwb-button>
                        <fwb-button type="submit" form="edit" color="yellow">Update</fwb-button>
                    </div>
                </template>
            </fwb-modal>
        </div>
    </div>
</template>
