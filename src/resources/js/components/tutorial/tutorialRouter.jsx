import React, { useState } from "react";
import TutorialList from "./tutorialList";
import { createRoot } from "react-dom/client";

export default function TutorialRouter() {
    const [activeTab, setActiveTab] = useState("tutorias");

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
                        <TutorialList />
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
