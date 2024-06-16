import { useRouter } from "vue-router";

export default function useAdminGuard() {
    const router = useRouter();

    const token = localStorage.getItem("accessToken");
    let user = null;

    try {
        user = JSON.parse(localStorage.getItem("user"));
    } catch (error) {
        console.error('Error parsing user object:', error);
    }

    if (!token || !user || !user.is_admin) {
        router.push({ name: "employeeTask" });
    }
}
