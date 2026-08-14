import { useEffect, useState } from "react";
import contactService from "./services/contactService";

function App() {
    const [contacts, setContacts] = useState([]);

    useEffect(() => {
        const loadData = async () => {
            try {
                const response = await contactService.getAll();
                setContacts(response.datos);
            } catch (error) {
                console.error("Error al cargar contactos:", error);
            }
        };

        loadData();
    }, []);

    return (
        <div style={{ padding: "40px" }}>
            <h1>Contact Manager</h1>

            <p>Conexión con la API funcionando.</p>

            <pre>{JSON.stringify(contacts, null, 2)}</pre>
        </div>
    );
}

export default App;