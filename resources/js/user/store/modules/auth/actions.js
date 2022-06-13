export default{
	login({commit},data){
		commit('setUser',data.user);
		localStorage.setItem('access_token',data.access_token);
	},
	logout({commit}){
		commit('setUser','');
		localStorage.removeItem('access_token');
	}
}