import axios from "axios";

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    headers: {
        "Content-Type": "application/json"
    }
});

const contactService = {

    async getAll(search = "") {

        const response = await api.get("/contacts", {
            params: { search }
        });

        return response.data;
    },

    async create(contact) {

        const response = await api.post("/contacts", contact);

        return response.data;
    },

    async remove(id) {

        const response = await api.delete(`/contacts/${id}`);

        return response.data;
    }

};

export default contactService;