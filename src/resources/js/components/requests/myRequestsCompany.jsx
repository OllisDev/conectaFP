import React, { useEffect, useState } from "react";

export default function myRequestsCompany() {
    const [solicitudes, setSolicitudes] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const token = localStorage.getItem("api_token");
        const userStr = localStorage.getItem("user");
        if (!token || !userStr) {
            window.location.href = "/login";
            return;
        }

        let url = "/api/solicitud/empresa";

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        })
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
            <h1>Solicitudes</h1>
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
                                <th>Profesor</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {solicitudes.map((s, i) => (
                                <tr key={i}>
                                    <td>{s.oferta?.titulo}</td>
                                    <td>{s.profesor?.usuario?.nombre}</td>
                                    <td>{s.fecha_solicitud}</td>
                                    <td>{s.estado}</td>
                                    <td>
                                        <button
                                            type="button"
                                            className="btn-update"
                                        >
                                            <img
                                                src="/images/update.svg"
                                                className="btn-update-img"
                                            ></img>
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                )}
            </div>
        </div>
    );
}
