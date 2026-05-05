import React, { useEffect, useState } from "react";
import OffersStudent from "./offersStudent";
import OffersTeacher from "./offersTeacher";
import OffersCompany from "./offersCompany";

export default function offersRouter() {
    const [rol, setRol] = useState(null); // rol del usuario (null hasta verificar)

    /**
     * verifica que el usuario esté autenticado y obtiene su rol
     * si no está autenticado, redirige al login
     */
    useEffect(() => {
        const token = localStorage.getItem("api_token");
        const stored = localStorage.getItem("user");
        if (!token || !stored) {
            window.location.href = "/login";
            return;
        }
        setRol(JSON.parse(stored).rol);
    }, []);

    // -- RENDERIZAOD DE LAS OFERTAS SEGÚN EL ROL --

    if (rol === "alumno") return <OffersStudent />;
    if (rol === "profesor") return <OffersTeacher />;
    if (rol === "empresa") return <OffersCompany />;
    return null;
}
