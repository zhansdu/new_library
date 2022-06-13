var setLocale = {
	methods:{
		setLocale(locale){
			locale=locale ?? window.configs.default_lang;
			this.$i18n.locale=locale;
			this.$http.defaults.headers.common['Content-Language'] = locale;
			localStorage.setItem('lang',JSON.stringify(locale));
		}
	}
};
export default setLocale;