export default {
    computed: {
        config() {
            return this.$store.getters.configs;
        }
    },
    methods: {
        getConfigs() {
            return this.$http.get('/admin/configuration').then(response => {
                this.$store.state.common.configs = response.data.res.content;
                this.setConfigLocales();
            })
        },
        setConfigLocales() {
            let config = this.$store.state.common.configs;
            let videos_t = { en: [], ru: [], kz: [] };
            for (let i = 0; i < config.videos.length; i++) {
                let video = config.videos[i];
                for (let lang in videos_t) {
                    videos_t[lang].push({ title: video['title_' + lang] });
                }
            }
            for (let lang in videos_t) {
                this.$addLang(lang, { videos: videos_t[lang] })
            }

            let news_t = { en: [], ru: [], kz: [] };
            for (let i = 0; i < config.news.length; i++) {
                let na = config.news[i];
                for (let lang in news_t) {
                    news_t[lang].push(Object.assign({}, na[lang]));
                }
            }
            for (let lang in news_t) {
                this.$addLang(lang, { news: news_t[lang] })
            }
        }
    }
}