import React, { useState, useEffect } from "react";
import HeaderStudent from "./headerStudent";
import HeaderTeacher from "./headerTeacher";
import HeaderCompany from "./headerCompany";

export default function HeaderRouter() {
    const [rol, setRol] = useState(null); // rol del usuario actual (null hasta cargar)

    /**
     * obtiene el rol del usuario desde localStorage al montar el componente
     * el rol se guarda durante el proceso de login
     */
    useEffect(() => {
        const stored = localStorage.getItem("user");
        if (stored) setRol(JSON.parse(stored).rol);
    }, []);

    // -- RENDERIZADO DEL HEADER SEGÚN EL ROL --

    if (rol === "alumno") return <HeaderStudent />;
    if (rol === "profesor") return <HeaderTeacher />;
    if (rol === "empresa") return <HeaderCompany />;

    return null;
}
