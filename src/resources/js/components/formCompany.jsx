import { useEffect, useState } from "react";
import React from "react";

export default function formCompany({ onBack }) {
    const [sectores, setSectores] = useState([]);
    const [errors, setErrors] = useState([]);
    const [success, setSuccess] = useState("");
    const [form, setForm] = useState({
        nombre: "",
        contrasena: "",
        email: "",
        telefono: "",
        id_sector: "",
        nif: "",
        descripcion: "",
        direccion: "",
        web: "",
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

        // validaciones para el campo "nombre"
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

        // validaciones para el campo "contraseña"
        if (!form.contrasena) {
            newErrors.contrasena = "La contraseña es obligatoria.";
        } else if (form.contrasena.length < 8) {
            newErrors.contrasena =
                "La contraseña debe tener al menos 8 caracteres.";
        } else if (form.contrasena.length > 255) {
            newErrors.contrasena =
                "La contraseña no puede superar los 255 caracteres.";
        } else if (!/[A-Z]/.test(form.contrasena)) {
            newErrors.contrasena =
                "La contraseña debe contener al menos una mayúscula.";
        } else if (!/[a-z]/.test(form.contrasena)) {
            newErrors.contrasena =
                "La contraseña debe contener al menos una minúscula.";
        } else if (!/[0-9]/.test(form.contrasena)) {
            newErrors.contrasena =
                "La contraseña debe contener al menos un número.";
        } else if (!/[^A-Za-z0-9]/.test(form.contrasena)) {
            newErrors.contrasena =
                "La contraseña debe contener al menos un símbolo.";
        }

        // validaciones para el campo "email"
        if (!form.email) {
            newErrors.email = "El email es obligatorio.";
        } else if (form.email !== form.email.toLowerCase()) {
            newErrors.email = "El email debe estar en minúsculas.";
        } else if (form.email.length > 100) {
            newErrors.email = "El email no puede superar los 100 caracteres.";
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
            newErrors.email = "El formato del email no es válido.";
        }

        // validaciones para el campo "telefono"
        if (!form.telefono) {
            newErrors.telefono = "El teléfono es obligatorio.";
        } else if (!/^[6-9][0-9]{8}$/.test(form.telefono)) {
            newErrors.telefono = "El teléfono no es válido.";
        }

        // validaciomnes para el campo "sector"
        if (!form.id_sector) {
            newErrors.id_sector = "El sector es obligatorio.";
        } else if (
            !Number.isInteger(Number(form.id_sector)) ||
            Number(form.id_sector) < 1
        ) {
            newErrors.id_sector =
                "El identificador del sector debe ser un número entero.";
        }

        // validaciones para el campo "NIF"
        const NIFLetras = "TRWAGMYFPDXBNJZSQVHLCKE";
        if (!form.nif) {
            newErrors.nif = "El NIF es obligatorio.";
        } else if (form.nif.length !== 9) {
            newErrors.nif = "El NIF debe tener 9 caracteres.";
        } else if (!/^[0-9]{8}[A-Z]$/.test(form.nif.toUpperCase())) {
            newErrors.nif = "El formato del NIF no es válido.";
        } else {
            const nifUpper = form.nif.toUpperCase();
            const digits = parseInt(nifUpper.substring(0, 8), 10);
            const expectedLetter = NIFLetras[digits % 23];
            if (nifUpper[8] !== expectedLetter) {
                newErrors.nif = "NIF incorrecto.";
            }
        }

        // validaciones para el campo "descripcion"
        if (!form.descripcion) {
            newErrors.descripcion = "La descripción es obligatoria.";
        } else if (form.descripcion.length < 10) {
            newErrors.descripcion =
                "La descripción debe tener al menos 10 caracteres.";
        } else if (form.descripcion.length > 5000) {
            newErrors.descripcion =
                "La descripción debe tener menos de 5000 caracteres.";
        }

        // validaciones para el campo "direccion"
        if (!form.direccion) {
            newErrors.direccion = "La dirección es obligatoria.";
        } else if (form.direccion.length > 255) {
            newErrors.direccion =
                "La dirección no puede superar los 255 caracteres.";
        }

        // validaciones para el campo "web"
        if (!form.web) {
            newErrors.web = "La web es obligatoria.";
        } else if (form.web.length > 100) {
            newErrors.web = "La web no puede superar los 100 caracteres.";
        } else {
            try {
                new URL(form.web);
            } catch {
                newErrors.web = "Formato incorrecto de la web.";
            }
        }
        return newErrors;
    };

    const handleSubmit = () => {
        const validationErrors = validate();
        if (Object.keys(validationErrors).length > 0) {
            setErrors(validationErrors);
            return;
        }

        const formData = new FormData();
        Object.entries(form).forEach(([key, value]) => {
            formData.append(key, value);
        });

        let url = "/api/empresa/register";

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
        let url = "/api/sector";

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => setSectores(data.sectores));
    }, []);

    return (
        <div id="form-company">
            <h1>Crear cuenta</h1>
            <form>
                <div className="form">
                    <label htmlFor="nombre">Nombre de la empresa:</label>
                    <input
                        type="text"
                        id="nombre"
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
                    <label htmlFor="id_sector">Sector:</label>
                    <select
                        id="id_sector"
                        name="sector"
                        onChange={handleChange}
                    >
                        <option value="">-- Selecciona un sector --</option>
                        {sectores.map((sector) => (
                            <option key={sector.id} value={sector.id}>
                                {sector.nombre}
                            </option>
                        ))}
                    </select>
                </div>

                <div className="form">
                    <label htmlFor="nif">NIF:</label>
                    <input type="text" id="nif" onChange={handleChange}></input>
                </div>

                <div className="form">
                    <label htmlFor="descripcion">
                        Descripción de la empresa:
                    </label>
                    <textarea
                        id="descripcion"
                        onChange={handleChange}
                    ></textarea>
                </div>

                <div className="form">
                    <label htmlFor="direccion">Dirección:</label>
                    <input
                        type="text"
                        id="direccion"
                        onChange={handleChange}
                    ></input>
                </div>

                <div className="form">
                    <label htmlFor="web">Web:</label>
                    <input type="text" id="web" onChange={handleChange}></input>
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
