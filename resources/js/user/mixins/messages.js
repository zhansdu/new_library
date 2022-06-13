export const message_success={
	methods:{
		message_success(title,response){
			let message=response.data.res ? (response.data.res.message ?? this.$t('success') ) : this.$t('success');
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
			let message=error.response.data ? (error.response.data.message ?? this.$t('error')) : this.$t('error');
			this.$fire({
				title:this.$t(title),
				text:message,
				type:"error",
			});;
		}
	}
}