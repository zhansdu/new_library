export default {
    data() {
        return {
            langs: [
                { lan: 'en', name: 'EN' },
                { lan: 'ru', name: 'RU' },
                { lan: 'kz', name: 'KZ' },
            ]
        }
    },
    methods: {
        setLang(lan) {
            this.$i18n.locale = lan.lan || window.configs.default_lang;
            localStorage.setItem('lang', JSON.stringify(lan.lan));
        }
    }
}