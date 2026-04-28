import React from "react";

export default function modalRequest() {
    return (
        <div className="modal-container">
            <div className="form-container">
                <form>
                    <div className="form">
                        <label htmlFor="id_alumno">Alumnos a asignar:</label>
                        <select id="id_alumno" multiple></select>
                    </div>
                </form>
            </div>
        </div>
    );
}
