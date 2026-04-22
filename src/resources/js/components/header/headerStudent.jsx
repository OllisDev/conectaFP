import React from "react";

export default function headerStudent() {
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
                ></img>
                <input
                    type="text"
                    id="search"
                    placeholder="Buscar ofertas..."
                />
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
                    <img src="/images/requests.svg" alt="Mis solicitudes" />
                    <span>Mis solicitudes</span>
                </a>
                <a href="/mi-tutoria">
                    <img src="/images/tutorial.svg" alt="Mi tutoría" />
                    <span>Mi tutoría</span>
                </a>
            </nav>
            <div className="header-actions">
                <a href="#" className="header-notif">
                    <img
                        src="/images/notifications.svg"
                        alt="Notificaciones"
                    ></img>
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
