import React from "react";

export default function headerTeacher() {
    return (
        <header className="header-container">
            <div className="header-logo">
                <span className="header-brand">ConectaFP</span>
            </div>
            <div className="header-search">
                <img
                    src="/images/search.svg"
                    alt="Buscar"
                    className="search-icon"
                />
                <input type="text" id="search" placeholder="Buscar..." />
            </div>
            <nav className="header-nav">
                <a href="/feed">
                    <img src="/images/home.svg" alt="Inicio" />
                    <span>Inicio</span>
                </a>
                <a href="/mis-tutorias">
                    <img src="/images/tutorial.svg" alt="Mis tutorías" />
                    <span>Mis tutorías</span>
                </a>
            </nav>
            <div className="header-actions">
                <a href="#" className="header-notif">
                    <img src="/images/notifications.svg" alt="Notificaciones" />
                    <span>Notificaciones</span>
                </a>
                <a href="#" className="header-profile">
                    <span className="header-avatar-initials"></span>
                    <span className="profile">Mi perfil</span>
                </a>
            </div>
        </header>
    );
}
