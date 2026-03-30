import './bootstrap';
import { createRoot } from 'react-dom/client';
import RegisterApp from './registerApp';

const registerElement = document.getElementById('register');
const headerElement = document.getElementById('header');
const footerElement = document.getElementById('footer');

if (registerElement) {
    const register = createRoot(registerElement);
    register.render(<RegisterApp />);
};
