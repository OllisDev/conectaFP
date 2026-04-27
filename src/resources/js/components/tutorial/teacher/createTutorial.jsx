import React, { useEffect, useState } from "react";
import Select from "react-select";

export default function createTutorial() {
    return (
        <div className="create-container">
            <div className="form-container">
                <form>
                    <div className="form">
                        <label html_for="id_alumno">Alumno asignado:</label>
                        <Select
                            id="id_alumno"
                            placeholder="Busca un alumno asignado..."
                            isClearable
                        ></Select>
                    </div>
                    <div className="form">
                        <label htmlFor="id_empresa">
                            Empresa asignada del alumno:
                        </label>
                        <Select
                            id="id_alumno"
                            placeholder="Busca una empresa asignada..."
                            isClearable
                        ></Select>
                    </div>
                    <div className="form">
                        <label htmlFor="fecha_inicio">
                            Fecha de inicio de la tutoría:
                        </label>
                        <input type="datetime-local" id="fecha_inicio"></input>
                    </div>
                    <div className="form">
                        <label htmlFor="fecha_fin">
                            Fecha de finalización de la tutoría:
                        </label>
                        <input type="datetime-local" id="fecha_fin"></input>
                    </div>
                    <div className="form">
                        <label htmlFor="estado">Estado de la tutoría:</label>
                        <select
                            id="estado"
                            className="select-status"
                            name="estado"
                        >
                            <option value="">-- Selecciona un estado --</option>
                            <option value="Activa">Activa</option>
                            <option value="Finalizada">Finalizada</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>
                    <button type="submit" id="btn-tutorial">
                        Crear tutoría
                    </button>
                </form>
            </div>
        </div>
    );
}
