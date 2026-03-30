import React from "react";

export default function formStudent({ onBack }) {
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
                    <label htmlFor="school">Centro educativo:</label>
                    <input type="text" id="school"></input>
                </div>
                <div className="form">
                    <label htmlFor="degree">Grado:</label>
                    <input type="text" id="degree"></input>
                </div>
                <div className="form">
                    <label htmlFor="cv">CV:</label>
                    <input type="file" id="cv"></input>
                </div>
                <div className="form">
                    <label htmlFor="availability">Disponibilidad:</label>
                    <input type="checkbox" id="availability"></input>
                </div>
                <div className="btn-group">
                    <input type="button" id="btnBack" value="Atrás" onClick= {onBack}></input>
                    <input type="button" id="btnRegister" value="Crear cuenta"></input>
                </div>
            </form>
            <p>¿Ya tienes cuenta?<a href="/login">Inicie sesión</a></p>
        </div>
    )
}