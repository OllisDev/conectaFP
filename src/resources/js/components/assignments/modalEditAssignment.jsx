import React, { useState } from "react";

export default function modalEditAssignment({ onClose, asignacion, onUpdate }) {
    const [updateAssignment, setUpdateAssignment] = useState([]);
    const [errors, setErrors] = useState({});
    const [form, setForm] = useState({
        estado: asignacion.estado || "",
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setForm((prev) => ({
            ...prev,
            [name]: value,
        }));
        setErrors({});
    };

    const validate = () => {
        const newErrors = {};

        if (!form.estado) {
            newErrors.estado = "El estado es obligatorio.";
        } else if (!["Activo", "Finalizado"].includes(form.estado)) {
            newErrors.estado = "El estado no es válido.";
        }
        return newErrors;
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        const validationErrors = validate();
        if (Object.keys(validationErrors).length > 0) {
            setErrors(validationErrors);
            return;
        }

        let token = localStorage.getItem("api_token");

        let url = `/api/asignacion/${asignacion.id}/actualizar`;

        fetch(url, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + token,
            },
            body: JSON.stringify(form),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    alert("La asignación se ha actualizado correctamente.");
                    setErrors({});
                    onUpdate({ ...asignacion, ...form });
                } else {
                    setErrors(data.message || "Error al actualizar.");
                }
            })
            .catch((error) => {
                alert("Error de conexión");
                console.log(error);
            });
    };

    return (
        <div className="modal-container">
            <div className="modal-box">
                <h2>Editar asignación</h2>
                <form onSubmit={handleSubmit}>
                    <div className="modal-field">
                        <label htmlFor="estado"></label>
                        <select
                            id="estado"
                            name="estado"
                            value={form.estado}
                            onChange={handleChange}
                        >
                            <option value="">-- Selecciona un estado --</option>
                            <option value="Activo">Activo</option>
                            <option value="Finalizado">Finalizado</option>
                        </select>
                    </div>
                    <div className="modal-actions">
                        <button type="submit" id="btn-save">
                            Guardar
                        </button>
                        <button type="button" id="btn-close" onClick={onClose}>
                            Cerrar
                        </button>
                    </div>
                    {Object.keys(errors).length > 0 && (
                        <div className="error-box">
                            <p>{Object.values(errors)[0]}</p>
                        </div>
                    )}
                </form>
            </div>
        </div>
    );
}
