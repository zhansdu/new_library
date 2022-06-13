export default{
	emptyFilters({state}){
		state.filter_search={
			types:[],
			languages:[],
			years:''
		}
	},
	setSearches({commit},response){
		commit('setResults',response.data.res);
		commit('setFilter',response.data.filter);
		commit('setAllData',response.data.all);
	}
}