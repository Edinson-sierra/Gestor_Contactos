export default function ContactTable({ contacts, onDelete }) {

    if (contacts.length === 0) {

        return (
            <div className="empty-state">
                <p>No se encontraron contactos.</p>
            </div>
        );

    }

    return (

        <div className="table-container">

            <table className="contact-table">

                <thead>

                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Creado</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    {contacts.map((contact) => (

                        <tr key={contact.id}>

                            <td>{contact.nombre}</td>

                            <td>{contact.email}</td>

                            <td>{contact.Telefono}</td>
                            <td>
                                 {new Date(contact.created_at).toLocaleDateString("es-CO")}
                            </td>

                            <td>
                                <button className="btn-danger"
                                 onClick={() => onDelete(contact)}>
                                    Eliminar
                                </button>
                            </td>

                        </tr>

                    ))}

                </tbody>

            </table>

        </div>

    );

}