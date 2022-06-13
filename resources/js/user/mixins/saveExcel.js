export default{
	methods:{
		saveExcel(){
			this.$store.commit('setFullPageLoading',true);
			let media={media:this.selected.data};
			this.$http.post('media/save-excel', media,{
				responseType: 'blob',
			}).then((res) => {
				const url = window.URL.createObjectURL(new Blob([res.data]));
				const link = document.createElement('a');
				link.href = url;
				let now=new Date;
				let name='books';
				let extension='xlsx';

				name+='_'+now.getFullYear()+'.'+now.getMonth()+'.'+now.getDate()+'_'+now.getHours()+'.'+now.getMinutes() +'.'+extension;

				link.setAttribute('download', name);
				document.querySelector('#app').appendChild(link);
				link.click();
			}).catch((err) => {
				let text=err.message ?? this.$t('error');
				if(media.media.length==0){
					text="Nothing selected. Please, select the media you want to save as data in excel file";
				}
				this.$fire({
					title:this.$t("download"),
					text:text,
					type:"error"
				});;
			}).then(()=>{
				this.$store.commit('setFullPageLoading',false);
			})

		},
		selectAll(){
			let data=this.selected;
			let all=this.$store.getters.all_results;
			if(data.data.length>0){
				data.data=[]
				data.all=false;
			}
			else{
				data.data=JSON.parse(JSON.stringify(all));
				data.all=true;
			}
		}
	}
}