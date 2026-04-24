import React, { useEffect, useState } from "react";
import ModalEditAssignment from "./modalEditAssignment";

export default function assignmentList() {
    const [asignaciones, setAsignaciones] = useState([]);
    const [showModal, setShowModal] = useState(false);
    const [assignmentEdit, setAssignmentEdit] = useState(null);

    const fetchAsignaciones = () => {
        let token = localStorage.getItem("api_token");

        let url = "/api/asignacion";

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + token,
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.asignaciones) {
                    setAsignaciones(data.asignaciones);
                }
            });
    };

    useEffect(() => {
        fetchAsignaciones();

        const interval = setInterval(() => {
            fetchAsignaciones();
        }, 30000);

        return () => clearInterval(interval);
    }, []);

    const handleEditClick = (asignacion) => {
        setAssignmentEdit(asignacion);
        setShowModal(true);
    };

    const handleUpdate = (updatedAsignacion) => {
        setAsignaciones((prev) =>
            prev.map((t) =>
                t.id === updatedAsignacion.id ? updatedAsignacion : t,
            ),
        );
        setShowModal(false);
        setAssignmentEdit(null);
        fetchAsignaciones();
    };

    const handleRemove = (id) => {
        let token = localStorage.getItem("api_token");
        let url = `/api/asignacion/${id}/eliminar`;

        fetch(url, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + token,
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    alert("La asignación ha sido eliminada correctamente.");
                    setAsignaciones((prev) => prev.filter((a) => a.id !== id));
                    fetchAsignaciones();
                }
            })
            .catch((error) => {
                alert("Error al eliminar la asignación.");
                console.log(error);
            });
    };

    return (
        <div className="list-container">
            <div className="table">
                <table>
                    <thead>
                        <tr>
                            <th>Alumno</th>
                            <th>Empresa</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {asignaciones.map((a) => (
                            <tr key={a.id}>
                                <td>
                                    {a.alumno?.usuario?.nombre}{" "}
                                    {a.alumno?.usuario?.apellidos}
                                </td>
                                <td>{a.empresa?.usuario?.nombre}</td>
                                <td>{a.estado}</td>
                                <td>
                                    <div className="actions-cell">
                                        <button
                                            type="button"
                                            className="btn-edit"
                                            onClick={() => handleEditClick(a)}
                                        >
                                            <img
                                                src="/images/update.svg"
                                                alt="Actualizar"
                                            />
                                        </button>
                                        <button
                                            type="button"
                                            className="btn-delete"
                                            onClick={() => handleRemove(a.id)}
                                        >
                                            <img
                                                src="/images/bin.svg"
                                                alt="Eliminar"
                                            />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            {showModal && assignmentEdit && (
                <ModalEditAssignment
                    onClose={() => {
                        setShowModal(false);
                        setAssignmentEdit(null);
                    }}
                    asignacion={assignmentEdit}
                    onUpdate={handleUpdate}
                />
            )}
        </div>
    );
}
