import React, { useEffect, useState } from "react";

export default function formStudent({ onBack }) {
    const [centros, setCentros] = useState([]);
    const [grados, setGrados] = useState([]);

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
                    <input type="text" id="phone"></input>
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
                    <label htmlFor="degree">Grado:</label>
                    <select id="degree" name="grado_id">
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
                    <label htmlFor="curse">Curso:</label>
                    <select id="curse">
                        <option value="1">1º</option>
                        <option value="2">2º</option>
                    </select>
                </div>
                <div className="form">
                    <label htmlFor="dni">DNI:</label>
                    <input type="text" id="dni"></input>
                </div>
                <div className="form">
                    <label htmlFor="cv">CV:</label>
                    <input type="file" id="cv"></input>
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
