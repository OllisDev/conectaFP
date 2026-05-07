import React, { useEffect, useState } from "react";

export default function formStudent({ onBack }) {
    const [centros, setCentros] = useState([]); // lista de centros educativos disponibles
    const [grados, setGrados] = useState([]); // lista de grados formativos disponibles
    const [errors, setErrors] = useState([]); // errores de validación
    const [success, setSuccess] = useState(""); // mensaje de éxito
    const [form, setForm] = useState({
        nombre: "",
        apellidos: "",
        contrasena: "",
        email: "",
        telefono: "",
        id_centro: "",
        id_profesor: "",
        id_grado: "",
        curso: "",
        dni: "",
        fecha_nacimiento: "",
        cv: null,
    });
    const [profesores, setProfesores] = useState([]); // lista de profesores del centro educativo seleccionado

    /**
     * maneja cambios en todos los campos del formulario
     * @param {Event} e
     */
    const handleChange = (e) => {
        const { id, value, files } = e.target;
        setForm((prev) => ({
            ...prev,
            // si es un input de tipo archivo, toma el primer archivo, sino el valor
            [id]: files ? files[0] : value,
        }));
        setErrors({});
    };

    /**
     * valida todos los campos requeridos para un estudiante de FP
     * @returns {Object}
     */
    const validate = () => {
        const newErrors = {};

        // -- VALIDACIONES --

        if (!form.nombre) {
            newErrors.nombre = "El nombre es obligatorio.";
        } else if (form.nombre.length < 2) {
            newErrors.nombre = "El nombre debe tener al menos 2 caracteres.";
        } else if (form.nombre.length > 50) {
            newErrors.nombre = "El nombre no puede superar los 50 caracteres.";
        } else if (!/^[\p{L}\s\-']+$/u.test(form.nombre)) {
            newErrors.nombre =
                "El nombre solo puede contener letras, espacios, guiones y apóstrofes.";
        }

        if (!form.apellidos) {
            newErrors.apellidos = "Los apellidos son obligatorios.";
        } else if (form.apellidos.length < 2) {
            newErrors.apellidos =
                "Los apellidos deben tener al menos 2 caracteres.";
        } else if (form.apellidos.length > 100) {
            newErrors.apellidos =
                "Los apellidos no pueden superar los 100 caracteres.";
        } else if (!/^[\p{L}\s\-']+$/u.test(form.apellidos)) {
            newErrors.apellidos =
                "Los apellidos solo pueden contener letras, espacios, guiones y apóstrofes.";
        }

        if (!form.contrasena) {
            newErrors.contrasena = "La contraseña es obligatoria.";
        } else if (form.contrasena.length < 8) {
            newErrors.contrasena =
                "La contraseña debe tener al menos 8 caracteres.";
        } else if (form.contrasena.length > 255) {
            newErrors.contrasena =
                "La contraseña no puede superar los 255 caracteres.";
        } else if (
            !/[A-Z]/.test(form.contrasena) ||
            !/[a-z]/.test(form.contrasena)
        ) {
            newErrors.contrasena =
                "La contraseña debe contener mayúsculas y minúsculas.";
        } else if (!/[0-9]/.test(form.contrasena)) {
            newErrors.contrasena =
                "La contraseña debe contener al menos un número.";
        } else if (!/[^A-Za-z0-9]/.test(form.contrasena)) {
            newErrors.contrasena =
                "La contraseña debe contener al menos un símbolo.";
        }

        if (!form.email) {
            newErrors.email = "El email es obligatorio.";
        } else if (form.email !== form.email.toLowerCase()) {
            newErrors.email = "El email debe estar en minúsculas.";
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
            newErrors.email = "El formato del email no es válido.";
        } else if (form.email.length > 100) {
            newErrors.email = "El email no puede superar los 100 caracteres.";
        }

        if (!form.telefono) {
            newErrors.telefono = "El teléfono es obligatorio.";
        } else if (!/^[6-9][0-9]{8}$/.test(form.telefono)) {
            newErrors.telefono = "El teléfono no es válido.";
        }

        if (!form.id_centro) {
            newErrors.id_centro = "El centro educativo es obligatorio.";
        }

        if (!form.id_profesor) {
            newErrors.id_profesor = "El profesor es obligatorio.";
        }

        if (!form.id_grado) {
            newErrors.id_grado = "El grado es obligatorio.";
        }

        if (!form.fecha_nacimiento) {
            newErrors.fecha_nacimiento =
                "La fecha de nacimiento es obligatoria.";
        } else {
            const fecha = new Date(form.fecha_nacimiento);
            const minDate = new Date("1900-01-01");
            const maxDate = new Date();
            maxDate.setFullYear(maxDate.getFullYear() - 16);

            if (isNaN(fecha.getTime())) {
                newErrors.fecha_nacimiento =
                    "El formato de la fecha no es válido.";
            } else if (fecha <= minDate) {
                newErrors.fecha_nacimiento =
                    "La fecha de nacimiento no puede ser antes de 1900.";
            } else if (fecha > maxDate) {
                newErrors.fecha_nacimiento =
                    "La edad debe ser mayor a 16 años.";
            }
        }

        if (!form.curso) {
            newErrors.curso = "El curso es obligatorio.";
        } else if (!["1º", "2º"].includes(form.curso)) {
            newErrors.curso = 'El curso debe ser "1º" o "2º".';
        }

        if (!form.dni) {
            newErrors.dni = "El DNI es obligatorio.";
        } else if (form.dni.length !== 9) {
            newErrors.dni = "El DNI debe tener 9 caracteres.";
        } else if (!/^[0-9]{8}[A-Za-z]$/.test(form.dni)) {
            newErrors.dni = "El formato del DNI no es válido.";
        } else if (!validateSpanishId(form.dni)) {
            newErrors.dni = "DNI incorrecto.";
        }

        if (!form.cv) {
            newErrors.cv = "El CV es obligatorio.";
        } else if (form.cv.type !== "application/pdf") {
            newErrors.cv = "El CV debe ser un archivo PDF.";
        } else if (form.cv.size > 2 * 1024 * 1024) {
            newErrors.cv = "El CV no puede superar los 2MB.";
        }

        return newErrors;
    };

    /**
     * validar DNI español
     * @param {*} id
     * @returns {boolean}
     */
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

    /**
     * Procesa el envío del formulario de registro de estudiante
     */
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

    /**
     * cargar datos necesarios para el formulario al montar el componente
     */
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

    /**
     * cargar profesores cuando se selecciona un centro educativo
     */
    useEffect(() => {
        if (form.id_centro) {
            fetch(`/api/profesor/centro/${form.id_centro}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => setProfesores(data.profesores || []));
        } else {
            setProfesores([]); // limpiar profesores si no hay centro educativo
        }
    }, [form.id_centro]); // se ejecuta cuando cambia el centro educativo seleccionado

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
                        {Object.entries(
                            centros.reduce((acc, centro) => {
                                if (!acc[centro.provincia])
                                    acc[centro.provincia] = [];
                                acc[centro.provincia].push(centro);
                                return acc;
                            }, {}),
                        ).map(([provincia, items]) => (
                            <optgroup key={provincia} label={provincia}>
                                {items.map((centro) => (
                                    <option key={centro.id} value={centro.id}>
                                        {centro.nombre} - {centro.localidad}
                                    </option>
                                ))}
                            </optgroup>
                        ))}
                    </select>
                </div>

                <div className="form">
                    <label htmlFor="id_profesor">Profesor:</label>
                    <select id="id_profesor" onChange={handleChange}>
                        <option value="">-- Selecciona un profesor --</option>
                        {profesores.map((profesor) => (
                            <option key={profesor.id} value={profesor.id}>
                                {profesor.usuario?.nombre}{" "}
                                {profesor.usuario?.apellidos}
                            </option>
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
                        max={new Date().toISOString().split("T")[0]}
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
