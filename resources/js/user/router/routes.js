import Home from "../views/Home";

import Search from "../views/Search";

import Mybooks from "../views/Mybooks";

import Event_full from '../views/Home/'
export default [{
        path: '/',
        component: Home,
        name: 'home',
        meta: {
            footer: true
        }
    },
    {
        path: '/search',
        component: Search,
        name: 'search'
    },
    {
        path: '/mybooks',
        name: 'mybooks',
        component: Mybooks
    },
    {
        path: '/book_full',
        name: 'full_book',
        component: () =>
            import ('../views/Full/Book')
    },
    {
        path: '/event_full',
        name: 'full_event',
        props: true,
        component: () =>
            import ('../views/Full/Event')
    },
    {
        path: '*',
        redirect: { name: 'home' }
    }
];