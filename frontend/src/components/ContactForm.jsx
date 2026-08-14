import { useState } from "react";

const initialState = {
    nombre: "",
    email: "",
    Telefono: ""
};

export default function ContactForm({ onSubmit }) {

    const [form, setForm] = useState(initialState);
    const [errors, setErrors] = useState({});
    const [sending, setSending] = useState(false);

    const handleChange = ({ target }) => {

        const { name, value } = target;

        setForm((prev) => ({
            ...prev,
            [name]: value
        }));

        setErrors((prev) => ({
            ...prev,
            [name]: ""
        }));
    };

    const validate = () => {

        const newErrors = {};

        if (!form.nombre.trim()) {
            newErrors.nombre = "Ingrese un nombre.";
        }

        if (!form.email.trim()) {
            newErrors.email = "Ingrese un correo.";
        }

        if (!form.Telefono.trim()) {
            newErrors.Telefono = "Ingrese un teléfono.";
        }

        setErrors(newErrors);

        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = async (event) => {

        event.preventDefault();

        if (!validate()) return;

        setSending(true);

        try {

            const response = await onSubmit(form);

            if (response?.success) {

                setForm(initialState);
                setErrors({});

            } else if (response?.errors) {

                setErrors(response.errors);

            }

        } finally {

            setSending(false);

        }
    };

    return (

        <form className="contact-form" onSubmit={handleSubmit}>

            <h2>Nuevo contacto</h2>

            <div className="form-group">

                <label htmlFor="nombre">Nombre</label>

                <input
                    id="nombre"
                    name="nombre"
                    type="text"
                    placeholder="Ej: Edinson Sierra"
                    value={form.nombre}
                    onChange={handleChange}
                />

                {errors.nombre && (
                    <small className="error">{errors.nombre}</small>
                )}

            </div>

            <div className="form-group">

                <label htmlFor="email">Correo electrónico</label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="correo@ejemplo.com"
                    value={form.email}
                    onChange={handleChange}
                />

                {errors.email && (
                    <small className="error">{errors.email}</small>
                )}

            </div>

            <div className="form-group">

                <label htmlFor="Telefono">Teléfono</label>

                <input
                    id="Telefono"
                    name="Telefono"
                    type="text"
                    placeholder="3001234567"
                    value={form.Telefono}
                    onChange={handleChange}
                />

                {errors.Telefono && (
                    <small className="error">{errors.Telefono}</small>
                )}

            </div>

            <button
                type="submit"
                className="btn-primary"
                disabled={sending}
            >
                {sending ? "Guardando..." : "Guardar contacto"}
            </button>

        </form>
    );
}