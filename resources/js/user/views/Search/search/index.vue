<template>
	<div>
		<tabs :components="components" class="position-relative" :tabClasses="tabClasses" :tabsClasses="'d-flex justify-content-end'" tabActiveClasses="text-red"/>
	</div>
</template>
<script type="text/javascript">
// components
import tabs from '../../../../common/components/Tabs'
import gen_col from './general_collection'
import e_resources from './e_resources'

export default{
	components:{tabs},
	data(){
		return{
			components:[
			{name:'books&media',component:gen_col},
			{name:'eresources',component:e_resources}
			]
		}
	},
	computed:{
		tabClasses(){
			return "font-size-24 font-weight-bold mr-4"+(this.$store.getters.theme=="light" ? " text-lightgrey":" text-black")
		}
	}
}
</script>