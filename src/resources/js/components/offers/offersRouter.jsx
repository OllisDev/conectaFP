import React, { useEffect, useState } from "react";
import OffersStudent from "./offersStudent";
import OffersTeacher from "./offersTeacher";

export default function offersRouter() {
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

    if (rol === "alumno") return <OffersStudent />;
    if (rol === "profesor") return <OffersTeacher />;
    return null;
}
