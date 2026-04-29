import React from "react";

export default function headerCompany() {
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
                    <span>Mis ofertas</span>
                </a>
                <a href="/solicitudes">
                    <img src="/images/requests.svg" alt="Solicitudes" />
                    <span>Solicitudes</span>
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
