import axios from "axios";
import { ref } from "vue";

export default function useTaskComplete(api) {
    const token = localStorage.getItem("accessToken");

    const url = ref(api);

    const taskComplete = async () => {
        try {
            const response = await axios({
                method: "PATCH",
                url: url.value,
                headers: {
                    Authorization: "Bearer " + token,
                },
            });
            return response.data;
        } catch (error) {
            return error.response.data;
        }
    };

    return { taskComplete };
}
