import React, { useState } from "react";
import RoleCard from './components/roleCard';
import FormStudent from './components/formStudent';
import FormTeacher from './components/formTeacher';
import FormCompany from './components/formCompany';

export default function RegisterApp() {
    const [step, setStep] = useState(1); // estado en que paso estamos (1 => rol, 2 => formulario)
    const [role, setRole] = useState(null); // estado para saber el rol elegido

    const handleSelectRole = (selectedRole) => {
        setRole(selectedRole.toLowerCase());
        setStep(2);
    };

    const handleBack = () => {
        setStep(1);
    };

    if (step === 1) {
        return (
            <div id="role">
                <h1 className="title-conectafp">¿Cómo vas a usar ConectaFP?</h1>
                <div id="role-cards">
                    <RoleCard text={"Alumno"} class_name={"role-student"} image={"images/student.svg"} image_alt={"Alumno"} handle_next={() => handleSelectRole('alumno')} />
                    <RoleCard text={"Profesor"}  class_name={"role-teacher"} image={"images/teacher.svg"} image_alt={"Profesor"} handle_next={() => handleSelectRole('profesor')} />
                    <RoleCard text={"Empresa"}  class_name={"role-company"} image={"images/company.svg"} image_alt={"Empresa"} handle_next={() => handleSelectRole('empresa')} />
                </div>
                
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