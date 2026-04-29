import React from "react";

export default function modalCreateOffer({ onClose }) {
    return (
        <div className="modal-overlay">
            <div className="modal-create-container">
                <h2>Crear oferta</h2>
                <div className="form-container">
                    <form>
                        <div className="form">
                            <label htmlFor="titulo">Titulo de la oferta:</label>
                            <input type="text" id="titulo"></input>
                        </div>
                        <div className="form">
                            <label htmlFor="descripcion">
                                Descripción de la oferta:
                            </label>
                            <textarea id="descripcion"></textarea>
                        </div>
                        <div className="form">
                            <label htmlFor="requisitos">Requisitos:</label>
                            <textarea></textarea>
                        </div>
                        <div className="form">
                            <label htmlFor="modalidad">Modalidad:</label>
                            <select id="modalidad">
                                <option value="">
                                    -- Selecciona una modalidad --
                                </option>
                                <option value="Presencial">Presencial</option>
                                <option value="Remoto">Remoto</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                        <div className="form">
                            <label htmlFor="fecha_publicacion">
                                Fecha de publicación:
                            </label>
                            <input type="date" id="fecha_publicacion"></input>
                        </div>
                        <div className="form-actions">
                            <button type="button" id="btnCreate">
                                Crear oferta
                            </button>
                            <button
                                type="button"
                                id="btnClose"
                                onClick={onClose}
                            >
                                Cerrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}
