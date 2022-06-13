export default {
	methods:{
		validate(string,nullAllowed){
			if(string.replace(/\s/g, "").length>=2 || (string.length==0&&nullAllowed)){
				return true;
			}
			else{
				return false;
			}
		}
	}
}