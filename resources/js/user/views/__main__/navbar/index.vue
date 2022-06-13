<template>
	<div>
		<nav class="navbar navbar-expand-lg bg-black navbar-dark text-white padding py-0">
			
			<div class="d-flex align-items-center">
				<span class="mr-2"><clock /></span>
				<span>{{$t( 'days[' + configs.w_h.start_day + ']' ) + ' - ' + $t( 'days[' + configs.w_h.end_day + ']' ) + ' : ' + configs.w_h.start_time + ' - ' + configs.w_h.end_time }}</span>
				<!-- <div class="dot ml-3" :class="open ? 'bg-success': 'bg-danger'"></div> -->
			</div>

			
			<!-- main items -->
			<div class="collapse navbar-collapse flex-grow-0 m-auto" id="collapsibleNavbar">
				<ul class="navbar-nav">
					<li class="nav-item" v-for="(link,index) in lib_links" :key="index">
						<a :href="link.link" :target="link.target!=undefined ? link.target : '_blank'" class="nav-link link text-white text-nowrap font-weight-bold px-1 mr-2">
							<!-- if link is a dropdown -->
							<span v-if="link.dropdown==undefined">{{$t(link.name).toUpperCase()}}</span>
							<!-- in other cases -->
							<span v-else>
								<dropdown :data="link.dropdown.links" :links="true" :title="{uppercase:true,link:link.name}"/>
							</span>
						</a>
					</li>
				</ul>
			</div>
			<div class="d-flex">
				<div class="d-flex align-items-center mr-2">
					<!-- languages dropdown -->
					<dropdown :data="langs" :on_click="setLang" menu_classes="dropdown-menu-right" :title="{link:$i18n.locale,uppercase:true}"/>
					<!-- login button if ur not logged in -->
					<div class="link bg-lightblue font-size-18 py-3 px-3 ml-3"  @click="showModal(login,{width:'300px',height:'300px'})" v-if="!logged">{{$t('login')}}</div>
					<!-- dropdown, in other cases -->
					<dropdown :data="dropdown_links" class="bg-lightblue font-size-18 py-3 px-3 ml-3" :title="{link:user.name}" menu_classes="dropdown-menu-right" v-else />
				</div>	
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
					<span class="navbar-toggler-icon"></span>
				</button>
			</div>
		</nav>

 		<nav class="navbar navbar-expand-lg bg-red navbar-dark padding py-2">
			<!-- logo -->
			<router-link class="cursor-pointer" :to="'/'+$i18n.locale">
				<img class="logo" src="/images/logo.svg">
			</router-link>
			<!-- appears only on screens smaller than xl -->
			
			<!-- main items -->
			<div class="collapse navbar-collapse flex-grow-0 m-auto py-3">
				<ul class="navbar-nav">
					<div class="font-size-48 text-white text-center" v-html="$t('welcome_to_sdu')"></div>
				</ul>
			</div>
		</nav>
	</div>
</template>
<script type="text/javascript">
	// components
	import dropdown from '../../../components/dropdown'

	import login from './login'
	
	// mixins
	import links from '../../../mixins/links'
	import langs from '../../../mixins/langs'
	import modal from '../../../mixins/modal'
	import account_dropdown from './account_dropdown'

	import { mapGetters } from 'vuex'

	// icons
	import Clock from '../../../../common/assets/icons/Clock'
	
	export default{
		mixins:[links,langs,modal,account_dropdown],
		components:{
			dropdown,Clock
		},
		computed:{
			...mapGetters(['logged','user','configs']),
		},
		data(){
			return{
				login:login,
				chatShown:false
			}
		}
	}
</script>
<style scoped>
.dot{
	border-radius: 50%;
	height: .5em;
	width: .5em;
}
.slide-out{
	position:absolute;
	top:200%;
}
.shown{
	right:0 !important;
}
.hidden{
	right:-340px;
}
</style>