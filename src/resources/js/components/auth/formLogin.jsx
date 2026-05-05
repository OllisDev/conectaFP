import { useEffect, useState } from "react";

export default function formLogin() {
    const [errors, setErrors] = useState([]);
    const [form, setForm] = useState({
        email: "",
        contrasena: "",
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

        // validaciones para el campo "email"
        if (!form.email) {
            newErrors.email = "El email es obligatorio.";
        } else if (form.email !== form.email.toLowerCase()) {
            newErrors.email = "El email debe estar en minúsculas.";
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
            newErrors.email = "El formato del email no es válido.";
        } else if (form.email.length > 100) {
            newErrors.email = "El email no puede superar los 100 caracteres.";
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

        let url = "/api/usuario/login";

        fetch(url, {
            method: "POST",
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    localStorage.setItem("api_token", data.api_token);
                    localStorage.setItem(
                        "user",
                        JSON.stringify({
                            nombre: data.nombre,
                            rol: data.rol,
                            id: data.id_rol,
                        }),
                    );
                    setErrors({});
                    window.location.href = "/feed";
                } else {
                    if (typeof data.message === "string") {
                        setErrors({ general: data.message });
                    } else if (
                        typeof data.message === "object" &&
                        data.message !== null
                    ) {
                        setErrors(data.message);
                    } else {
                        // Fallback
                        setErrors({ general: "Error al iniciar sesión." });
                    }
                }
            })
            .catch((error) => {
                alert("Error de conexión.");
                console.log(error);
            });
    };

    return (
        <div id="form-login">
            <h1>Iniciar sesión</h1>
            <form>
                <div className="form">
                    <label htmlFor="email">Email:</label>
                    <input
                        type="email"
                        id="email"
                        onChange={handleChange}
                    ></input>
                </div>

                <div className="form">
                    <label htmlFor="password">Contraseña:</label>
                    <input
                        type="password"
                        id="contrasena"
                        onChange={handleChange}
                    ></input>
                </div>
                <input
                    type="button"
                    id="btnLogin"
                    value="Iniciar sesión"
                    onClick={handleSubmit}
                ></input>
            </form>
            <p>
                ¿No tienes cuenta? <a href="/register">Cree una cuenta</a>
            </p>
            {Object.keys(errors).length > 0 && (
                <div className="error-box">
                    {Object.entries(errors).map(([key, value]) => (
                        <p key={key}>
                            {Array.isArray(value) ? value[0] : value}
                        </p>
                    ))}
                </div>
            )}
        </div>
    );
}
