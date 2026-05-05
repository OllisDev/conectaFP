import React, { useState } from "react";

/**
 *
 * @param {Object} solicitud - datos completos de la solicitud
 * @param {Function} onClose - función para cerrar el modal
 * @param {boolean} onRequestUpdated - conocer si la solicitud se ha actualizado para actualizar la lista de solicitudes
 */
export default function modalEditRequest({
    solicitud,
    onClose,
    onRequestUpdated,
}) {
    const [errors, setErrors] = useState([]); // errores de validación
    const [form, setForm] = useState({
        estado: "",
    });

    /**
     * maneja los cambios en los campos del formulario
     * @param {Event} e
     */
    const handleChange = (e) => {
        setForm((prev) => ({
            ...prev,
            [e.target.id]: e.target.value,
        }));
        setErrors({});
    };

    /**
     * valida todos los campos del formulario de la edición de la solicitud
     * @returns {Object}
     */
    const validate = () => {
        const newErrors = {};

        // -- VALIDACIONES --
        if (!form.estado) {
            newErrors.estado = "El estado es obligatorio.";
        } else if (
            !["Pendiente", "Revision", "Aceptada", "Rechazada"].includes(
                form.estado,
            )
        ) {
            newErrors.estado =
                'El estado debe ser "Pendiente", "Revisión", "Aceptada" o "Rechazada".';
        }

        return newErrors;
    };

    /**
     * Procesa el envío del formulario de la edición de la solicitud
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

        const formData = new FormData();
        Object.entries(form).forEach(([key, value]) => {
            formData.append(key, value);
        });

        const token = localStorage.getItem("api_token");
        let url = `/api/solicitud/${solicitud.id}/actualizar`;

        fetch(url, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
            body: JSON.stringify(form),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    alert("La solicitud ha sido actualizada correctamente.");
                    onClose();
                    onRequestUpdated();
                } else {
                    setErrors(data.message);
                }
            })
            .catch((error) => {
                alert("Error de conexión.");
                console.log(error);
            });
    };

    return (
        <div className="modal-overlay">
            <div className="modal-update-container">
                <h2>Editar solicitud</h2>
                <div className="form-container">
                    <form onSubmit={handleSubmit}>
                        <div className="form">
                            <label htmlFor="estado">Estado:</label>
                            <select id="estado" onChange={handleChange}>
                                <option value="">
                                    -- Selecciona un estado --
                                </option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Revision">Revisión</option>
                                <option value="Aceptada">Aceptada</option>
                                <option value="Rechazada">Rechazada</option>
                            </select>
                        </div>
                        {Object.keys(errors).length > 0 && (
                            <div className="error-box">
                                <p>{Object.values(errors)[0]}</p>
                            </div>
                        )}

                        <div className="form-actions">
                            <button type="submit" id="btnUpdate">
                                Actualizar solicitud
                            </button>
                            <button
                                type="button"
                                id="btnClose"
                                onClick={onClose}
                            >
                                Cerrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}
