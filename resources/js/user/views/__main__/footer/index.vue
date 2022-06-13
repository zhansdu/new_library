<template>
	<div class="w-100 bg-blue d-flex flex-column text-white bg-black">
		<div class="d-flex justify-content-between padding py-5 flex-wrap">
			<div class="text-center text-sm-left flex-grow-1 flex-sm-grow-0">
				<div class="cursor-pointer" @click="scrollTo('top')">
					<img src="/images/logo.svg" class="logo">
				</div>
				<div class="mt-5 mb-3 font-size-14 text-lightgrey">
					<div>{{$t('SDU')}}</div>
					<div class="font-weight-bold">{{$t('kaskelen')}}</div>
				</div>
			</div>
			<div class="d-none d-xl-flex flex-column">
				<a class="link mb-2" :href="link.link" :target="link.target!=undefined ? link.target : '_blank'" v-for="(link,index) in lib_links" :key="index" >
					<span v-if="link.dropdown==undefined">{{$t(link.name)}}</span>
					<span v-else>
						<dropdown class="dropup" :data="link.dropdown.links" :links="true" :title="{link:link.name}"/>
					</span>
				</a>
			</div>
			<div class="d-none d-sm-flex flex-column">
				<div class="mb-3">{{$t('contacts')}}</div>
				<div class="mb-2 text-lightgrey font-size-14" v-for="(value,key,index) in contacts" :key="index">
					<div>
						<span v-if="!(key=='place' || key=='address' || key=='city')">{{$t(key)}}: </span>{{value.val ? $t(value.key,{phone:value.val}):$t(value)}}
					</div>
				</div>
			</div>
			<div class="d-flex flex-column text-center text-sm-left flex-grow-1 flex-sm-grow-0">
				<div class="mb-3">{{$t('follow_us')}}</div>
				<div class="d-flex justify-content-around">
					<a :href="icon.link" v-for="(icon,index) in icons" :key="index" class="d-flex text-black bg-white align-items-center justify-content-center mr-3 transition icon_wrapper" target="_blank">
						<component :is="icon.component" class="icon"/>
					</a>
				</div>
			</div>
		</div>
		<div class="py-3 d-flex justify-content-center border-top border-unclear text-lightgrey">
			<div>
				{{$t('allRightsReserved',{year:year})}}
			</div>
			<div class="ml-3">
				<dropdown class="dropup" :data="langs" :on_click="setLang" menu_classes="dropdown-menu-right" :title="{link:$i18n.locale,uppercase:true}"/>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
	// mixins
	import {scrollTo} from '../../../mixins/goTo'
	import links from '../../../mixins/links'
	import langs from '../../../mixins/langs'

	// components
	import dropdown from '../../../components/dropdown'

	//icons
	import Instagram from '../../../../common/assets/icons/Instagram'
	import Facebook from '../../../../common/assets/icons/Facebook'
	import Telegram from '../../../../common/assets/icons/Telegram'
	import Youtube from '../../../../common/assets/icons/Youtube'
	export default{
		mixins:[scrollTo,links,langs],
		components:{dropdown},
		data(){
			return{
				year:new Date().getFullYear(),
				contacts:{
					place:'SDUlibrary',
					address:'SDUaddress',
					city:"kaskelen",
					phone:{key:'SDUphone',val:'+7 (727) 307 9565'},
					email:'library@sdu.edu.kz'

				},
				icons:[
				{component:Telegram,link:'https://t.me/sdu_library'},
				{component:Instagram,link:'https://instagram.com/sdu_library?igshid=1spec8pmcepnw'},
				{component:Youtube,link:'https://www.youtube.com/channel/UCmuuTTBkfi8aUgUc56VY8kA'},
				{component:Facebook,link:'https://www.facebook.com/librarysdu'}
				]
			}
		}
	}
</script>
<style scoped>
.icon_wrapper{
	width: 2.5em;
	height:2.5em;
	border-radius: 50%;
	cursor: pointer;
}
.icon{
	font-size:1.4em;
}
.icon_wrapper:hover{
	transform:rotate(180deg);
	background-color:#9E2629 !important
}
.icon_wrapper:hover > .icon{
	transform:rotate(180deg);
	font-size: 1.6em;
	color:white;
}
.border-unclear{
	border-color: #000000 !important;
}
</style>