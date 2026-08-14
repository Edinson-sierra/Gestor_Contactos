import { useEffect, useState } from "react";
import contactService from "../services/contactService";

export default function useContacts() {
  const [contacts, setContacts] = useState([]);
  const [search, setSearch] = useState("");
  const [loading, setLoading] = useState(false);
  const [loadError, setLoadError] = useState("");

  const loadContacts = async (text = "") => {
    setLoading(true);

    try {
      const response = await contactService.getAll(text);
      setContacts(response.datos ?? []);
      setLoadError("");
    } catch (error) {
      console.error("Error al cargar contactos:", error);
      setContacts([]);
      setLoadError(
        "No fue posible cargar los contactos. Verifique la conexión con la API.",
      );
    } finally {
      setLoading(false);
    }
  };

  const createContact = async (contact, reemplazar = false) => {
    try {
      const response = await contactService.create(contact, reemplazar);

      // La recarga de la lista no debe mantener bloqueado el botón de guardar.
      loadContacts(search);

      return {
        success: true,
        data: response,
      };
    } catch (error) {
      console.error("Error al crear contacto:", error);

      const data = error.response?.data;

      if (data?.codigo === "TELEFONO_DUPLICADO") {
        return {
          success: false,
          duplicate: true,
          existing: data.contacto,
          message: data.mensaje,
        };
      }

      return {
        success: false,
        errors: data?.errores ?? {
          general: data?.mensaje ?? "No fue posible crear el contacto.",
        },
      };
    }
  };

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
    // Espera brevemente para no consultar la API en cada tecla escrita.
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
    loadError,
    loadContacts,
    createContact,
    deleteContact,
  };
}
