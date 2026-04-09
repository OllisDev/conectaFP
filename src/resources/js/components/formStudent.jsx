import React, { useEffect, useState } from "react";

export default function formStudent({ onBack }) {
    const [centros, setCentros] = useState([]);
    const [grados, setGrados] = useState([]);
    const [errors, setErrors] = useState([]);
    const [success, setSuccess] = useState("");
    const [form, setForm] = useState({
        nombre: "",
        apellidos: "",
        contrasena: "",
        email: "",
        telefono: "",
        id_centro: "",
        id_grado: "",
        curso: "",
        dni: "",
        fecha_nacimiento: "",
        cv: null,
    });

    const handleChange = (e) => {
        const { id, value, files } = e.target;
        setForm((prev) => ({
            ...prev,
            [id]: files ? files[0] : value,
        }));
        setErrors({});
    };

    const validate = () => {
        const newErrors = {};
        if (!form.nombre) newErrors.nombre = "El nombre es obligatorio.";
        if (!form.apellidos)
            newErrors.apellidos = "Los apellidos son obligatorios.";
        if (!form.contrasena || form.contrasena.length < 8)
            newErrors.contrasena =
                "La contraseña debe tener al menos 8 caracteres.";
        if (!form.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email))
            newErrors.email = "El email no es válido.";
        if (!form.telefono || !/^[6-9][0-9]{8}$/.test(form.telefono))
            newErrors.telefono = "El teléfono no es válido.";
        if (!form.id_centro)
            newErrors.id_centro = "El centro educativo es obligatorio.";
        if (!form.id_grado) newErrors.id_grado = "El grado es obligatorio.";
        if (!form.fecha_nacimiento)
            newErrors.fecha_nacimiento =
                "La fecha de nacimiento es obligatoria.";
        if (!form.dni || !validateSpanishId(form.dni))
            newErrors.dni = "DNI incorrecto.";
        if (!form.cv) newErrors.cv = "El CV es obligatorio.";
        return newErrors;
    };

    const validateSpanishId = (id) => {
        const dniRegex = /^[0-9]{8}[A-Z]$/i;
        const nieRegex = /^[XYZ][0-9]{7}[A-Z]$/i;
        const letters = "TRWAGMYFPDXBNJZSQVHLCKE";

        const str = id.toUpperCase();

        if (dniRegex.test(str)) {
            const num = parseInt(str.slice(0, 8), 10);
            return str[8] === letters[num % 23];
        }

        if (nieRegex.test(str)) {
            const niePrefix = { X: "0", Y: "1", Z: "2" };
            const num = parseInt(niePrefix[str[0]] + str.slice(1, 8), 10);
            return str[8] === letters[num % 23];
        }

        return false;
    };

    const handleSubmit = () => {
        console.log("cv:", form.cv);
        const validationErrors = validate();
        if (Object.keys(validationErrors).length > 0) {
            setErrors(validationErrors);
            return;
        }

        const formData = new FormData();
        Object.entries(form).forEach(([key, value]) => {
            formData.append(key, value);
        });

        let url = "/api/alumno/register";

        fetch(url, {
            method: "POST",
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    setSuccess("Cuenta creada correctamente.");
                    setErrors({});
                } else {
                    setErrors(data.message);
                }
            })
            .catch((error) => {
                alert("Error de conexión.");
                console.log(error);
            });
    };

    useEffect(() => {
        let url = "/api/centro";

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => setCentros(data.centros));
    }, []);

    useEffect(() => {
        let url = "/api/grado";

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => setGrados(data.grados));
    }, []);

    return (
        <div id="form-student">
            <h1>Crear cuenta</h1>
            <form>
                <div className="form">
                    <label htmlFor="nombre">Nombre:</label>
                    <input
                        type="text"
                        id="nombre"
                        onChange={handleChange}
                    ></input>
                </div>

                <div className="form">
                    <label htmlFor="apellidos">Apellidos:</label>
                    <input
                        type="text"
                        id="apellidos"
                        onChange={handleChange}
                    ></input>
                </div>

                <div className="form">
                    <label htmlFor="contrasena">Contraseña:</label>
                    <input
                        type="password"
                        id="contrasena"
                        onChange={handleChange}
                    ></input>
                </div>

                <div className="form">
                    <label htmlFor="email">Email:</label>
                    <input
                        type="email"
                        id="email"
                        onChange={handleChange}
                    ></input>
                </div>

                <div className="form">
                    <label htmlFor="telefono">Teléfono:</label>
                    <input
                        type="text"
                        id="telefono"
                        onChange={handleChange}
                    ></input>
                </div>

                <div className="form">
                    <label htmlFor="id_centro">Centro educativo:</label>
                    <select id="id_centro" onChange={handleChange}>
                        <option value="">-- Selecciona un centro --</option>
                        {centros.map((centro) => (
                            <optgroup key={centro.id} label={centro.provincia}>
                                <option key={centro.id} value={centro.id}>
                                    {centro.nombre} - {centro.localidad}
                                </option>
                            </optgroup>
                        ))}
                    </select>
                </div>

                <div className="form">
                    <label htmlFor="id_grado">Grado:</label>
                    <select
                        id="id_grado"
                        name="grado_id"
                        onChange={handleChange}
                    >
                        <option value="">-- Selecciona un grado --</option>
                        {Object.entries(
                            grados.reduce((acc, grado) => {
                                if (!acc[grado.familia_profesional])
                                    acc[grado.familia_profesional] = [];
                                acc[grado.familia_profesional].push(grado);
                                return acc;
                            }, {}),
                        ).map(([familia, items]) => (
                            <optgroup key={familia} label={familia}>
                                {items.map((grado) => (
                                    <option key={grado.id} value={grado.id}>
                                        {grado.nombre} ({grado.tipo})
                                    </option>
                                ))}
                            </optgroup>
                        ))}
                    </select>
                </div>

                <div className="form">
                    <label htmlFor="fecha_nacimiento">
                        Fecha de nacimiento:
                    </label>
                    <input
                        type="date"
                        id="fecha_nacimiento"
                        onChange={handleChange}
                    ></input>
                </div>

                <div className="form">
                    <label htmlFor="curso">Curso:</label>
                    <select id="curso" onChange={handleChange}>
                        <option value="1º">1º</option>
                        <option value="2º">2º</option>
                    </select>
                </div>
                <div className="form">
                    <label htmlFor="dni">DNI:</label>
                    <input type="text" id="dni" onChange={handleChange}></input>
                </div>
                <div className="form">
                    <label htmlFor="cv">CV:</label>
                    <input
                        type="file"
                        id="cv"
                        accept=".pdf"
                        onChange={handleChange}
                    ></input>
                </div>
                <div className="btn-group">
                    <input
                        type="button"
                        id="btnBack"
                        value="Atrás"
                        onClick={onBack}
                    ></input>
                    <input
                        type="button"
                        id="btnRegister"
                        value="Crear cuenta"
                        onClick={handleSubmit}
                    ></input>
                </div>
            </form>
            <p>
                ¿Ya tienes cuenta?<a href="/login">Inicie sesión</a>
            </p>
            {Object.keys(errors).length > 0 && (
                <div className="error-box">
                    <p>{Object.values(errors)[0]}</p>
                </div>
            )}

            {success && (
                <div className="success-box">
                    <p>{success}</p>
                </div>
            )}
        </div>
    );
}
