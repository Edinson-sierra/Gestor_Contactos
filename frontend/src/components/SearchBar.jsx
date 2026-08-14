export default function SearchBar({ value, onChange }) {
  return (
    <div className="search-bar" role="search">
      <label htmlFor="contact-search">Buscar contactos</label>
      <input
        id="contact-search"
        type="text"
        placeholder="Buscar por nombre, correo o teléfono..."
        value={value}
        onChange={(e) => onChange(e.target.value)}
      />
    </div>
  );
}
