export default{
	methods:{
		warn(id,show){
			var warning=document.getElementById('warn-'+id);
			if(show){
				warning.style.opacity='1';
			}
			else{
				warning.style.opacity='0';
			}
		}
	}
}
