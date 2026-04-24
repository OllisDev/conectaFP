import React, { useState } from "react";
import AssignmentList from "./assignmentsList";
import CreateAssignments from "./createAssignments";

export default function assignmentsRouter() {
    const [activeTab, setActiveTab] = useState("asignaciones");

    return (
        <div className="assignments-container">
            <div className="assignments-menu">
                <button
                    className={`assignments-tab ${activeTab === "asignaciones" ? "active" : ""}`}
                    onClick={() => setActiveTab("asignaciones")}
                >
                    Mis asignaciones
                </button>
                <button
                    className={`assignments-tab ${activeTab === "crear asignacion" ? "active" : ""}`}
                    onClick={() => setActiveTab("crear asignacion")}
                >
                    Crear asignación
                </button>
            </div>

            <div className="assignments-content">
                {activeTab === "asignaciones" && (
                    <div className="assignment-section">
                        <AssignmentList />
                    </div>
                )}
                {activeTab === "crear asignacion" && (
                    <div className="assignment-section">
                        <CreateAssignments />
                    </div>
                )}
            </div>
        </div>
    );
}
