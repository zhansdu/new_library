// acquisitions
import Batches from '../views/Acquisitions/Batches/Batches'
import Supplier from '../views/Acquisitions/Supplier/Supplier'
import Items from '../views/Acquisitions/Items/Items'
import Print from '../views/Acquisitions/Items/Print'
import Publisher from '../views/Acquisitions/Publisher/Publisher'

// reports
// no lazy load -> to use lazy load just comment out these two routes in here and in routes
// lazy load future -- >  is good when u don't want to load a page in the beginning but only want to load when u need it
import Attendance from '../views/Reports/Attendance/Attendance'
import BooksHistory from '../views/Reports/BooksHistory/BooksHistory'
import InventoryBooks from '../views/Reports/InventoryNumber/InventoryNumber'
import MRBooks from '../views/Reports/MostReadBooks/Books'
import KSU from '../views/Reports/KSU/KSU'
import Report from '../views/Reports/Report/Report'

// service_desk
import Users from '../views/Service_desk/Users/Users'
import Info from '../views/Service_desk/Users/Info'
import Service from '../views/Service_desk/Service/Service'

// catalogin
import CatalogingSearch from '../views/Cataloging/Search/Search'
import CatalogingEdit from '../views/Cataloging/Edit/Edit'

// settings
import Settings from '../views/Settings/Main'

// webiste settings
import NA from '../views/Website/N&A'
import VideoContent from '../views/Website/VideoContent'
import WorkingHours from '../views/Website/WorkingHours'

//administration
import Admin from '../views/Admin/Main'
import Control from '../views/Admin/ControlPage'
import AddPermission from '../views/Admin/AddPermissionPage'

// router
import Router from './Router'

export default [{
        path: '/',
        redirect: { name: 'acquisitions' }
    },
    {
        path: '/acquisitions',
        name: 'acquisitions',
        redirect: 'acquisitions/batches',
        meta:{
            shown:true,
        },
        component: Router,
        children: [{
                path: 'batches',
                name: 'batches',
                component: Batches
            },
            {
                path: 'items',
                name: 'items',
                component: Items
            },
            {
                path: 'suppliers',
                name: 'suppliers',
                component: Supplier
            },
            {
                path: 'publishers',
                name: 'publishers',
                component: Publisher
            },
            {
                path: 'print',
                component: Print,
            }
        ]
    },
    {
        path: '/reports',
        name: 'reports',
        redirect: 'reports/attendance',
        meta:{
            shown:true,
        },
        component: Router,
        children: [{
                path: 'attendance',
                name: 'attendance',
                component: Attendance
                    // lazy load solution so that at the beginning the loading is faster ... but loading this ATTENDANCE component would take some time
                    // component:()=>import ('../views/Reports/Attendance/Attendance')
            },
            {
                path: 'most_read_books',
                name: 'mrbooks',
                component: MRBooks
            },
            {
                path: 'history_books',
                name: 'books_history',
                component: BooksHistory
            },
            {
                path: 'inventory_books',
                name: 'inventory_books',
                component: InventoryBooks
            },
            {
                path: 'ksu',
                name: 'ksu',
                component: KSU
            },
            {
                path: 'report',
                name: 'report',
                component: Report
            }
        ]
    },
    {
        path: '/service',
        name: 'service_desk',
        redirect: 'service/users',
        meta: {
            shown:true,
            // if there are no children then the default route would be the only route u can enter by navbar or sidebar
            noChildren: {
                name:"users"
            }
        },
        component: Router,
        children: [{
                path: 'users',
                name: 'users',
                component: Users
            },
            {
                path: 'info',
                component: Info
            },
            {
                path: 'service',
                name: 'service',
                props: true,
                component: Service
            }
        ]
    },
    {
        path: '/cataloging',
        name: 'cataloging',
        redirect: 'cataloging/search',
        meta: {
            shown:true,
            noChildren: {
                name:"cataloging_search"
            }
        },
        component: Router,
        children: [{
                path: 'search',
                name: 'cataloging_search',
                component: CatalogingSearch
            },
            {
                path: 'edit',
                props: true,
                name: 'cataloging_edit',
                component: CatalogingEdit
            }
        ]
    },
    {
        path: '/settings',
        name: 'settings',
        redirect: 'settings/main',
        meta: {
            shown:false,
            noTab: true
        },
        component: Router,
        children: [{
            path: 'main',
            name: 'settings_main',
            component: Settings
        }]
    },
    {
        path: '/website',
        name: 'website',
        redirect: 'website/video_content',
        meta:{
            shown:true,
        },
        component: Router,
        children: [{
            path: 'video_content',
            name: 'video_content',
            component: VideoContent
        }, {
            path: 'n_a',
            name: 'n_a',
            component: NA
        }, {
            path: 'working_hours',
            name: 'working_hours',
            component: WorkingHours
        }]
    },
    {
        path: '/administration',
        name: 'main-admin',
        redirect: 'administration/main',
        meta:{
            shown:true,
            noChildren:{
                name:"administration_main"
            }
        },
        component: Router,
        children: [{
            path: 'main',
            name: 'administration_main',
            component: Admin
        },{
            path: 'control',
            props:true,
            name: 'administration_control',
            component: Control
        },{
            path: 'add',
            props:true,
            name: 'administration_add_permission',
            component: AddPermission
        }]
    },
    {
        path: '*',
        redirect: '/'
    }
];