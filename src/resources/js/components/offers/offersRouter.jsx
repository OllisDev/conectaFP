import React, { useEffect, useState } from "react";
import OffersStudent from "./offersStudent";

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
    return null;
}
