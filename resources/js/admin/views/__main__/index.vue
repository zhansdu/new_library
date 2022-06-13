<template>
	<div id="app" class="d-flex">
		<!-- full page loader -->
		<div class="loader" v-if="$store.getters.fullPageLoading">
			<clip-loader size="6.25em" color="#a4d9f6" borderWidth='0.5em'/>
		</div>

		<sidebar />

		<div id="body" class="transition d-flex flex-column w-100">
			<navbar />
			<div id="main" class="bg-grey h-100">
				<router-view :key="$route.fullPath" class="h-min-100" />
			</div>
		</div>

	</div>
</template>
<script type="text/javascript">
// importing bars
// all App related stuff -> things that are common for all templates -> they're located in components/App
import sidebar from './sidebar'
import navbar from './navbar'

// loading indicator
import ClipLoader from 'vue-spinner/src/ClipLoader'

export default {
	components:{
		sidebar,
		navbar,
		ClipLoader
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
	height:100vh;
	width:100vw;
	background-color: rgba(0,0,0,0.30);
	z-index: 1000;
}
</style>