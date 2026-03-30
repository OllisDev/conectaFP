import React from "react";

export default function roleCard({text, image, image_alt}) {
    return (
        <div className="role">
            <h1>{text}</h1>
            <img src={image} alt={image_alt}></img>
        </div>
    )
}