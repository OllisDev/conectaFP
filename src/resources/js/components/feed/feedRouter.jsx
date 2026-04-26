import React, { useEffect, useState } from "react";
import FeedStudent from "./feedStudent";

export default function feedRouter() {
    const user = localStorage.getItem("user");
    const token = localStorage.getItem("api_token");
    if (!user || !token) {
        window.location.href = "/login";
        return null;
    }

    const [rol, setRol] = useState(null);

    useEffect(() => {
        if (user) setRol(JSON.parse(user).rol);
    }, [user]);

    if (rol === "alumno") return <FeedStudent />;
    return null;
}
