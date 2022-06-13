<template>
    <main-body/>
</template>
<script type="text/javascript">
    import MainBody from './views/__main__'

    export default{
        components:{
            MainBody
        },
        methods:{
            setLanguage(){
                this.$i18n.locale=JSON.parse(localStorage.getItem('lang')) || window.configs.default_lang;
            },
            checkLogin(){
                this.$http.get('user',{api:false}).then(response=>{
                    this.$store.commit('setUser',response.data.res.user);
                }).catch(e=>{}).then(()=>{
                });
            }
        },
        created(){
            this.setLanguage();
            this.checkLogin();
        }
    }
</script>
<style>
@import '../common/assets/styles/colors.css';
@import '../common/assets/styles/common.css';
@import '../common/assets/styles/font.css';
@import '../common/assets/styles/sizes.css';
@import './assets/fonts/Futura/stylesheet.css';
@import './assets/styles/style.css';
</style>