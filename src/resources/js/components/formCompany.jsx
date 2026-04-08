import { useEffect, useState } from "react";
import React from "react";

export default function formCompany({ onBack }) {
    const [sectores, setSectores] = useState([]);

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
                    <label htmlFor="name-company">Nombre de la empresa:</label>
                    <input type="text" id="name-company"></input>
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
                    <label htmlFor="description">
                        Descripción de la empresa:
                    </label>
                    <textarea id="description"></textarea>
                </div>

                <div className="form">
                    <label htmlFor="nif">NIF:</label>
                    <input type="text" id="nif"></input>
                </div>

                <div className="form">
                    <label htmlFor="sector">Sector:</label>
                    <select id="sector" name="sector">
                        {sectores.map((sector) => (
                            <option key={sector.id} value={sector.id}>
                                {sector.nombre}
                            </option>
                        ))}
                    </select>
                </div>

                <div className="form">
                    <label htmlFor="address">Dirección:</label>
                    <input type="text" id="address"></input>
                </div>

                <div className="form">
                    <label htmlFor="web">Web:</label>
                    <input type="text" id="web"></input>
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
