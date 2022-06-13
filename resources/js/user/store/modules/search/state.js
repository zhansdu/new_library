export default{
	// request to delete in autocomplete
	request:{},
	// request u make when searching
	search_request:[],
	// query u show u were looking for in searching page
	query:'',
	// boolean that shows if ur searching ( in search page books appear only if ur searching)
	searching:false,
	// actual search results
	results:{},
	// all results u get from da search ( needed for export excel )
	all_results:[],
	// all filter results
	filter_data:{},
	// filters u wanna apply when search
	filter_search:{
		types:[],
		languages:[],
		year:''
	},
	// position of square in pagination 
	wrapper_index:0,
	// for export to excel
	selected:{
		data:[],
		all:false
	}
}