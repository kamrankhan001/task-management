import axiosRequest from "./axiosRequest";

export default function useCreateRecord(api) {
    const createRecord = async (data) => {
        return await axiosRequest('POST', api, data);
    };

    return { createRecord };
}
