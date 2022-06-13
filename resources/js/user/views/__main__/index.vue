<template>
	<div id="app" class="d-flex flex-column">
		<div id="top"></div>
		<navbar />		
		<router-view />
		<footer-div class="mt-auto" v-if="$route.meta.footer" />

		<!-- fixed stuff -->
		<div class="loader" v-if="$store.getters.loading">
			<half-circle-spinner
			:animation-duration="1000"
			:size="80"
			class="spinner"
			color="#FF9D29"
			/>
		</div>
	</div>
</template>
<script type="text/javascript">
	import Navbar from './navbar'
	import FooterDiv from './footer'

	// loading indicator
	import HalfCircleSpinner from 'epic-spinners/src/components/lib/HalfCircleSpinner'

	export default{
		components:{
			FooterDiv,
			Navbar,
			HalfCircleSpinner
		}
	}
</script>
<style scoped>
.loader{
	display: flex;
	align-items: center;
	justify-content: center;
	
	position: fixed;
	top:0;
	z-index: 2000;
	width:100%;
	height:100%;
	background-color: rgba(0,0,0,0.45);
}
.spinner{
	pointer-events: none;
}
</style>