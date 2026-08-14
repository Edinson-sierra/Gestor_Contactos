export default function barra_busqueda({ value, onChange }) {
    return (
        <div className="barra_busqueda">
            <input
                type="text"
                placeholder="Buscar por nombre, correo o teléfono..."
                value={value}
                onChange={(e) => onChange(e.target.value)}
            />
        </div>
    );
}