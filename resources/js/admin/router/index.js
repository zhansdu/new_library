import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes';

Vue.use(VueRouter)

const router = new VueRouter({
	mode: 'history',
	base:'/admin/',
	routes,
});

Vue.prototype.$replaceRoutes=(routes)=>{
	router.routes=routes
}

export default router;
