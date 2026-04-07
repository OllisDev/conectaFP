import React, { useState } from "react";

export default function formStudent({ onBack }) {
    const [formData, setFormData] = useState({
        nonbre: '',
        apellidos: '',
        contrasena: ''
    });

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
                    <input type="text" id="school"></input>
                </div>
                <div className="form">
                    <label htmlFor="degree">Grado:</label>
                    <select name="grado" id="degree">
                        <optgroup label="Informática y Comunicaciones">
                            <option value="ASIR">Administración de Sistemas Informáticos en Red</option>
                            <option value="DAM">Desarrollo de Aplicaciones Multiplataforma</option>
                            <option value="DAW">Desarrollo de Aplicaciones Web</option>
                            <option value="Telecomunicaciones">Sistemas de Telecomunicaciones e Informáticos</option>
                        </optgroup>

                        <optgroup label="Sanidad">
                            <option value="laboratorio">Laboratorio Clínico y Biomédico</option>
                            <option value="anatomia">Anatomía Patológica y Citodiagnóstico</option>
                            <option value="imagen-diag">Imagen para el Diagnóstico y Medicina Nuclear</option>
                            <option value="higiene">Higiene Bucodental</option>
                            <option value="dietetica">Dietética</option>
                            <option value="protesis">Prótesis Dentales</option>
                            <option value="doc-sanitaria">Documentación y Administración Sanitarias</option>
                            <option value="radioterapia">Radioterapia y Dosimetría</option>
                        </optgroup>
                        <optgroup label="Administración y Comercio">
                            <option value="ad-finanzas">Administración y Finanzas</option>
                            <option value="asistencia-dir">Asistencia a la Dirección</option>
                            <option value="comercio-int">Comercio Internacional</option>
                            <option value="logistica">Transporte y Logística</option>
                            <option value="marketing">Marketing y Publicidad</option>
                            <option value="gestion-ventas">Gestión de Ventas y Espacios Comerciales</option>
                        </optgroup>

                        <optgroup label="Servicios a la Comunidad">
                            <option value="infantil">Educación Infantil</option>
                            <option value="integracion">Integración Social</option>
                            <option value="igualdad">Promoción de Igualdad de Género</option>
                            <option value="mediacion">Mediación Comunicativa</option>
                        </optgroup>

                        <optgroup label="Industria y Edificación">
                            <option value="robotica">Automatización y Robótica Industrial</option>
                            <option value="mecatronica">Mecatrónica Industrial</option>
                            <option value="mantenimiento-elec">Sistemas Electrotécnicos y Automatizados</option>
                            <option value="proyectos-ed">Proyectos de Edificación</option>
                            <option value="obra-civil">Proyectos de Obra Civil</option>
                            <option value="renovables">Energías Renovables</option>
                        </optgroup>

                        <optgroup label="Imagen, Sonido y Hostelería">
                            <option value="3d-juegos">Animaciones 3D, Juegos y Entornos Interactivos</option>
                            <option value="realizacion">Realización de Proyectos de Audiovisuales y Espectáculos</option>
                            <option value="cocina">Dirección de Cocina</option>
                            <option value="restauracion">Dirección de Servicios de Restauración</option>
                            <option value="turismo">Guía, Información y Asistencias Turísticas</option>
                        </optgroup>

                        <optgroup label="Actividades Físicas y Deportivas">
                            <option value="tseas">Enseñanza y Animación Sociodeportiva (TSEAS)</option>
                            <option value="fitness">Acondicionamiento Físico</option>
                        </optgroup>

                        <optgroup label="Imagen Personal">
                            <option value="estetica">Estética Integral y Bienestar</option>
                            <option value="estilismo">Estilismo y Dirección de Peluquería</option>
                            <option value="caracterizacion">Caracterización y Maquillaje Profesional</option>
                        </optgroup>
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
                    <input type="button" id="btnBack" value="Atrás" onClick= {onBack}></input>
                    <input type="button" id="btnRegister" value="Crear cuenta"></input>
                </div>
            </form>
            <p>¿Ya tienes cuenta?<a href="/login">Inicie sesión</a></p>
        </div>
    )
}