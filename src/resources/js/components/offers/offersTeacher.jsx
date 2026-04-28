import React, { useEffect, useState } from "react";
import OfferCardTeacher from "./offerCardTeacher";

export default function offersTeacher() {
    const [sectores, setSectores] = useState([]);
    const [ofertas, setOfertas] = useState([]);
    const [solicitud, setSolicitud] = useState([]);
    const [mensaje, setMensaje] = useState(null);
    const [expandedOfferId, setExpandedOfferId] = useState(null);

    const token = localStorage.getItem("api_token");
    const userStr = localStorage.getItem("user");
    if (!token || !userStr) {
        window.location.href = "/login";
        return;
    }

    useEffect(() => {
        let url = "/api/sector";

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                Authorization: "Bearer" + token,
            },
        })
            .then((res) => {
                if (res.status === 401) {
                    window.location.href = "/login";
                    return;
                }
                return res.json();
            })
            .then((data) => setSectores(data.sectores));

        fetch("/api/oferta", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                Authorization: "Bearer" + token,
            },
        })
            .then((res) => {
                if (res.status === 401) {
                    console.log("Redirigiendo a login");
                    window.location.href = "/login";
                    return;
                }
                return res.json();
            })
            .then((data) => setOfertas(data.alumnos || []));
    }, []);

    const handleFiltros = () => {
        const params = new URLSearchParams();
        const modalidad = document.getElementById("modalidad").value;
        const sector = document.getElementById("id_sector").value;
        const titulo = document.getElementById("titulo").value;
        const checkSolicitado = document.getElementById("ofertas-solicitadas");

        if (modalidad) {
            params.append("modalidad", modalidad);
        }

        if (sector) {
            params.append("id_sector", sector);
        }

        if (titulo) {
            params.append("titulo", titulo);
        }

        if (checkSolicitado && checkSolicitado.checked) {
            const user = JSON.parse(localStorage.getItem("user"));
            console.log(user.id);
            params.append("id_profesor", user.id);
        }

        let url = `/api/oferta/filtrar?${params.toString()}`;

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
        })
            .then((res) => {
                if (res.status === 401) {
                    window.location.href = "/login";
                    return;
                }
                return res.json();
            })
            .then((data) => {
                if (data.success) {
                    setOfertas(data.ofertas);
                    setMensaje(null);
                } else {
                    setOfertas([]);
                    setMensaje("No se encontraron ofertas.");
                }
            })
            .catch(() => {
                alert("Error en la conexión");
            });
    };

    const handleLimpiar = () => {
        setMensaje(null);
        let url = "/api/oferta";
        document.getElementById("filter").reset();

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
        })
            .then((res) => {
                if (res.status === 401) {
                    window.location.href = "/login";
                    return;
                }
                return res.json();
            })
            .then((data) => setOfertas(data.alumnos || []));
    };

    return (
        <div className="feed-container">
            <div id="filter-container">
                <h3>Filtros</h3>
                <form id="filter">
                    <div className="form">
                        <label htmlFor="modalidad">Modalidad</label>
                        <select id="modalidad" multiple>
                            <option value="Presencial">Presencial</option>
                            <option value="Remoto">Remoto</option>
                            <option value="Híbrido">Híbrido</option>
                        </select>
                    </div>
                    <div className="form">
                        <label htmlFor="id_sector">Sector</label>
                        <select id="id_sector" name="sector">
                            <option value="">-- Todos los sectores --</option>
                            {sectores.map((sector) => (
                                <option key={sector.id} value={sector.id}>
                                    {sector.nombre}
                                </option>
                            ))}
                        </select>
                    </div>
                    <div className="form">
                        <label htmlFor="titulo">Título</label>
                        <input
                            type="text"
                            id="titulo"
                            placeholder="Buscar por título..."
                        />
                    </div>
                    <div className="form">
                        <label
                            htmlFor="ofertas-solicitadas"
                            className="checkbox-label"
                        >
                            Ofertas solicitadas
                        </label>
                        <input
                            type="checkbox"
                            id="ofertas-solicitadas"
                            className="filter-checkbox"
                        ></input>
                    </div>
                    <button
                        type="button"
                        className="filter-btn"
                        onClick={handleFiltros}
                    >
                        Aplicar filtros
                    </button>
                    <button
                        type="button"
                        className="filter-clear"
                        onClick={handleLimpiar}
                    >
                        Limpiar filtros
                    </button>
                </form>
            </div>

            <div className="card-container">
                {mensaje ? (
                    <p className="no-results">{mensaje}</p>
                ) : ofertas.length === 0 ? (
                    <p className="no-results">No hay ofertas disponibles.</p>
                ) : (
                    ofertas.map((oferta) => (
                        <OfferCardTeacher
                            key={oferta.id}
                            oferta={oferta}
                            isExpanded={expandedOfferId === oferta.id}
                            onExpand={() =>
                                setExpandedOfferId(
                                    expandedOfferId === oferta.id
                                        ? null
                                        : oferta.id,
                                )
                            }
                        />
                    ))
                )}
            </div>
        </div>
    );
}
