import React, { useState, useEffect } from "react";

export default function OfferCard({
    oferta,
    yaSolicitada,
    solicitudId,
    idAlumno,
}) {
    const estadoClass =
        oferta.estado === "Abierta"
            ? "abierta"
            : oferta.estado === "Cerrada"
              ? "cerrada"
              : "pausada";

    const [solicitada, setSolicitada] = useState(yaSolicitada);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        setSolicitada(yaSolicitada);
    }, [yaSolicitada]);

    const handleSolicitar = () => {
        setLoading(true);
        fetch("/api/solicitud", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_oferta: oferta.id, id_alumno: idAlumno }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    setSolicitada(true);
                } else {
                    alert(data.message || "Error al solicitar.");
                }
            })
            .catch(() => alert("Error de conexión."))
            .finally(() => setLoading(false));
    };

    const handleCancelar = () => {
        setLoading(true);
        fetch(`/api/solicitud/${solicitudId}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "applicaction/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    setSolicitada(false);
                } else {
                    alert(data.message || "Error al cancelar.");
                }
            })
            .catch(() => alert("Error de conexión."))
            .finally(() => setLoading(false));
    };

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

            <div className="offer-card-request">
                {solicitada ? (
                    <input
                        type="button"
                        value={loading ? "Cancelando..." : "Cancelar solicitud"}
                        onClick={!loading ? handleCancelar : undefined}
                        disabled={loading}
                    />
                ) : (
                    <input
                        type="button"
                        value={loading ? "Enviando..." : "Solicitar"}
                        onClick={!loading ? handleSolicitar : undefined}
                        disabled={loading}
                    />
                )}
            </div>
        </div>
    );
}
