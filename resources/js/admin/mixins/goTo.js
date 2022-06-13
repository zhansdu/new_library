export const goTo={
	methods:{
		goTo(place,props){
			if(this.$route.name!=place || this.$route.matched[0].name != place){
				this.$router.push({name:place,params:props});
				this.$emit('close');
			}
		}
	}
}
export const goToMain={
	methods:{
		goToMain(){
			window.location.replace('/')
		}
	}
}