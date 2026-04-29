import React from "react";

export default function feedCompany() {
    const token = localStorage.getItem("api_token");
    if (!token) {
        window.location.href = "/login";
        return null;
    }

    return (
        <div className="feed-container">
            <div className="process-container">
                <img
                    src="/images/process.svg"
                    alt="Proceso"
                    className="process-image"
                ></img>
                <span className="process-span">En construcción...</span>
            </div>
        </div>
    );
}
