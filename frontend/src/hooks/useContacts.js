import { useEffect, useState } from "react";
import contactService from "../services/contactService";

export default function useContacts() {

    const [contacts, setContacts] = useState([]);
    const [search, setSearch] = useState("");
    const [loading, setLoading] = useState(false);

    const loadContacts = async (text = "") => {

        setLoading(true);

        try {

            const response = await contactService.getAll(text);

            setContacts(response.datos);

        } catch (error) {

            console.error("Error al cargar contactos:", error);

        } finally {

            setLoading(false);

        }

    };

    useEffect(() => {

        loadContacts(search);

    }, [search]);

    return {
        contacts,
        search,
        setSearch,
        loading,
        loadContacts
    };

}