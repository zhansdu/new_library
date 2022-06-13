import { message_success, message_error } from './messages'
export const loadData = {
    mixins: [message_error],
    methods: {
        loadData() {
            let store = this.$store.state.website;
            if (!store.fetched) {
                this.$store.commit('setFullPageLoading', true);
                this.$http.get('/admin/configuration').then(response => {
                    store.data = response.data.res.content;
                    if (!store.data.news) {
                        store.data.news = []
                    }
                    if (!store.data.videos) {
                        store.data.videos = []
                    }
                    if (!store.data.w_h) {
                        store.data.w_h = {}
                    }

                    store.fetched = true;
                }).catch(error => {
                    this.message_error('get_data', error);
                }).then(() => {
                    this.$store.commit('setFullPageLoading', false);
                });
            }
        },
    },
    created() {
        this.loadData();
    }
}
export const save = {
    mixins: [message_success, message_error],
    methods: {
        save(na) {
            let content = this.$store.getters.website.data;

            console.log(content.w_h)
            
            if(na.editing_lan){
                content.news.push(na)
            }

            // content.news.sort((a,b)=>{
            //     let c = new Date(a.create_date).getTime();
            //     let d = new Date(b.create_date).getTime();
            //     // console.log(c)
            //     // if ( c > d ){
            //     //     return -1;
            //     // }
            //     // if ( c < d ){
            //     //     return 1;
            //     // }
            //     // return d-c;
            //     if (d === c) 
            //         return 0;

            //     return d < c ? -1 : 1;
            // })

            content.news.forEach(elem => {
                elem.editing_lan = "en";
                if (elem.editing) {
                    elem.edit_date = new Date();
                    elem.edit_acount = this.$store.getters.user;
                    elem.editing = false;
                }
                elem.extending=false
            })

            this.$store.commit('setFullPageLoading', true);
            this.$http.post('/admin/configuration', { content: content }).then(response => {
                this.message_success('save', response)
            }).catch(error => {
                this.message_error('save', error);
            }).then(() => {
                this.$store.commit('setFullPageLoading', false);
            });
        }
    }
}