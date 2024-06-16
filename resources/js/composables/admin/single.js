import axiosRequest from "./axiosRequest";

export default function useSingleRecord(api) {
    const singleRecord = async () => {
        return await axiosRequest('GET', api);
    };

    return { singleRecord };
}
