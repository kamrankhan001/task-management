import { ref } from "vue";
import axios from "axios";

export default function useSignin() {
    const isLoading = ref(false);
    const error = ref(null);

    const signin = async (email, password) => {
        isLoading.value = true;
        error.value = null;

        try {
            const response = await axios.post('/api/login', {
                email,
                password,
            });

            if (response.data.success) {
                return response.data;
            } else {
                throw new Error('Invalid credentials');
            }
        } catch (err) {
            if (err.response && err.response.status === 401) {
                error.value = 'Invalid credentials';
            } else {
                error.value = err.response?.data || 'An error occurred';
            }
            return null;
        } finally {
            isLoading.value = false;
        }
    };

    return { signin, isLoading, error };
}
