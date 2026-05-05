import React, { useEffect, useState, useRef } from "react";

export default function headerTeacher() {
    const [open, setOpen] = useState(false); // control del menú de perfil
    const menuRef = useRef(); // referencia para detectar clics fuera del menú
    const [notificaciones, setNotificaciones] = useState([]); // lista de notificaciones del usuario
    const [notifOpen, setNotifOpen] = useState(false); // control del dropdown de notificaciones

    /**
     * detecta clics fuera del menú para cerrarlo automáticamente
     * nejora la intefaz al cerrar dropdowns al hacer clic en cualquier parte
     */
    useEffect(() => {
        function handleClickOutside(e) {
            if (menuRef.current && !menuRef.current.contains(e.target)) {
                setOpen(false);
            }
        }
        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, []);

    /**
     * maneja el cierre de sesión del usuario
     * 1. Llama a la API para invalidar el token
     * 2. Limpia localStorage
     * 3. Redirige al login
     */
    const logout = () => {
        const token = localStorage.getItem("api_token");
        let url = "/api/usuario/logout";

        fetch(url, {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
            },
        });
        localStorage.removeItem("api_token");
        localStorage.removeItem("user");
        window.location.href = "/login";
    };

    /**
     * obtiene las notificaciones del usuario desde la API
     * se ejecuta cuando el usuario abre el dropdown de notificaciones
     */
    const fetchNotificaciones = () => {
        const token = localStorage.getItem("api_token");

        let url = "/api/usuario/notificacion";

        fetch(url, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    setNotificaciones(data.notificaciones);
                }
            });
    };

    return (
        <header className="header-container">
            <div className="header-logo">
                <span className="header-brand">ConectaFP</span>
            </div>
            <nav className="header-nav">
                <a href="/feed">
                    <img src="/images/home.svg" alt="Inicio" />
                    <span>Inicio</span>
                </a>
                <a href="/ofertas">
                    <img src="/images/offers.svg" alt="Ofertas" />
                    <span>Ofertas</span>
                </a>
                <a href="/mis-tutorias">
                    <img src="/images/tutorial.svg" alt="Mis tutorías" />
                    <span>Mis tutorías</span>
                </a>
                <a href="/mis-solicitudes">
                    <img src="/images/requests.svg" alt="Solicitudes"></img>
                    <span>Solicitudes</span>
                </a>
            </nav>
            <div className="header-actions" ref={menuRef}>
                <a
                    className="header-notif"
                    onClick={(e) => {
                        e.preventDefault();
                        if (!notifOpen) fetchNotificaciones();
                        setNotifOpen(!notifOpen);
                    }}
                >
                    <img
                        src="/images/notifications.svg"
                        alt="Notificaciones"
                    ></img>
                    <span>Notificaciones</span>
                </a>
                {notifOpen && (
                    <div className="notifications-dropdown">
                        {notificaciones.length === 0 ? (
                            <div>No tienes notificaciones.</div>
                        ) : (
                            notificaciones.map((notif, idx) => {
                                // Calcula fecha relativa
                                const fecha = notif.created_at
                                    ? new Date(notif.created_at)
                                    : null;
                                const ahora = new Date();
                                let diff = "";
                                if (fecha) {
                                    const ms = ahora - fecha;
                                    const dias = Math.floor(
                                        ms / (1000 * 60 * 60 * 24),
                                    );
                                    const horas = Math.floor(
                                        (ms / (1000 * 60 * 60)) % 24,
                                    );
                                    if (dias > 0)
                                        diff = `hace ${dias} día${dias > 1 ? "s" : ""}`;
                                    else if (horas > 0)
                                        diff = `hace ${horas} hora${horas > 1 ? "s" : ""}`;
                                    else diff = "hace menos de 1 hora";
                                }

                                return (
                                    <div
                                        key={idx}
                                        className="notification-item"
                                    >
                                        <div className="notification-title">
                                            <img
                                                src="/images/notifications.svg"
                                                className="notif-icon"
                                                alt="icono"
                                            />
                                            {notif.data?.titulo ||
                                                "Notificación"}
                                        </div>
                                        <div className="notification-message">
                                            {notif.data?.mensaje ||
                                                notif.data?.message ||
                                                "Sin mensaje"}
                                        </div>
                                        <div className="notification-date">
                                            {diff}
                                        </div>
                                    </div>
                                );
                            })
                        )}
                    </div>
                )}
                <div
                    className="header-profile"
                    style={{ position: "relative" }}
                >
                    <img
                        className="header-avatar-initials"
                        src="/images/myprofile.svg"
                        onClick={() => setOpen(!open)}
                        style={{ cursor: "pointer" }}
                        alt="Mi perfil"
                    ></img>
                    {open && (
                        <div
                            className="profile-dropdown"
                            style={{
                                position: "absolute",
                                right: 0,
                                top: "100%",
                                background: "#fff",
                                border: "1px solid #ddd",
                                borderRadius: "8px",
                                boxShadow: "0 2px 8px rgba(0,0,0,0.08)",
                                zIndex: 10,
                                minWidth: "160px",
                            }}
                        >
                            <a href="#" className="dropdown-item">
                                Editar perfil
                            </a>
                            <a
                                href="#"
                                className="dropdown-item"
                                onClick={logout}
                            >
                                Cerrar sesión
                            </a>
                        </div>
                    )}
                </div>
            </div>
        </header>
    );
}
