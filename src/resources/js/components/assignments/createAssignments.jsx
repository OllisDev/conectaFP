import React, { useEffect, useState } from "react";
import Select from "react-select";

export default function createAssignments() {
    const [alumnos, setAlumnos] = useState([]);
    const [selectedAlumno, setSelectedAlumno] = useState(null);
    const [empresas, setEmpresas] = useState([]);
    const [selectedEmpresa, setSelectedEmpresa] = useState(null);
    const [errors, setErrors] = useState("");
    const [success, setSuccess] = useState("");
    const [form, setForm] = useState({
        id_alumno: "",
        id_empresa: "",
        estado: "",
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setForm((prev) => ({
            ...prev,
            [name]: value,
        }));
        setErrors("");
    };

    const validate = () => {
        const newErrors = {};

        if (!form.id_alumno) {
            newErrors.id_alumno = "El alumno es obligatorio.";
        }

        if (!form.id_empresa) {
            newErrors.id_empresa = "La empresa es obligatoria.";
        }

        // Estado
        if (!form.estado) {
            newErrors.estado = "El estado es obligatorio.";
        } else if (!["Activo", "Finalizado"].includes(form.estado)) {
            newErrors.estado = 'El estado debe ser "Activo" o "Finalizado".';
        }

        return newErrors;
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        const validationErrors = validate();
        if (Object.keys(validationErrors).length > 0) {
            setErrors(validationErrors);
            return;
        }

        let url = "/api/asignacion/crear";
        let token = localStorage.getItem("api_token");
        console.log(localStorage.getItem("api_token"));

        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + token,
            },
            body: JSON.stringify(form),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    setSuccess("La asignación ha sido creada correctamente.");
                    setErrors("");
                } else {
                    setErrors(data.message);
                }
            })
            .catch((error) => {
                alert("Error de conexión.");
                console.log(error);
            });
    };

    useEffect(() => {
        let urlStudent = "/api/alumno";

        fetch(urlStudent, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                const alumnosArray = data.Alumnos || data.alumnos || data;
                if (Array.isArray(alumnosArray)) {
                    const options = alumnosArray.map((a) => ({
                        value: a.id,
                        label: a.usuario
                            ? `${a.usuario.nombre} ${a.usuario.apellidos}`
                            : a.id_usuario,
                    }));
                    setAlumnos(options);
                } else {
                    setAlumnos([]);
                }
            });
    }, []);

    useEffect(() => {
        if (selectedAlumno) {
            let urlCompany = `/api/empresa/aceptado/${selectedAlumno.value}`;
            fetch(urlCompany, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    const empresasArray = data.empresas || [];
                    if (Array.isArray(empresasArray)) {
                        const options = empresasArray.map((a) => ({
                            value: a.id,
                            label: a.usuario
                                ? `${a.usuario.nombre}`
                                : a.id_usuario,
                        }));
                        setEmpresas(options);
                    } else {
                        setEmpresas([]);
                    }
                });
        } else {
            setEmpresas([]);
        }
    }, [selectedAlumno]);

    return (
        <div className="create-container">
            <div className="form-container">
                <form onSubmit={handleSubmit}>
                    <div className="form">
                        <label htmlFor="id_alumno">Alumno a asignar:</label>
                        <Select
                            id="id_alumno"
                            options={alumnos}
                            value={selectedAlumno}
                            onChange={(option) => {
                                setSelectedAlumno(option);
                                setForm((prev) => ({
                                    ...prev,
                                    id_alumno: option ? option.value : "",
                                }));
                                setErrors("");
                            }}
                            placeholder="Buscar un alumno..."
                            isClearable
                        ></Select>
                    </div>
                    <div className="form">
                        <label htmlFor="id_empresa">
                            Empresa a asignar al alumno:
                        </label>
                        <Select
                            id="id_empresa"
                            options={empresas}
                            value={selectedEmpresa}
                            onChange={(option) => {
                                setSelectedEmpresa(option);
                                setForm((prev) => ({
                                    ...prev,
                                    id_empresa: option ? option.value : "",
                                }));
                                setErrors("");
                            }}
                            placeholder="Buscar una empresa..."
                            isClearable
                            noOptionsMessage={() =>
                                selectedAlumno
                                    ? "No hay empresas aceptadas para este alumno"
                                    : "Primero selecciona un alumno"
                            }
                        ></Select>
                    </div>
                    <div className="form">
                        <label htmlFor="estado">Estado de la asignación:</label>
                        <select
                            id="estado"
                            className="select-status"
                            name="estado"
                            value={form.estado}
                            onChange={handleChange}
                        >
                            <option value="">-- Selecciona un estado --</option>
                            <option value="Activo">Activo</option>
                            <option value="Finalizado">Finalizado</option>
                        </select>
                    </div>
                    <button type="submit" id="btn-assignment">
                        Asignar
                    </button>
                    {errors && (
                        <div className="error-box">
                            <p>
                                {typeof errors === "string"
                                    ? errors
                                    : Object.values(errors)[0]}
                            </p>
                        </div>
                    )}

                    {success && (
                        <div className="success-box">
                            <p>{success}</p>
                        </div>
                    )}
                </form>
            </div>
        </div>
    );
}
