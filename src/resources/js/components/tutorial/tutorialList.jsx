import React, { useEffect, useState } from "react";

export default function tutorialList() {
    const [tutorias, setTutorias] = useState([]);

    useEffect(() => {
        const user = JSON.parse(localStorage.getItem("user"));
        const id = user?.id;
        if (!id) return;

        let url = `/api/tutoria/alumno/${id}`;

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.Tutorias) setTutorias(data.Tutorias);
            });
    }, []);

    function openGoogleCalendar(tutoria) {
        const formatGoogleDate = (fecha) => {
            if (!fecha) return "";
            const d = new Date(fecha.replace(" ", "T"));
            return (
                d.getUTCFullYear().toString() +
                String(d.getUTCMonth() + 1).padStart(2, "0") +
                String(d.getUTCDate()).padStart(2, "0") +
                "T" +
                String(d.getUTCHours()).padStart(2, "0") +
                String(d.getUTCMinutes()).padStart(2, "0") +
                String(d.getUTCSeconds()).padStart(2, "0") +
                "Z"
            );
        };

        const start = formatGoogleDate(tutoria.fecha_inicio);
        const end = formatGoogleDate(tutoria.fecha_fin);

        const url =
            `https://calendar.google.com/calendar/render?action=TEMPLATE` +
            `&text=Tutoría con ${encodeURIComponent(tutoria.profesor?.usuario?.nombre || "")}` +
            `&dates=${start}/${end}` +
            `&details=Empresa: ${encodeURIComponent(tutoria.empresa?.usuario?.nombre || "")}`;

        window.open(url, "_blank");
    }

    return (
        <div className="list-container">
            <div className="card-container">
                {tutorias.length === 0 ? (
                    <p>No tienes tutorías.</p>
                ) : (
                    tutorias.map((tutoria) => (
                        <div className="tutorial-card" key={tutoria.id}>
                            <div className="tutorial-card-header">
                                <div className="tutorial-card-company">
                                    <img
                                        src="images/company.svg"
                                        alt="Empresa"
                                    ></img>
                                    <h3>{tutoria.empresa?.usuario?.nombre}</h3>
                                </div>
                                <div className="tutorial-card-teacher">
                                    <img
                                        src="images/teacher.svg"
                                        alt="Profesor"
                                    ></img>
                                    <span>
                                        {tutoria.profesor?.usuario?.nombre}
                                    </span>
                                </div>
                                <span
                                    className={
                                        "tutorial-card-status " +
                                        (tutoria.estado === "Activa"
                                            ? "activa"
                                            : tutoria.estado === "Finalizada"
                                              ? "finalizada"
                                              : "cancelada")
                                    }
                                >
                                    {tutoria.estado}
                                </span>
                            </div>
                            <div className="tutorial-card-footer">
                                <div className="tutorial-card-date">
                                    <img
                                        src="/images/schedule.svg"
                                        alt="Calendario"
                                    ></img>
                                    <span>
                                        {tutoria.fecha_inicio} ●{" "}
                                        {tutoria.fecha_fin}
                                    </span>
                                </div>
                            </div>
                            <div className="tutorial-card-button">
                                <button
                                    type="button"
                                    onClick={() => openGoogleCalendar(tutoria)}
                                >
                                    Añadir a Google Calendar
                                </button>
                            </div>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
}
