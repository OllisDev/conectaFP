import React, { useEffect, useState, useRef } from "react";

export default function headerStudent() {
    const [open, setOpen] = useState(false);
    const menuRef = useRef();

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
                <a href="/mis-solicitudes">
                    <img src="/images/requests.svg" alt="Solicitudes" />
                    <span>Solicitudes</span>
                </a>
                <a href="/mi-tutoria">
                    <img src="/images/tutorial.svg" alt="Mi tutoría" />
                    <span>Mi tutoría</span>
                </a>
            </nav>
            <div className="header-actions" ref={menuRef}>
                <a href="#" className="header-notif">
                    <img
                        src="/images/notifications.svg"
                        alt="Notificaciones"
                    ></img>
                    <span>Notificaciones</span>
                </a>
                <div
                    className="header-profile"
                    style={{ position: "relative" }}
                >
                    <span
                        className="header-avatar-initials"
                        onClick={() => setOpen(!open)}
                        style={{ cursor: "pointer" }}
                    ></span>
                    <span
                        className="profile"
                        onClick={() => setOpen(!open)}
                        style={{ cursor: "pointer" }}
                    >
                        Mi perfil
                    </span>
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
                            <a href="#" className="dropdown-item">
                                Cerrar sesión
                            </a>
                        </div>
                    )}
                </div>
            </div>
        </header>
    );
}
