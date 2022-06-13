export const message_success={
	methods:{
		message_success(title,response){
			let message=this.$t('success');
			if(Object.keys(response).length>0){
				message=response.data.res ? (response.data.res.message ?? this.$t('success') ) : this.$t('success');
			}
			this.$fire({
				title:this.$t(title),
				text:message,
				type:"success",
				timer:1700
			});;
		}
	}
}
export const message_error={
	methods:{
		message_error(title,error){
			let message=this.$t('error');
			try{
				if(Object.keys(error).length>0){
					if(typeof error === 'object'){
						message=error.response.data ? ((error.response.data.message ?? error.response.data.errors.message) ?? this.$t('error')) : this.$t('error');
					}
					else{
						message=this.$t(error);
					}
				}
			}catch(e){
				message=this.$t('error')
			}
			this.$fire({
				title:this.$t(title),
				text:message,
				type:"error",
			});;
		}
	}
}