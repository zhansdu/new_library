export default{
	user:state=>state.user,
	logged:state=>state.user ? Object.keys(state.user).length>0 : false,
	access_token:(state)=>{
		let token=localStorage.getItem('access_token');
		if(state.user!=null){
			if(state.user.access_token){
				token=state.user.access_token
			}
		}
		return token
	}
}
