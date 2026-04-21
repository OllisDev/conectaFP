import React, { useState, useEffect } from "react";

export default function myRequests() {
    const [solicitudes, setSolicitudes] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const user = JSON.parse(localStorage.getItem("user"));
        const idAlumno = user?.id;

        fetch(`/api/solicitud/alumno/${idAlumno}`)
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    setSolicitudes(data.solicitud);
                }
            })
            .finally(() => setLoading(false));
    }, []);

    return (
        <div className="requests-container">
            <h1>Mis solicitudes</h1>
            <div className="table-container">
                {loading ? (
                    <p>Cargando...</p>
                ) : solicitudes.length === 0 ? (
                    <p>No tienes solicitudes.</p>
                ) : (
                    <table>
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Empresa</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            {solicitudes.map((s, i) => (
                                <tr key={i}>
                                    <td>{s.oferta?.titulo}</td>
                                    <td>
                                        {s.oferta?.empresa?.usuario?.nombre}
                                    </td>
                                    <td>{s.fecha_solicitud}</td>
                                    <td>{s.estado}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                )}
            </div>
        </div>
    );
}
