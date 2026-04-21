import React, { useEffect, useState } from "react";
import OffersStudent from "./offersStudent";

export default function offersRouter() {
    const [rol, setRol] = useState(null);

    useEffect(() => {
        const stored = localStorage.getItem("user");
        if (stored) setRol(JSON.parse(stored).rol);
    }, []);

    if (rol === "alumno") return <OffersStudent />;

    return null;
}
