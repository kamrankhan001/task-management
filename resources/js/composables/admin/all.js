import { ref } from "vue";
import axiosRequest from "./axiosRequest";

export default function useAllRecord(api) {
    const url = ref(api);

    const allRecord = async (page = 1, query = '') => {
        return await axiosRequest('GET', `${url.value}?page=${page}${query}`);
    };

    return { allRecord };
}
