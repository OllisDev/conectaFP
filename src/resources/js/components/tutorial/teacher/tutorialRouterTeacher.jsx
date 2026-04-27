import React, { useState } from "react";
import TutorialListTeacher from "./tutorialListTeacher";
import CreateTutorial from "./createTutorial";

export default function tutorialRouterTeacher() {
    const [activeTab, setActiveTab] = useState("tutorias");
    const token = localStorage.getItem("api_token");
    const userStr = localStorage.getItem("user");
    if (!token || !userStr) {
        window.location.href = "/login";
        return null; // Evita renderizar el componente
    }

    return (
        <div className="tutorial-container">
            <div className="tutorial-menu">
                <button
                    className={`tutorial-tab ${activeTab === "tutorias" ? "active" : ""}`}
                    onClick={() => setActiveTab("tutorias")}
                >
                    Mis tutorías
                </button>
                <button
                    className={`tutorial-tab ${activeTab === "crear tutoria" ? "active" : ""}`}
                    onClick={() => setActiveTab("crear tutoria")}
                >
                    Crear tutoría
                </button>
                <button
                    className={`tutorial-tab ${activeTab === "chat" ? "active" : ""}`}
                    onClick={() => setActiveTab("chat")}
                >
                    Chat
                </button>
            </div>

            <div className="tutorial-content">
                {activeTab === "tutorias" && (
                    <div className="tutorial-section">
                        <TutorialListTeacher />
                    </div>
                )}
                {activeTab === "crear tutoria" && (
                    <div className="tutorial-section">
                        <CreateTutorial />
                    </div>
                )}
                {activeTab === "chat" && (
                    <div className="tutorial-section">
                        <p>Proximamente...</p>
                    </div>
                )}
            </div>
        </div>
    );
}
