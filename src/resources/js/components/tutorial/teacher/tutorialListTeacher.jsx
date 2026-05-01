import React, { useEffect, useState } from "react";
import ModalEdit from "./modalEditTutorial";

export default function tutorialListTeacher() {
    const [tutorias, setTutorias] = useState([]);
    const [showModal, setShowModal] = useState(false);
    const [tutorialEdit, setTutorialEdit] = useState(null);

    const fetchTutorias = () => {
        const token = localStorage.getItem("api_token");

        let url = "/api/tutoria/profesor";

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
                if (data.Tutorias) {
                    setTutorias(data.Tutorias);
                }
            });
    };

    useEffect(() => {
        fetchTutorias();

        const interval = setInterval(() => {
            fetchTutorias();
        }, 30000);

        return () => clearInterval(interval);
    }, []);

    const handleEditClick = (tutoria) => {
        setTutorialEdit(tutoria);
        setShowModal(true);
    };

    const handleUpdate = (updatedTutoria) => {
        setTutorias((prev) =>
            prev.map((t) => (t.id === updatedTutoria.id ? updatedTutoria : t)),
        );
        setShowModal(false);
        setTutorialEdit(null);
        fetchTutorias();
    };

    const handleRemove = (id) => {
        let url = `/api/tutoria/${id}/eliminar`;

        fetch(url, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    alert("La tutoría se ha eliminado correctamente.");
                    setTutorias((prev) => prev.filter((t) => t.id !== id));
                    fetchTutorias();
                }
            })
            .catch((error) => {
                alert("Error al eliminar la tutoría.");
                console.log(error);
            });
    };

    return (
        <div className="list-container">
            <div className="card-container">
                {tutorias.length === 0 ? (
                    <p>No hay tutorías.</p>
                ) : (
                    tutorias.map((tutoria) => (
                        <div className="tutorial-card" key={tutoria.id}>
                            <div className="tutorial-card-header">
                                <div className="tutorial-card-company">
                                    <img
                                        src="images/company.svg"
                                        alt="Empresa"
                                    />
                                    <h3>{tutoria.empresa?.usuario?.nombre}</h3>
                                </div>
                                <div className="tutorial-card-student">
                                    <img
                                        src="images/student.svg"
                                        alt="Alumno"
                                    ></img>
                                    <span>
                                        {tutoria.alumno?.usuario?.nombre}
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
                            <div className="tutorial-card-actions">
                                <button
                                    type="button"
                                    className="btn-delete"
                                    onClick={() => handleRemove(tutoria.id)}
                                >
                                    <img
                                        src="/images/bin.svg"
                                        alt="Papelera"
                                    ></img>
                                </button>
                                <button
                                    type="button"
                                    className="btn-edit"
                                    onClick={() => handleEditClick(tutoria)}
                                >
                                    <img
                                        src="/images/update.svg"
                                        alt="Rueda"
                                    ></img>
                                </button>
                            </div>
                        </div>
                    ))
                )}
            </div>
            {showModal && (
                <ModalEdit
                    onClose={() => setShowModal(false)}
                    tutoria={tutorialEdit}
                    onUpdate={handleUpdate}
                />
            )}
        </div>
    );
}
