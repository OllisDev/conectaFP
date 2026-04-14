import React, { useState, useEffect } from "react";
import HeaderStudent from "./headerStudent";
import HeaderTeacher from "./headerTeacher";
import HeaderCompany from "./headerCompany";

export default function HeaderRouter() {
    const [rol, setRol] = useState(null);

    useEffect(() => {
        const stored = localStorage.getItem("user");
        if (stored) setRol(JSON.parse(stored).rol);
    }, []);

    if (rol === "alumno") return <HeaderStudent />;
    if (rol === "profesor") return <HeaderTeacher />;
    if (rol === "empresa") return <HeaderCompany />;

    return null;
}
