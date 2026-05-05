import React, { useEffect, useState } from "react";
import MyRequestsStudent from "./myRequestsStudent";
import MyRequestsTeacher from "./myRequestsTeacher";
import MyRequestsCompany from "./myRequestsCompany";

export default function myRequestsRouter() {
    const [rol, setRol] = useState(null);

    useEffect(() => {
        const token = localStorage.getItem("api_token");
        const stored = localStorage.getItem("user");
        if (!token || !stored) {
            window.location.href = "/login";
            return;
        }
        setRol(JSON.parse(stored).rol);
    }, []);

    // -- RENDERIZADO DE LA PÁGINA DE SOLICITUDES SEGÚN EL ROL --

    if (rol === "alumno") return <MyRequestsStudent />;
    if (rol === "profesor") return <MyRequestsTeacher />;
    if (rol === "empresa") return <MyRequestsCompany />;
    return null;
}
