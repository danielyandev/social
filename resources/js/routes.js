import Register from "./views/auth/Register";
import Login from "./views/auth/Login";
import Main from "./views/Main";


export const routes = [
    {
        name: 'main',
        path: '/',
        component: Main
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    }
];
