import { useState, useRef, useEffect } from "react";

import SearchBar from "../components/SearchBar";
import ContactForm from "../components/ContactForm";
import ContactTable from "../components/ContactTable";
import ConfirmModal from "../components/ConfirmModal";
import Toast from "../components/Toast";

import useContacts from "../hooks/useContacts";

export default function Home() {
  const {
    contacts,
    search,
    setSearch,
    loading,
    loadError,
    createContact,
    deleteContact,
  } = useContacts();

  const [selected, setSelected] = useState(null);

  const [toast, setToast] = useState({
    message: "",
    type: "success",
  });

  // useRef conserva el temporizador entre renderizados sin provocar uno nuevo.
  const toastTimeoutRef = useRef(null);

  const mostrarToast = (message, type = "success") => {
    if (toastTimeoutRef.current) {
      clearTimeout(toastTimeoutRef.current);
    }

    setToast({ message, type });

    toastTimeoutRef.current = setTimeout(() => {
      setToast({ message: "", type: "success" });
      toastTimeoutRef.current = null;
    }, 4000);
  };

  useEffect(() => {
    return () => {
      if (toastTimeoutRef.current) {
        clearTimeout(toastTimeoutRef.current);
      }
    };
  }, []);

  const handleSubmit = async (contact, reemplazar = false) => {
    const result = await createContact(contact, reemplazar);

    if (result.success) {
      mostrarToast(
        result.data?.reemplazado
          ? "Contacto reemplazado correctamente."
          : "Contacto creado correctamente.",
        "success",
      );
    } else if (!result.duplicate) {
      mostrarToast("No fue posible guardar el contacto.", "error");
    }

    return result;
  };

  const handleDelete = async () => {
    if (!selected) return;

    const ok = await deleteContact(selected.id);

    if (ok) {
      mostrarToast("Contacto eliminado.", "success");
    } else {
      mostrarToast("No fue posible eliminar.", "error");
    }

    setSelected(null);
  };

  return (
    <main className="container">
      <header className="header">
        <h1>Gestor de Contactos</h1>
        <p>Guarda y encuentra la información que necesitas.</p>
      </header>

      <SearchBar value={search} onChange={setSearch} />

      <div className="content-grid">
        <ContactForm onSubmit={handleSubmit} />

        <section className="contacts-panel">
          {loading ? (
            <p className="loading-state">Cargando contactos...</p>
          ) : loadError ? (
            <p className="load-error" role="alert">
              {loadError}
            </p>
          ) : (
            <>
              <div className="list-heading">
                <h2>Contactos</h2>
                <span className="contact-count">{contacts.length}</span>
              </div>

              <ContactTable contacts={contacts} onDelete={setSelected} />
            </>
          )}
        </section>
      </div>

      <ConfirmModal
        open={selected !== null}
        title="Eliminar contacto"
        message={`¿Desea eliminar a ${selected?.nombre}?`}
        onConfirm={handleDelete}
        onCancel={() => setSelected(null)}
      />

      <Toast message={toast.message} type={toast.type} />
    </main>
  );
}
