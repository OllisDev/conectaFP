import { useEffect, useState } from "react";
import React from "react";

export default function formTeacher({ onBack }) {
    const [centros, setCentros] = useState([]);

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

    return (
        <div id="form-teacher">
            <h1>Crear cuenta</h1>
            <form>
                <div className="form">
                    <label htmlFor="name">Nombre:</label>
                    <input type="text" id="name"></input>
                </div>

                <div className="form">
                    <label htmlFor="last-name">Apellidos:</label>
                    <input type="text" id="last-name"></input>
                </div>

                <div className="form">
                    <label htmlFor="password">Contraseña:</label>
                    <input type="password" id="password"></input>
                </div>

                <div className="form">
                    <label htmlFor="email">Email:</label>
                    <input type="email" id="email"></input>
                </div>

                <div className="form">
                    <label htmlFor="phone">Teléfono:</label>
                    <input type="phone"></input>
                </div>

                <div className="form">
                    <label htmlFor="school">Centro educativo:</label>
                    <select id="school" name="centro_educativo_id">
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
                    <label htmlFor="department">Departamento:</label>
                    <input type="text" id="department"></input>
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
                    ></input>
                </div>
            </form>
            <p>
                ¿Ya tienes cuenta?<a href="/login">Inicie sesión</a>
            </p>
        </div>
    );
}
