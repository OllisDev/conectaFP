import React, { useEffect, useState } from "react";

/**
 * modal para editar la tutoría seleccionada
 * @param {Function} onClose - función para cerrar el modal
 * @param {Object} tutoria - lista de tutorías disponibles
 * @param {Function} onUpdate - función para actualizar la lista de tutorías
 */
export default function ModalEditTutorial({ onClose, tutoria, onUpdate }) {
    const [updateTutorial, setupdateTutorial] = useState([]); // estado para saber si la tutoría se ha actualizado
    const [errors, setErrors] = useState({}); // errores de validación
    const [form, setForm] = useState({
        fecha_inicio: tutoria.fecha_inicio || "",
        fecha_fin: tutoria.fecha_fin || "",
        estado: tutoria.estado || "",
    });

    /**
     * maneja los cambios en los campos del formulario
     * @param {Event} e
     */
    const handleChange = (e) => {
        const { name, value } = e.target;
        setForm((prev) => ({
            ...prev,
            [name]: value,
        }));
        setErrors({});
    };

    /**
     * valida todos los campos del formulario de la edición del tutorial
     * @returns {Object}
     */
    const validate = () => {
        const newErrors = {};

        // -- VALIDACIONES --

        if (!form.fecha_inicio) {
            newErrors.fecha_inicio = "La fecha de inicio es obligatoria.";
        }

        if (form.fecha_fin) {
            if (!form.fecha_inicio) {
                newErrors.fecha_fin = "Primero selecciona la fecha de inicio.";
            } else if (form.fecha_fin < form.fecha_inicio) {
                newErrors.fecha_fin =
                    "La fecha de fin debe ser posterior a la de inicio.";
            }
        }

        if (!form.estado) {
            newErrors.estado = "El estado es obligatorio.";
        } else if (
            !["Activa", "Finalizada", "Cancelada"].includes(form.estado)
        ) {
            newErrors.estado = "El estado no es válido.";
        }

        return newErrors;
    };

    /**
     * función para guardar el mismo formato de fecha en la base de datos
     * @param {Date} dt
     * @returns
     */
    function formatDateTimeLocalToSQL(dt) {
        if (!dt) return "";

        return dt.replace("T", " ") + ":00";
    }

    /**
     * Procesa el envío del formulario de la edición de la tutoría
     * @param {Event} e
     * @returns
     */
    const handleSubmit = (e) => {
        e.preventDefault();
        const validationErrors = validate();
        if (Object.keys(validationErrors).length > 0) {
            setErrors(validationErrors);
            return;
        }

        const formattedForm = {
            ...form,
            fecha_inicio: formatDateTimeLocalToSQL(form.fecha_inicio),
            fecha_fin: form.fecha_fin
                ? formatDateTimeLocalToSQL(form.fecha_fin)
                : "",
        };

        let url = `/api/tutoria/${tutoria.id}/actualizar`;

        fetch(url, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(formattedForm),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    alert("La tutoría se ha actualizado correctamente.");
                    setErrors({});
                    onUpdate({ ...tutoria, ...formattedForm });
                } else {
                    setErrors(data.message || "Error al actualizar.");
                }
            })
            .catch((error) => {
                alert("Error de conexión.");
                console.log(error);
            });
    };

    return (
        <div className="modal-container">
            <div className="modal-box">
                <h2>Editar tutoría</h2>
                <form onSubmit={handleSubmit}>
                    <div className="modal-field">
                        <label htmlFor="fecha_inicio">Comienzo:</label>
                        <input
                            type="datetime-local"
                            id="fecha_inicio"
                            name="fecha_inicio"
                            value={form.fecha_inicio}
                            onChange={handleChange}
                        />
                    </div>
                    <div className="modal-field">
                        <label htmlFor="fecha_fin">Fin:</label>
                        <input
                            type="datetime-local"
                            id="fecha_fin"
                            name="fecha_fin"
                            value={form.fecha_fin}
                            onChange={handleChange}
                        />
                    </div>
                    <div className="modal-field">
                        <label htmlFor="estado">Estado:</label>
                        <select
                            id="estado"
                            name="estado"
                            value={form.estado}
                            onChange={handleChange}
                        >
                            <option value="">-- Selecciona un estado --</option>
                            <option value="Activa">Activa</option>
                            <option value="Finalizada">Finalizada</option>
                            <option value="Cancelada">Cancelada</option>
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
