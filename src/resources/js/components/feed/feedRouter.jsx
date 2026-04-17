import React, { useEffect, useState } from "react";
import FeedStudent from "./feedStudent";

export default function feedRouter() {
    const [rol, setRol] = useState(null);

    useEffect(() => {
        const stored = localStorage.getItem("user");
        if (stored) setRol(JSON.parse(stored).rol);
    }, []);

    if (rol === "alumno") return <FeedStudent />;

    return null;
}
