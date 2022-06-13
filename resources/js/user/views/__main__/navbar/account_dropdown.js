import { message_success, message_error } from '../../../mixins/messages'
import { goTo } from '../../../mixins/goTo'
import { mapGetters } from 'vuex'
export default {
    mixins: [message_success, message_error, goTo],
    computed: {
        ...mapGetters(['user']),
        dropdown_links() {
            let links = [{
                    name: 'lib_office',
                    on_click: this.goToAdmin,
                    invisible: !(this.user.is_admin)
                },
                {
                    name: 'my_books',
                    on_click: () => { this.goTo('mybooks') }
                },
                {
                    name: 'logout',
                    on_click: this.logout
                }
            ];
            return links
        }
    },
    methods: {
        logout() {
            this.$store.commit('setFullPageLoading', true);
            this.$http.post('logout',{}, { api: false }).then(response => {
                this.$store.dispatch('logout');
                this.message_success('logout', response);
            }).catch(error => {
                this.message_error('logout', error);
            }).then(() => {
                this.$store.commit('setFullPageLoading', false);
                this.goTo('home');
            })
        },
        goToAdmin() {
            window.location.replace('/admin');
        }
    }
}