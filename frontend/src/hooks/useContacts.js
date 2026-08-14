import { useEffect, useState } from "react";
import contactService from "../services/contactService";

export default function useContacts() {
    const [contacts, setContacts] = useState([]);
    const [search, setSearch] = useState("");
    const [loading, setLoading] = useState(false);

    // Obtener contactos
    const loadContacts = async (text = "") => {
        setLoading(true);

        try {
            const response = await contactService.getAll(text);
            setContacts(response.datos ?? []);
        } catch (error) {
            console.error("Error al cargar contactos:", error);
            setContacts([]);
        } finally {
            setLoading(false);
        }
    };

    // Crear contacto
    const createContact = async (contact) => {
        try {
            const response = await contactService.create(contact);

            await loadContacts(search);

            return {
                success: true,
                data: response,
            };
        } catch (error) {
            console.error("Error al crear contacto:", error);

            return {
                success: false,
                errors: error.response?.data?.errores ?? {
                    general: "No fue posible crear el contacto.",
                },
            };
        }
    };

    // Eliminar contacto
    const deleteContact = async (id) => {
        try {
            await contactService.remove(id);

            await loadContacts(search);

            return true;
        } catch (error) {
            console.error("Error al eliminar contacto:", error);
            return false;
        }
    };

   useEffect(() => {

    const timer = setTimeout(() => {
        loadContacts(search);
    }, 300);

    return () => clearTimeout(timer);

}, [search]);

    return {
        contacts,
        search,
        setSearch,
        loading,
        loadContacts,
        createContact,
        deleteContact,
    };
}