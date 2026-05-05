import "./bootstrap";
import { createRoot } from "react-dom/client";
import RegisterApp from "./registerApp";
import LoginApp from "./components/auth/formLogin";
import Header from "./components/header/headerRouter";
import Feed from "./components/feed/feedRouter";
import Offers from "./components/offers/offersRouter";
import MyRequests from "./components/requests/myRequestsRouter";
import TutorialStudent from "./components/tutorial/student/tutorialRouterStudent";
import TutorialTeacher from "./components/tutorial/teacher/tutorialRouterTeacher";

// buscar elementos en el DOM donde monstar cada uno de los componentes React
const headerElement = document.getElementById("header");
const footerElement = document.getElementById("footer");
const registerElement = document.getElementById("register");
const loginElement = document.getElementById("login");
const feedElement = document.getElementById("feed");
const offersElement = document.getElementById("offers");
const myRequestsElement = document.getElementById("myRequests");
const tutorialStudentElement = document.getElementById("tutorialStudent");
const tutorialTeacherElement = document.getElementById("tutorialTeacher");

// Solo se monta el componente si el elemento existe en el DOM

// encabezado dinámico
if (headerElement) {
    const header = createRoot(headerElement);
    header.render(<Header />);
}

// aplicación de registro (/register)
if (registerElement) {
    const register = createRoot(registerElement);
    register.render(<RegisterApp />);
}

// aplicación de login (/login)
if (loginElement) {
    const login = createRoot(loginElement);
    login.render(<LoginApp />);
}

// aplicación del feed (/feed)
if (feedElement) {
    const feed = createRoot(feedElement);
    feed.render(<Feed />);
}

// gestión de ofertas de prácticas (/ofertas)
if (offersElement) {
    const offers = createRoot(offersElement);
    offers.render(<Offers />);
}

// gestión de solicitudes (/mis-solicitudes)
if (myRequestsElement) {
    const myRequests = createRoot(myRequestsElement);
    myRequests.render(<MyRequests />);
}

// tutorias para estudiantes (/mi-tutoria)
if (tutorialStudentElement) {
    const tutorialStudent = createRoot(tutorialStudentElement);
    tutorialStudent.render(<TutorialStudent />);
}

// tutorias para profesores (/mis-tutorias)
if (tutorialTeacherElement) {
    const tutorialTeacher = createRoot(tutorialTeacherElement);
    tutorialTeacher.render(<TutorialTeacher />);
}
