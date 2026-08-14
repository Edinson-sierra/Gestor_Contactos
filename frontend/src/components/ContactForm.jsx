import { useState } from "react";
import ConfirmModal from "./ConfirmModal";

const initialState = {
  nombre: "",
  email: "",
  Telefono: "",
};

export default function ContactForm({ onSubmit }) {
  const [form, setForm] = useState(initialState);
  const [errors, setErrors] = useState({});
  const [sending, setSending] = useState(false);
  const [duplicate, setDuplicate] = useState(null);

  const handleChange = ({ target }) => {
    const { name, value } = target;

    setForm((prev) => ({
      ...prev,
      [name]: value,
    }));

    setErrors((prev) => ({
      ...prev,
      [name]: "",
      general: "",
    }));
  };

  const validate = () => {
    const newErrors = {};

    if (!form.nombre.trim()) {
      newErrors.nombre = "Ingrese un nombre.";
    } else if (
      form.nombre.trim().length < 2 ||
      form.nombre.trim().length > 100
    ) {
      newErrors.nombre = "El nombre debe tener entre 2 y 100 caracteres.";
    }

    if (!form.email.trim()) {
      newErrors.email = "Ingrese un correo.";
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email.trim())) {
      newErrors.email = "Ingrese un correo válido.";
    }

    if (!form.Telefono.trim()) {
      newErrors.Telefono = "Ingrese un teléfono.";
    }

    if (form.Telefono.trim() && !/^[0-9]{7,15}$/.test(form.Telefono.trim())) {
      newErrors.Telefono = "Use entre 7 y 15 dígitos, sin espacios.";
    }

    setErrors(newErrors);

    return Object.keys(newErrors).length === 0;
  };

  // Crear y reemplazar comparten el mismo flujo; solamente cambia esta bandera.
  const sendContact = async (replace = false) => {
    setSending(true);

    try {
      const response = await onSubmit(form, replace);

      if (response?.success) {
        setForm(initialState);
        setErrors({});
        setDuplicate(null);
      } else if (response?.duplicate) {
        setDuplicate(response.existing);
      } else if (response?.errors) {
        setErrors(response.errors);
        setDuplicate(null);
      }
    } finally {
      setSending(false);
    }
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    if (!validate()) return;

    await sendContact();
  };

  return (
    <form className="contact-form" onSubmit={handleSubmit} noValidate>
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
          aria-invalid={Boolean(errors.nombre)}
        />

        {errors.nombre && <small className="error">{errors.nombre}</small>}
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
          aria-invalid={Boolean(errors.email)}
        />

        {errors.email && <small className="error">{errors.email}</small>}
      </div>

      <div className="form-group">
        <label htmlFor="Telefono">Teléfono</label>

        <input
          id="Telefono"
          name="Telefono"
          type="text"
          inputMode="numeric"
          placeholder="3001234567"
          value={form.Telefono}
          onChange={handleChange}
          aria-invalid={Boolean(errors.Telefono)}
        />

        {errors.Telefono && <small className="error">{errors.Telefono}</small>}
      </div>

      {errors.general && (
        <p className="form-error" role="alert">
          {errors.general}
        </p>
      )}

      <button type="submit" className="btn-primary" disabled={sending}>
        {sending ? "Guardando..." : "Guardar contacto"}
      </button>

      <ConfirmModal
        open={duplicate !== null}
        title="Teléfono ya registrado"
        message={`El número pertenece a ${duplicate?.nombre}. ¿Desea reemplazar ese contacto con los datos nuevos?`}
        confirmText="Reemplazar"
        cancelText="Rechazar"
        tone="primary"
        busy={sending}
        onConfirm={() => sendContact(true)}
        onCancel={() => setDuplicate(null)}
      />
    </form>
  );
}
