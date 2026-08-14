export default function ConfirmModal({
  open,
  title,
  message,
  onConfirm,
  onCancel,
  confirmText = "Eliminar",
  cancelText = "Cancelar",
  tone = "danger",
  busy = false,
}) {
  if (!open) return null;

  return (
    <div className="modal-overlay" role="presentation" onMouseDown={onCancel}>
      {/* Evita que un clic dentro del cuadro cierre el modal desde el fondo. */}
      <div
        className="modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-title"
        onMouseDown={(event) => event.stopPropagation()}
      >
        <h3 id="modal-title">{title}</h3>

        <p>{message}</p>

        <div className="modal-actions">
          <button
            type="button"
            className="btn-secondary"
            onClick={onCancel}
            disabled={busy}
          >
            {cancelText}
          </button>

          <button
            type="button"
            className={tone === "primary" ? "btn-primary" : "btn-danger"}
            onClick={onConfirm}
            disabled={busy}
          >
            {busy ? "Procesando..." : confirmText}
          </button>
        </div>
      </div>
    </div>
  );
}
