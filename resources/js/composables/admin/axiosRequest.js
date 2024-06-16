import axios from "axios";

const getToken = () => localStorage.getItem("accessToken");

const axiosRequest = async (method, url, data = null) => {
    const token = getToken();
    try {
        const response = await axios({
            method,
            url,
            data,
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        return response.data;
    } catch (error) {
        console.error(`Error during ${method} request to ${url}:`, error);
        if (error.response) {
            throw error.response.data;
        } else {
            throw new Error('Request failed');
        }
    }
};

export default axiosRequest;