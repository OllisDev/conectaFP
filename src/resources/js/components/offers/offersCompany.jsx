import React, { useEffect, useState } from "react";
import OfferCardCompany from "./offerCardCompany";
import ModalCreateOffer from "./modalCreateOffer";

export default function offersCompany() {
    const [ofertas, setOfertas] = useState([]);
    const [mensaje, setMensaje] = useState(null);
    const [showModal, setShowModal] = useState(false);

    const token = localStorage.getItem("api_token");
    const userStr = localStorage.getItem("user");
    if (!token || !userStr) {
        window.location.href = "/login";
        return;
    }

    useEffect(() => {
        let url = "/api/oferta/empresa";

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
                setOfertas(data.ofertas);
            });
    }, []);

    return (
        <div className="feed-container">
            <div className="card-container">
                <div className="create-container">
                    <button
                        type="button"
                        className="btn-create"
                        id="btnCreate"
                        onClick={() => setShowModal(true)}
                    >
                        <img
                            src="/images/create.svg"
                            alt="Crear oferta"
                            className="img-create"
                        />
                    </button>
                </div>
                {mensaje ? (
                    <p className="no-results">{mensaje}</p>
                ) : ofertas.length === 0 ? (
                    <p className="no-results">No hay ofertas disponibles.</p>
                ) : (
                    ofertas.map((oferta) => (
                        <OfferCardCompany key={oferta.id} oferta={oferta} />
                    ))
                )}
            </div>
            {showModal && (
                <ModalCreateOffer onClose={() => setShowModal(false)} />
            )}
        </div>
    );
}
