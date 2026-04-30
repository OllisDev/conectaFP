import React, { useEffect, useState } from "react";
import Select from "react-select";

export default function createTutorial() {
    const [alumnos, setAlumnos] = useState([]);
    const [empresas, setEmpresas] = useState([]);
    const [errors, setErrors] = useState([]);
    const [success, setSuccess] = useState("");
    const [form, setForm] = useState({
        id_alumno: "",
        id_empresa: "",
        fecha_inicio: "",
        fecha_fin: "",
        estado: "",
    });

    const token = localStorage.getItem("api_token");

    const handleChange = (e) => {
        setForm((prev) => ({
            ...prev,
            [e.target.id]: e.target.value,
        }));
        setErrors({});
    };

    const validate = () => {
        const newErrors = {};

        if (!form.id_alumno) {
            newErrors.id_alumno = "El alumno es obligatorio.";
        }

        if (!form.id_empresa) {
            newErrors.id_empresa = "La empresa es obligatoria.";
        }

        if (!form.fecha_inicio) {
            newErrors.fecha_inicio = "La fecha de inicio es obligatoria.";
        } else {
            const fechaInicio = new Date(form.fecha_inicio);
            const now = new Date();
            if (isNaN(fechaInicio.getTime())) {
                newErrors.fecha_inicio = "La fecha de inicio no es válida.";
            } else if (fechaInicio < new Date(now.toDateString())) {
                newErrors.fecha_inicio =
                    "La fecha de inicio no puede ser anterior a hoy.";
            }
        }

        if (form.fecha_fin) {
            const fechaFin = new Date(form.fecha_fin);
            const fechaInicio = new Date(form.fecha_inicio);
            if (isNaN(fechaFin.getTime())) {
                newErrors.fecha_fin = "La fecha de fin no es válida.";
            } else if (fechaFin <= fechaInicio) {
                newErrors.fecha_fin =
                    "La fecha de fin debe ser posterior a la fecha de inicio.";
            }
        }

        if (!form.estado) {
            newErrors.estado = "El estado es obligatorio.";
        } else if (
            !["Activa", "Finalizada", "Cancelada"].includes(form.estado)
        ) {
            newErrors.estado =
                'El estado debe ser "Activa", "Finalizada" o "Cancelada".';
        }

        return newErrors;
    };

    useEffect(() => {
        let url = "/api/alumnos/profesor";

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
                if (data.success && Array.isArray(data.alumnos)) {
                    setAlumnos(data.alumnos);
                } else {
                    setAlumnos([]);
                }
            })
            .catch((error) => {
                alert("Error de conexión");
                console.log(error);
            });
    }, []);

    useEffect(() => {
        if (!form.id_alumno) {
            setEmpresas([]);
            return;
        }
        let url = `/api/solicitud/empresa/alumno/${form.id_alumno}/aceptado`;

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
                if (data.success && Array.isArray(data.empresas)) {
                    setEmpresas(data.empresas);
                } else {
                    setEmpresas([]);
                }
            });
    }, [form.id_alumno, token]);

    const alumnoOptions = (alumnos || []).map((alumno) => ({
        value: alumno.id,
        label: alumno.usuario
            ? `${alumno.usuario.nombre} ${alumno.usuario.apellidos}`
            : `Alumno ${alumno.id}`,
    }));

    const empresaOptions = (empresas || []).map((empresa) => ({
        value: empresa.id,
        label: empresa.usuario
            ? `${empresa.usuario.nombre}`
            : `Empresa ${empresa.id}`,
    }));

    function formatDateTimeLocal(value) {
        if (!value) return "";

        const [date, time] = value.split("T");

        return `${date} ${time.length === 5 ? time + ":00" : time}`;
    }

    const handleSubmit = (e) => {
        e.preventDefault();
        const validationErrors = validate();
        if (Object.keys(validationErrors).length > 0) {
            setErrors(validationErrors);
            return;
        }

        const formData = new FormData();
        Object.entries(form).forEach(([key, value]) => {
            formData.append(key, value);
        });

        const formToSend = {
            ...form,
            fecha_inicio: formatDateTimeLocal(form.fecha_inicio),
            fecha_fin: formatDateTimeLocal(form.fecha_fin),
        };

        let url = "/api/tutoria/crear";

        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
            body: JSON.stringify(formToSend),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    setSuccess("La tutoria se ha creado correctamente.");
                    setErrors([]);
                } else {
                    setErrors(data.message);
                }
            })
            .catch((error) => {
                alert("Error de conexión.");
                console.log(error);
            });
    };

    return (
        <div className="create-container">
            <div className="form-container">
                <form onSubmit={handleSubmit}>
                    <div className="form">
                        <label html_for="id_alumno">Alumno asignado:</label>
                        <Select
                            id="id_alumno"
                            onChange={handleChange}
                            options={alumnoOptions}
                            placeholder="Busca un alumno asignado..."
                            isClearable
                            value={
                                alumnoOptions.find(
                                    (option) => option.value === form.id_alumno,
                                ) || null
                            }
                            onChange={(selected) => {
                                setForm((prev) => ({
                                    ...prev,
                                    id_alumno: selected ? selected.value : "",
                                    id_empresa: "",
                                }));
                            }}
                        ></Select>
                    </div>
                    <div className="form">
                        <label htmlFor="id_empresa">
                            Empresa asignada del alumno:
                        </label>
                        <Select
                            id="id_empresa"
                            onChange={handleChange}
                            options={empresaOptions}
                            placeholder="Busca una empresa asignada..."
                            isClearable
                            value={
                                empresaOptions.find(
                                    (option) =>
                                        option.value === form.id_empresa,
                                ) || null
                            }
                            onChange={(selected) => {
                                setForm((prev) => ({
                                    ...prev,
                                    id_empresa: selected ? selected.value : "",
                                }));
                                setErrors({});
                            }}
                            noOptionsMessage={() =>
                                !form.id_alumno
                                    ? "Selecciona un alumno primero."
                                    : "El alumno seleccionado no tiene empresas asociadas."
                            }
                        ></Select>
                    </div>
                    <div className="form">
                        <label htmlFor="fecha_inicio">
                            Fecha de inicio de la tutoría:
                        </label>
                        <input
                            type="datetime-local"
                            id="fecha_inicio"
                            onChange={handleChange}
                        ></input>
                    </div>
                    <div className="form">
                        <label htmlFor="fecha_fin">
                            Fecha de finalización de la tutoría:
                        </label>
                        <input
                            type="datetime-local"
                            id="fecha_fin"
                            onChange={handleChange}
                        ></input>
                    </div>
                    <div className="form">
                        <label htmlFor="estado">Estado de la tutoría:</label>
                        <select
                            id="estado"
                            onChange={handleChange}
                            className="select-status"
                            name="estado"
                        >
                            <option value="">-- Selecciona un estado --</option>
                            <option value="Activa">Activa</option>
                            <option value="Finalizada">Finalizada</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>
                    {Object.keys(errors).length > 0 && (
                        <div className="error-box">
                            <p>{Object.values(errors)[0]}</p>
                        </div>
                    )}
                    {success && (
                        <div className="success-box">
                            <p>{success}</p>
                        </div>
                    )}
                    <button type="submit" id="btn-tutorial">
                        Crear tutoría
                    </button>
                </form>
            </div>
        </div>
    );
}
