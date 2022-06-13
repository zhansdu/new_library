<template>
	<div id="sidebar" class="transition text-white overflow-auto">
		<a href="/" class="logo d-flex align-items-center justify-content-center cursor-pointer">
			<img src="/images/logo_black.png" v-if="theme=='light'"/>
			<img src="/images/logo.png" v-else/>
		</a>
		<div class="mt-2">
			<!-- tabs of sidebar -->
			<div class="d-flex flex-column" v-for="(tab,index) in tabs" :key="index">
				<!-- parent route -->
				<div class="d-flex align-items-center justify-content-between cursor-pointer title" @click="showTab(tab)">
					<!-- the parentRoute's name. If it has no children then it acts like a link and is colored orange if it is the current route's parent -->
					<div class="transition" :class="[{link:tab.meta ?tab.meta.noChildren!=undefined :false},{current:tab.name==parentRoute.name}]">
						{{$t(tab.name)}}
					</div>
					&nbsp;
					<!-- Presented only if there are children on the current tab -->
					<ArrowDown class="transition text-white right" :class="{'rotate':tab.shown}" v-if="!(tab.meta ?tab.meta.noChildren:true)"/>
				</div>
				<!-- children routes. Presented only if there are children on the current tab -->
				<div class="hidden transition" :class="{'shown':tab.shown}" v-if="!(tab.meta ?tab.meta.noChildren:true)">
					<!-- for loop for children -->
					<!-- children is a method because we cannot get children using routes -->
					<div class="link transition d-flex justify-content-between py-2" :class="{seen:currentRoute.name==link.name}" v-for="(link,i) in tab.children.filter(tab=>tab.name!=undefined)" :key="i" @click="goTo(link.name)">
						{{$tc(link.name,10)}}
						<span class="text-black">
							<ArrowDown class="right transition" :class="{current:currentRoute.name==link.name}" />
						</span>
					</div>
				</div>
				<div class="sidebar_line mt-2" v-if="index!=tabs.length-1"/>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
import {goTo,goToMain} from '../../../mixins/goTo'

import CaretUp from '../../../../common/assets/icons/CaretUp'
import ArrowDown from '../../../../common/assets/icons/ArrowDown'
export default{
	mixins:[goTo,goToMain],
	components:{CaretUp,ArrowDown},
	computed:{
		parentRoute(){
			return this.$route.matched[0]
		},
		currentRoute(){
			return this.$route
		},
		router(){
			return this.$router
		},
		theme(){
			return this.$store.state.theme;
		},
		tabs(){
			return this.$store.state.admin.tabs;
		}
	},
	methods:{
		showTab(tab){
			if(tab.meta){
				if(tab.meta.noChildren!=undefined){
					this.goTo(tab.meta.noChildren.name);
				}
				else{
					tab.shown=!tab.shown;
				}
			}
			this.$forceUpdate();
		}
	}
}
</script>
<style scoped>
#sidebar{
	box-shadow: 0 0.125em 0.25em rgba(0, 0, 0, 0.25);
	min-width: 15.625em;
	width:20.30vw;
	height:100vh;
	background: #333333;
	z-index: 1001;
}

/*since it's the only image here*/
img{
	width:32.92%;
	min-width: 5em;
}

.hidden>div{
	cursor: pointer;
}

.logo{
	/*from navbar*/
	min-height: 5em;
	height:8.72vh;
}

.sidebar_line{
	width:100%;
	height:0.1em;
	background: rgba(255, 255, 255, 0.25);
}

.title{
	font-size: 1.20em;
	padding:0.625em 1.25em 0 1.25em;
	font-weight: 500;
}

.title>div{
	font-size: 1.18em;
	font-weight: 500;
}

.hidden{
	max-height:0;
	overflow: hidden;
}

.hidden > div{
	font-size:1.125em;
	padding:0.625em 1.25em 0.625em 2.5em;
}

.shown{
	max-height: 25em;
}

.current{
	color:#FF9D29;
}

.right{
	transform: rotate(90deg);
}

.seen{
	background-color: #444444;
}

.link:hover>.text-black{
	color:white;
}
</style>