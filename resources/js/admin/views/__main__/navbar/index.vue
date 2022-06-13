<template>
	<div id="navbar" class="d-flex align-items-center justify-content-between">
		
		<div class="d-flex align-items-center">
			<!-- hide sidebar button -->
			<div class="icon mr-5 cursor-pointer" @click="hideSidebar()"><Bars /></div>
			
			<!-- those boxes that appear when u click on the navbar link -->
			<div class="d-flex align-items-center font-size-20">
				<!-- first dropdown -> route parents -->
				<div class="position-relative" @focusout="first_shown=false" tabindex="2">
					<!-- the parent route name -->
					<!-- onclick it will show the first dropdown ( show (0 - > first ) ) -->
					<span class="link font-weight-bold" @click="show(0)">{{$t(parentRoute.name)}}</span>

					<div class="dropdown rounded-lg bg-white shadow-sm pl-3 mt-3" :class="{shown:first_shown}">
						<!-- if you want to hide a route - you should just delete it's name or give it noTab-->
						<div class="link" v-for="(route,index) in allParentRoutes.filter(route=>!(route.name==undefined || (route.meta ? route.meta.noTab : false)))" :key="index" @click="linkGoTo(route.name,0)">
							{{$t(route.name)}}
						</div>

					</div>
				</div>
				<!-- the divident that looks like this " > " -->
				<!-- is shown everytime except if the route has no children -->
				<span class="ml-2" v-if="!(parentRoute.meta.noChildren || currentRoute.meta.noChildren)"><ArrowDown class="left"/></span>

				<!-- second dropdown -> route children -->
				<!-- is shown everytime except if the route has no children -->
				<div class="position-relative ml-2" @focusout="second_shown=false" tabindex="3" v-if="!(parentRoute.meta.noChildren || currentRoute.meta.noChildren)">
					<!-- the current route name -->
					<!-- onclick it will show the second dropdown ( show (1 - > second ) ) -->
					<span class="link font-weight-bold" @click="show(1)">{{$tc(currentRoute.name,10)}}</span>

					<div class="dropdown rounded-lg bg-white shadow-sm pl-3 mt-3" :class="{shown:second_shown}">
						<div class="link" v-for="(route,index) in allParentRoutes.filter(route=>route.name==parentRoute.name)[0].children" :key="index" @click="linkGoTo(route.name,1)">
							{{$tc(route.name,10)}}
						</div>
					</div>
				</div>
			</div>

		</div>
		
		<div class="d-flex align-items-center">
			<!-- languages dropdown -->
			<dropdown :itemOnClick="lanOnClick" :title="$i18n.locale.toUpperCase()" :items="languages" dropdownStyles="overflow:hidden;"/>
			<account-dropdown class="ml-2"/>
		</div>

	</div>
</template>
<script>
// components
import Dropdown from '../../../components/Dropdown'
import AccountDropdown from './account'

// icons
import Bars from '../../../../common/assets/icons/Bars'
import ArrowDown from '../../../../common/assets/icons/ArrowDown'

// mixins
import {goTo} from '../../../mixins/goTo'
import setLocale from '../../../mixins/setLocale'

export default {
	mixins:[goTo,setLocale],
	components:{Dropdown,AccountDropdown,Bars,ArrowDown},
	computed:{
		allParentRoutes(){
			return this.$store.state.admin.tabs;
		},
		parentRoute(){
			return this.$route.matched[0];
		},
		currentRoute(){
			return this.$route;
		}
	},
	data(){
		return{
			first_shown:false,
			second_shown:false,
			languages:[{name:'EN',locale:'en'},
			{name:'RU',locale:'ru'},{name:'KZ',locale:'kz'}]
		}
	},
	methods:{
		hideSidebar(){
			var sidebar=document.getElementById('sidebar');
			var main=document.getElementById('main');
			if(sidebar.offsetWidth==0){
				sidebar.classList.remove('text-no-wrap','sidebar_hidden');
				main.classList.remove('main_shown')
			}
			else{
				sidebar.classList.add('text-no-wrap','sidebar_hidden');
				main.classList.add('main_shwon')
			}
		},
		show(n){
			switch(n){
				case 0:
				this.first_shown=!this.first_shown
				break;
				case 1:
				this.second_shown=!this.second_shown
				break

			}
		},
		linkGoTo(link,n){
			try{
				this.goTo(link);
			}catch(e){}
			this.show(n);
		},

		// change language
		lanOnClick(locale){
			this.setLocale(locale.locale);
			this.$http.get('/locale/'+this.$i18n.locale);;
		}
	}
}
</script>
<style scoped>
#navbar{
	box-shadow: inset 0em -0.125em  rgba(0, 0, 0, 0.25);
	min-height: 5em;
	height:8.72vh;
	z-index: 1000;
	padding: 1.5625em;
}
.icon{
	padding:0.3125em 0.46875em;
	font-size: 1.73em;
}

.margin-right{
	/*at least ~20 px*/
	margin-right:max(2.7vh,1.25em);
}

.left{
	transform: rotate(90deg);
}

.shown{
	max-height: 11.25em;
}

.link{
	margin: 1em 0;
}

</style>