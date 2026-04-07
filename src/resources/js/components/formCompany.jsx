import React from "react";

export default function formCompany({ onBack }) {
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
                            <label htmlFor="description">Descripción de la empresa:</label>
                            <textarea id="description"></textarea>
                        </div>

                        <div className="form">
                            <label htmlFor="nif">NIF:</label>
                            <input type="text" id="nif"></input>
                        </div>

                        <div className="form">
                            <label htmlFor="sector">Sector:</label>
                            <select id="sector" name="sector">
                                <option value="Informatica y Telecomunicaciones">Informática y Telecomunicaciones</option>
                                <option value="Administracion y Gestion">Administración y Gestión</option>
                                <option value="Comercio y Marketing">Comercio y Marketing</option>
                                <option value="Sanidad y Salud">Sanidad y Salud</option>
                                <option value="Hosteleria y Turismo">Hostelería y Turismo</option>
                                <option value="Electricidad y Electronica">Electricidad y Electrónica</option>
                                <option value="Industria y Fabricacion">Industria y Fabricación</option>
                                <option value="Mecanica y Automocion">Mecánica y Automoción</option>
                                <option value="Transporte y Logistica">Transporte y Logística</option>
                                <option value="Arte y Diseno Grafico">Arte y Diseño Gráfico</option>
                                <option value="Edificacion y Obra Civil">Edificación y Obra Civil</option>
                                <option value="Energia y Medio Ambiente">Energía y Medio Ambiente</option>
                                <option value="Servicios Socioculturales">Servicios Socioculturales</option>
                                <option value="Hosteleria y Turismo">Hostelería y Turismo</option>
                                <option value="Actividades Agrarias y Alimentarias">Actividades Agrarias y Alimentarias</option>
                                <option value="Otros">Otros sectores</option>
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
                            <input type="button" id="btnBack" value="Atrás" onClick={ onBack }></input>
                            <input type="button" id="btnRegister" value="Crear cuenta"></input>
                        </div>
                </form>
                <p>¿Ya tienes cuenta?<a href="/login">Inicie sesión</a></p>
            </div>
                
    )
}