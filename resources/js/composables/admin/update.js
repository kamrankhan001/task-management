import axiosRequest from "./axiosRequest";

export default function useUpdateRecord(api) {
    const updateRecord = async (data) => {
        return await axiosRequest('PATCH', api, data);
    };

    return { updateRecord };
}
