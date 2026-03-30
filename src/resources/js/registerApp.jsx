import React, { useState } from "react";
import RoleCard from './components/roleCard';
import FormStudent from './components/formStudent';
import FormTeacher from './components/formTeacher';
import FormCompany from './components/formCompany';

export default function RegisterApp() {
    const [step, setStep] = useState(1); // estado en que paso estamos (1 => rol, 2 => formulario)
    const [role, setRole] = useState(null); // estado para saber el rol elegido

    const handleNext = () => {
        if (!role) {
            alert("Selecciona un rol");
            return;
        }
        setStep(2);
    };

    const handleBack = () => {
        setStep(1);
    };

    if (step === 1) {
        return (
            <div id="role">
                <h1>¿Como vas a usar ConectaFP?</h1>
                <RoleCard text={"Alumno"} image={""} image_alt={"Alumno"} />
                <RoleCard text={"Profesor"} image={""} image_alt={"Profesor"} />
                <RoleCard text={"Empresa"} image={""} image_alt={"Empresa"} />
            </div>
        );
    }

    if (step === 2) {
        if (role === 'alumno') {
            return <FormStudent onBack={handleBack} />;
        }

        if (role === 'profesor') {
            return <FormTeacher onBack={handleBack} />;
        }

        if (role === 'empresa') {
            return <FormCompany onBack={handleBack} />;
        }
    }
}