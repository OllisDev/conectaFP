import React from "react";

export default function roleCard({text, class_name, image, image_alt, handle_next}) {
    return (
        <div className={class_name} onClick={handle_next}>
            <img src={image} alt={image_alt} height="30" width="30"></img>
            <h1>{text}</h1>
        </div>
    )
}