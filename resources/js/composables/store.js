import { ref } from "vue";

export default function useStates() {
    const userData = ref(null);

    const storeCategory = ref(false);
    const storeTask = ref(false);
    const storeUser = ref(false);
    const storeGroup = ref(false);

    return { storeCategory, storeTask, storeUser, storeGroup, userData };
}
