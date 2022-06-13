<template>
	<div class="dropdown">
		<a class="link dropdown-toggle"  data-toggle="dropdown" :class="title_classes">
			<span v-if="title.uppercase">{{$t(title.link).toUpperCase()}}</span>
			<span v-else>{{$t(title.link)}}</span>
		</a>
		<div class="dropdown-menu" :class="menu_classes">
			<a :target="links ? '_blank':''" :href="item.link" class="dropdown-item link" v-for="(item,index) in data" :key="index" @click="item_on_click(item)" v-if="!item.invisible">
				{{$t(item.name ? item.name:item)}}
			</a>
		</div>
	</div>
</template>
<script type="text/javascript">
	export default{
		props:{
			title_classes:{
				type:[String,Array]
			},
			menu_classes:{
				type:[String,Array]
			},
			data:{
				type:Array
			},
			on_click:{
				type:Function,
				default(){
					return ()=>{};
				}
			},
			title:{
				type:[Object,String]
			},
			links:{
				type:Boolean
			}
		},
		methods:{
			item_on_click(item){
				if(item.on_click){
					item.on_click();
				}
				try{
					this.on_click(item);
				}catch(e){}
			}
		}
	}
</script>
<style scoped></style>