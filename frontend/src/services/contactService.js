import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    "Content-Type": "application/json",
  },
});

const contactService = {
  async getAll(search = "") {
    const response = await api.get("/contactos", {
      params: { search },
    });
    return response.data;
  },

  async create(contact, reemplazar = false) {
    const response = await api.post("/contactos", {
      ...contact,
      reemplazar,
    });
    return response.data;
  },

  async remove(id) {
    const response = await api.delete(`/contactos/${id}`);
    return response.data;
  },
};

export default contactService;
