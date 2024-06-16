import axiosRequest from "./axiosRequest";

export default function useDeleteRecord(api) {
    const deleteRecord = async () => {
        return await axiosRequest('DELETE', api);
    };

    return { deleteRecord };
}
