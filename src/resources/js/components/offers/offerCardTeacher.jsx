import React, { useState, useEffect } from "react";

export default function OfferCardTeacher({ oferta, isExpanded, onExpand }) {
    const estadoClass =
        oferta.estado === "Abierta"
            ? "abierta"
            : oferta.estado === "Cerrada"
              ? "cerrada"
              : "pausada";

    const [alumnosAsignados, setAlumnosAsignados] = useState([]);

    useEffect(() => {
        if (isExpanded) {
            const token = localStorage.getItem("api_token");

            let url = "/api/alumnos/profesor";

            fetch(url, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    Authorization: `Bearer ${token}`,
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        setAlumnosAsignados(data.alumnos);
                    } else {
                        setAlumnosAsignados([]);
                    }
                });
        }
    }, [isExpanded]);

    return (
        <div className="offer-card">
            <div className="offer-card-header">
                <div>
                    <h2 className="offer-card-title">{oferta.titulo}</h2>
                    <span className="offer-card-empresa">
                        {oferta.empresa?.usuario?.nombre}
                    </span>
                </div>
                <span className={`offer-card-estado ${estadoClass}`}>
                    {oferta.estado}
                </span>
            </div>

            <p className="offer-card-descripcion">{oferta.descripcion}</p>

            <div className="offer-card-tags">
                {oferta.requisitos.split(",").map((req, i) => (
                    <span key={i} className="offer-card-tag">
                        {req.trim()}
                    </span>
                ))}
            </div>

            <div className="offer-card-footer">
                <span className="offer-card-modalidad">{oferta.modalidad}</span>
                <span>{oferta.fecha_publicacion}</span>
            </div>

            <div className={`offer-card${isExpanded ? "expanded" : ""}`}>
                <div className="offer-card-request">
                    <button onClick={onExpand}>Solicitar</button>
                </div>

                {isExpanded && (
                    <div className="expanded-container">
                        <form>
                            <div className="form">
                                <label
                                    htmlFor="id_alumno"
                                    className="alumnos-titulo"
                                >
                                    Alumnos a solicitar:
                                </label>
                                <div className="alumnos-lista">
                                    {alumnosAsignados.length === 0 ? (
                                        <p>No hay alumnos asignados.</p>
                                    ) : (
                                        alumnosAsignados.map((alumno) => (
                                            <div
                                                key={alumno.id}
                                                className="alumno-card"
                                            >
                                                <label className="alumno-card-header">
                                                    <input
                                                        type="checkbox"
                                                        id={`alumno-${alumno.id}`}
                                                        name="alumnos"
                                                        className="alumno-checkbox"
                                                        value={alumno.id}
                                                    />
                                                    <div className="alumno-avatar">
                                                        {alumno.usuario
                                                            ?.nombre?.[0] ||
                                                            "?"}
                                                    </div>
                                                    <span className="alumno-nombre">
                                                        {alumno.usuario?.nombre}{" "}
                                                        {
                                                            alumno.usuario
                                                                ?.apellidos
                                                        }
                                                    </span>
                                                </label>
                                                <div className="alumno-card-info">
                                                    <span>
                                                        <strong>Curso:</strong>{" "}
                                                        {alumno.curso}
                                                    </span>
                                                    <span>
                                                        <strong>Grado:</strong>{" "}
                                                        {alumno.grado?.nombre ||
                                                            "Sin grado"}{" "}
                                                        (
                                                        {alumno.grado?.tipo ||
                                                            "Sin tipo"}
                                                        )
                                                    </span>
                                                    <span>
                                                        <strong>CV:</strong>{" "}
                                                        {alumno.cv ? (
                                                            <a
                                                                href={`/storage/${alumno.cv}`}
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                            >
                                                                Ver CV
                                                            </a>
                                                        ) : (
                                                            "No disponible"
                                                        )}
                                                    </span>
                                                </div>
                                            </div>
                                        ))
                                    )}
                                </div>
                            </div>
                            <div className="btn-actions">
                                <button
                                    type="button"
                                    id="btnSubmit"
                                    className="btn-submmit"
                                >
                                    Enviar solicitud
                                </button>
                                <button
                                    type="button"
                                    id="btnClose"
                                    className="btn-close"
                                    onClick={onExpand}
                                >
                                    Cerrar
                                </button>
                            </div>
                        </form>
                    </div>
                )}
            </div>
        </div>
    );
}
