import "./bootstrap";
import { createRoot } from "react-dom/client";
import RegisterApp from "./registerApp";
import LoginApp from "./components/formLogin";

const headerElement = document.getElementById("header");
const footerElement = document.getElementById("footer");
const registerElement = document.getElementById("register");
const loginElement = document.getElementById("login");

if (registerElement) {
    const register = createRoot(registerElement);
    register.render(<RegisterApp />);
}

if (loginElement) {
    const login = createRoot(loginElement);
    login.render(<LoginApp />);
}
