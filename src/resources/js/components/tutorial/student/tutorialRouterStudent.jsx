import React, { useState } from "react";
import TutorialListStudent from "./tutorialListStudent";
import { createRoot } from "react-dom/client";

export default function tutorialStudentRouter() {
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
                    className={`tutorial-tab ${activeTab === "chat" ? "active" : ""}`}
                    onClick={() => setActiveTab("chat")}
                >
                    Chat
                </button>
            </div>

            <div className="tutorial-content">
                {activeTab === "tutorias" && (
                    <div className="tutorial-section">
                        <TutorialListStudent />
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
