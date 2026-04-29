import React, { useState } from "react";

export default function modalCreateOffer({ onClose, onOfferCreated }) {
    const [errors, setErrors] = useState([]);
    const [form, setForm] = useState({
        titulo: "",
        descripcion: "",
        requisitos: "",
        modalidad: "",
    });

    const handleChange = (e) => {
        setForm((prev) => ({
            ...prev,
            [e.target.id]: e.target.value,
        }));
        setErrors({});
    };

    const validate = () => {
        const newErrors = {};

        if (!form.titulo.trim()) {
            newErrors.titulo = "El título es obligatorio";
        } else if (form.titulo.length < 2) {
            newErrors.titulo = "El título debe tener al menos 2 caracteres.";
        } else if (form.titulo.length > 255) {
            newErrors.titulo = "El título no debe tener más de 255 caracteres.";
        }

        if (!form.descripcion.trim()) {
            newErrors.descripcion = "La descripción es obligatoria.";
        } else if (form.descripcion.length < 10) {
            newErrors.descripcion =
                "La descripción debe tener al menos 10 caracteres.";
        } else if (form.descripcion.length > 5000) {
            newErrors.descripcion =
                "La descripción debe tener menos de 5000 caracteres.";
        }

        if (!form.requisitos.trim()) {
            newErrors.requisitos = "Los requisitos son obligatorios.";
        } else if (form.requisitos.length < 2) {
            newErrors.requisitos =
                "Los requisitos deben tener al menos 2 caracteres.";
        } else if (form.requisitos.length > 5000) {
            newErrors.requisitos =
                "Los requisitos deben tener menos de 5000 caracteres.";
        }

        if (!form.modalidad) {
            newErrors.modalidad = "La modalidad es obligatoria.";
        } else if (
            !["Presencial", "Remoto", "Híbrido"].includes(form.modalidad)
        ) {
            newErrors.modalidad =
                'La modalidad debe ser "Presencial", "Remoto" o "Híbrido".';
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

        let requisitosLimpios = form.requisitos
            .split(",")
            .map((req) => req.trim())
            .filter((req) => req.length > 0)
            .join(", ");

        const formData = new FormData();
        Object.entries(form).forEach(([key, value]) => {
            formData.append(key, value);
        });

        const token = localStorage.getItem("api_token");
        let url = "/api/oferta/crear";

        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
            body: JSON.stringify({ ...form, requisitos: requisitosLimpios }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    alert("Oferta creada correctamente.");
                    onClose();
                    onOfferCreated();
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
            <div className="modal-create-container">
                <h2>Crear oferta</h2>
                <div className="form-container">
                    <form onSubmit={handleSubmit}>
                        <div className="form">
                            <label htmlFor="titulo">Titulo de la oferta:</label>
                            <input
                                type="text"
                                id="titulo"
                                onChange={handleChange}
                            ></input>
                        </div>
                        <div className="form">
                            <label htmlFor="descripcion">
                                Descripción de la oferta:
                            </label>
                            <textarea
                                id="descripcion"
                                onChange={handleChange}
                            ></textarea>
                        </div>
                        <div className="form">
                            <label htmlFor="requisitos">Requisitos:</label>
                            <textarea
                                id="requisitos"
                                onChange={handleChange}
                            ></textarea>
                        </div>
                        <div className="form">
                            <label htmlFor="modalidad">Modalidad:</label>
                            <select id="modalidad" onChange={handleChange}>
                                <option value="">
                                    -- Selecciona una modalidad --
                                </option>
                                <option value="Presencial">Presencial</option>
                                <option value="Remoto">Remoto</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                        {Object.keys(errors).length > 0 && (
                            <div className="error-box">
                                <p>{Object.values(errors)[0]}</p>
                            </div>
                        )}

                        <div className="form-actions">
                            <button type="submit" id="btnCreate">
                                Crear oferta
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
