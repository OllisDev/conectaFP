import React, { useEffect, useState } from "react";
import OfferCardCompany from "./offerCardCompany";
import ModalCreateOffer from "./modalCreateOffer";

export default function offersCompany() {
    const [ofertas, setOfertas] = useState([]); // lista de ofertas de prácticas disponibles
    const [mensaje, setMensaje] = useState(null); // mensaje personalizado
    const [showModal, setShowModal] = useState(false); // controlar la visibilidad del modal de creación

    const token = localStorage.getItem("api_token");
    const userStr = localStorage.getItem("user");
    if (!token || !userStr) {
        window.location.href = "/login";
        return;
    }

    /**
     * obtiene las ofertas de trabajo de la empresa actual desde la API
     */
    const fetchOfertas = () => {
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
                setOfertas(Array.isArray(data.ofertas) ? data.ofertas : []); // asegurar que las ofertas de prácticas siempre sean un array
            });
    };

    // cargar las ofertas al montar el componente
    useEffect(() => {
        fetchOfertas();
    }, []);

    /**
     * maneja la eliminación de una oferta actualizando el estado local
     * @param {number} id
     */
    const handleDeleteOffer = (id) => {
        setOfertas((prevOfertas) =>
            prevOfertas.filter((oferta) => oferta.id !== id),
        );
    };

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
                        <OfferCardCompany
                            key={oferta.id}
                            oferta={oferta}
                            onDelete={handleDeleteOffer}
                        />
                    ))
                )}
            </div>
            {showModal && (
                <ModalCreateOffer
                    onClose={() => setShowModal(false)}
                    onOfferCreated={fetchOfertas}
                />
            )}
        </div>
    );
}
