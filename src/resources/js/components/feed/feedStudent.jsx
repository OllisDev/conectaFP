import React, { useEffect, useState } from "react";
import OfferCard from "./offerCard";

export default function feedStudent() {
    const [sectores, setSectores] = useState([]);
    const [ofertas, setOfertas] = useState([]);
    const [solicitud, setSolicitud] = useState([]);

    useEffect(() => {
        let url = "/api/sector";

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => setSectores(data.sectores));

        fetch("/api/oferta", {
            method: "GET",
            headers: { "Content-Type": "application/json" },
        })
            .then((res) => res.json())
            .then((data) => setOfertas(data.alumnos));

        const user = JSON.parse(localStorage.getItem("user"));
        const idAlumno = user?.id;

        fetch(`/api/solicitud/alumno/${idAlumno}`)
            .then((res) => res.json())
            .then((data) => {
                if (data.success) setSolicitud(data.solicitud);
            });
    }, []);

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
                    <button type="button" className="filter-btn">
                        Aplicar filtros
                    </button>
                    <button type="button" className="filter-clear">
                        Limpiar filtros
                    </button>
                </form>
            </div>

            <div className="card-container">
                {ofertas.map((oferta) => (
                    <OfferCard
                        key={oferta.id}
                        oferta={oferta}
                        yaSolicitada={solicitud.some(
                            (s) => s.id_oferta === oferta.id,
                        )}
                        solicitudId={
                            solicitud.find((s) => s.id_oferta === oferta.id)?.id
                        }
                        idAlumno={JSON.parse(localStorage.getItem("user"))?.id}
                    />
                ))}
            </div>
        </div>
    );
}
