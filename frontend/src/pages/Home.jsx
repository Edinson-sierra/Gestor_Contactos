import { useState } from "react";

import SearchBar from "../components/barra_busqueda";
import ContactForm from "../components/ContactForm";
import ContactTable from "../components/tablacontacto";
import ConfirmModal from "../components/ConfirmModal";
import Toast from "../components/Toast";

import useContacts from "../hooks/useContacts";

export default function Home() {

    const {
        contacts,
        search,
        setSearch,
        loading,
        createContact,
        deleteContact
    } = useContacts();

    const [selected, setSelected] = useState(null);

    const [toast, setToast] = useState({
        message: "",
        type: "success"
    });

    const handleCreate = async (contact) => {

        const result = await createContact(contact);

        if (result.success) {

            setToast({
                message: "Contacto creado correctamente.",
                type: "success"
            });

        } else {

            setToast({
                message: "No fue posible crear el contacto.",
                type: "error"
            });

        }

        return result;
    };

    const handleDelete = async () => {

        if (!selected) return;

        const ok = await deleteContact(selected.id);

        if (ok) {

            setToast({
                message: "Contacto eliminado.",
                type: "success"
            });

        } else {

            setToast({
                message: "No fue posible eliminar.",
                type: "error"
            });

        }

        setSelected(null);
    };

    return (

        <main className="container">

            <header className="header">

                <h1>Gestor de Contactos</h1>

                <p>Administra tus contactos de forma sencilla.</p>

            </header>

            <SearchBar
                value={search}
                onChange={setSearch}
            />

            <div className="content-grid">

                <ContactForm onSubmit={handleCreate} />

                <section>

                    {loading ? (

                        <p>Cargando contactos...</p>

                    ) : (

                        <>
                            <p className="contact-count">
                                {contacts.length} contacto(s)
                            </p>

                            <ContactTable
                                contacts={contacts}
                                onDelete={setSelected}
                            />
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

            <Toast
                message={toast.message}
                type={toast.type}
            />

        </main>

    );
}