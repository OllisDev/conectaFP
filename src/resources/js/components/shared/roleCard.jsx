import React from "react";

/**
 * tarjeta de selección de rol para el proceso de registro
 * @param {string} text - texto a mostrar en la tarjeta
 * @param {string} class_name - clase CSS específica para estilos del rol
 * @param {string} image - ruta de la imagen/icono del rol
 * @param {string} image_alt - texto alternativo para accesibilidad
 * @param {Function} handle_next - función a ejecutar cuando se hace clic en la tarjeta
 */
export default function roleCard({
    text,
    class_name,
    image,
    image_alt,
    handle_next,
}) {
    return (
        <div className={class_name} onClick={handle_next}>
            <img src={image} alt={image_alt} height="30" width="30"></img>
            <h1>{text}</h1>
        </div>
    );
}
