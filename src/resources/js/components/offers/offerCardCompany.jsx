import React, { useState, useEffect } from "react";

export default function OfferCardCompany({ oferta, onDelete }) {
    const estadoClass =
        oferta.estado === "Abierta"
            ? "abierta"
            : oferta.estado === "Cerrada"
              ? "cerrada"
              : "pausada";

    const handleDelete = () => {
        const token = localStorage.getItem("api_token");
        let url = `/api/oferta/${oferta.id}/eliminar`;

        fetch(url, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    alert("La oferta se ha eliminado correctamente.");
                    if (onDelete) {
                        onDelete(oferta.id);
                    } else {
                        alert("Error al eliminar la oferta");
                    }
                }
            });
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

            <div className="offer-card-actions">
                <button
                    type="button"
                    id="btnRemove"
                    className="btn-remove"
                    onClick={handleDelete}
                >
                    <img
                        src="/images/bin.svg"
                        alt="Papelera"
                        className="btn-remove-img"
                    ></img>
                </button>
            </div>
        </div>
    );
}
