<template>
	<div class="d-flex">
		<div class="border border-grey rounded-lg text-grey d-flex align-items-center cursor-pointer" :class="classes" @click="goBack()">
			<div class="rotate"><Right/></div>
			&nbsp;{{$t('back')}}
		</div>
	</div>
</template>
<script type="text/javascript">
// icons
import Right from '../../common/assets/icons/RightLittle'
export default{
	props:{
		classes:[Array,Object,String]
	},
	components:{Right},
	methods:{
		goBack(){
			this.$router.go(-1);
		}
	}
}
</script>
<style scoped>
.border-grey{
	padding:0.3125em 0.625em;
}
</style>