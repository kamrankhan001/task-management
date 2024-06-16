import { ref } from "vue";
import axiosRequest from "./axiosRequest";

export default function useStats(api) {
    const url = ref(api);

    const stats = async () => {
        return await axiosRequest('GET', url.value);
    };

    return { stats };
}
