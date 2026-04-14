import "./bootstrap";
import { createRoot } from "react-dom/client";
import RegisterApp from "./registerApp";
import LoginApp from "./components/auth/formLogin";
import Header from "./components/header/headerRouter";

const headerElement = document.getElementById("header");
const footerElement = document.getElementById("footer");
const registerElement = document.getElementById("register");
const loginElement = document.getElementById("login");

if (headerElement) {
    const header = createRoot(headerElement);
    header.render(<Header />);
}

if (registerElement) {
    const register = createRoot(registerElement);
    register.render(<RegisterApp />);
}

if (loginElement) {
    const login = createRoot(loginElement);
    login.render(<LoginApp />);
}
