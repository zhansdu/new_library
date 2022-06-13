<template>
    <main-body/>
</template>
<script>
import MainBody from './views/__main__'
import setLocale from './mixins/setLocale'
import {setTheme} from './mixins/settings'
import routes from './router/routes'
import {goTo} from './mixins/goTo'
export default {
    components:{
        MainBody
    },
    mixins:[setLocale,setTheme,goTo],
    methods:{
        filter_routes(){
			this.tabs=routes.filter((route)=>{
				route.shown=this.$route.matched[0] ? this.$route.matched[0].name==route.name : false;
				return route.name!=null && route.meta.shown;
			})
		},
		getModules(){
            this.$store.commit('setFullPageLoading',true)
			this.$http.get('manage/users/'+this.$store.state.user.user_cid+'/visualization').then(response=>response.data.res).then(modules=>{
				let tabs=this.tabs.filter(route=>{
                    route.children=route.children.filter(child=>modules.some(module=>{
                        if(module.route_name=="website"){
                            return true
                        }
                        return child.name==module.route_name
                    }));
					return route.children.length>0 || modules.some(module=>module.route_name==route.name);
				});
				this.$store.dispatch('setStore',{label:'admin',data:{modules:modules,tabs:tabs}});
                this.$replaceRoutes(tabs);
                this.goTo(tabs[0].name);
			}).then(()=>{
                this.$store.commit('setFullPageLoading',false)
            })
		},
        getAuth(){
            // checks if the user is logined
            this.$http.get('/user').then(response => {
                this.$store.commit('setUser',response.data.res.user);
            }).catch(e=>{}).then(()=>{
                this.filter_routes();
                this.getModules();
            });
        },
        setGlobalLocale(){
            this.setLocale(JSON.parse(localStorage.getItem('lang')));
        }
    },
    created(){
        this.setGlobalLocale();
        
        this.getAuth();
    },
    mounted(){
        this.setTheme(localStorage.getItem('theme'));
    }
}
</script>
<style scoped>
@import '../common/assets/styles/colors.css';
@import '../common/assets/styles/common.css';
@import '../common/assets/styles/font.css';
@import '../common/assets/styles/sizes.css';
@import './assets/styles/style.css';
</style>