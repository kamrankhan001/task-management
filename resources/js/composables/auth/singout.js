import axios from "axios";
import { useRouter } from "vue-router";

export default function useSignout() {
    const router = useRouter();

    const signout = async () => {
        const token = localStorage.getItem("accessToken");

        if (token) {
            try {
                const response = await axios({
                    method: "post",
                    url: "api/logout",
                    headers: {
                        Authorization: "Bearer " + token,
                    },
                });

                if (response.data.success) {
                    localStorage.removeItem("accessToken");
                    localStorage.removeItem("user");
                    if (router) {
                        router.push({ name: "login" });
                    }
                }
            } catch (error) {
                alert("something went wrong");
            }
        }
    };

    return { signout };
}
