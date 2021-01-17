import Register from "./views/auth/Register";
import Login from "./views/auth/Login";
import Main from "./views/Main";
import User from "./views/User";


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
    },
    {
        name: 'user',
        path: '/users/:id',
        component: User,
        props: true
    }
];
