import "./bootstrap";
import { createRoot } from "react-dom/client";
import RegisterApp from "./registerApp";
import LoginApp from "./components/auth/formLogin";
import Header from "./components/header/headerRouter";
import Feed from "./components/feed/feedRouter";
import Offers from "./components/offers/offersRouter";
import MyRequests from "./components/shared/myRequests";
import Tutorial from "./components/tutorial/tutorialRouter";

const headerElement = document.getElementById("header");
const footerElement = document.getElementById("footer");
const registerElement = document.getElementById("register");
const loginElement = document.getElementById("login");
const feedElement = document.getElementById("feed");
const offersElement = document.getElementById("offers");
const myRequestsElement = document.getElementById("myRequests");
const tutorialElement = document.getElementById("tutorial");

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

if (feedElement) {
    const feed = createRoot(feedElement);
    feed.render(<Feed />);
}

if (offersElement) {
    const offers = createRoot(offersElement);
    offers.render(<Offers />);
}

if (myRequestsElement) {
    const myRequests = createRoot(myRequestsElement);
    myRequests.render(<MyRequests />);
}

if (tutorialElement) {
    const tutorial = createRoot(tutorialElement);
    tutorial.render(<Tutorial />);
}
