<template>
    <div class="padding mt-4" v-html="description"/>
</template>
<script>
import configs from '../../mixins/configs'

export default {
    props:{
        index:Number
    },
    data(){
        return{
            description:""
        }
    },
    watch:{
        '$i18n.locale'(){
            this.description=this.$t('news['+this.index+'].description');
        }
    },
    mixins:[configs],
    created(){
        this.getConfigs().then(()=>{
            this.description=this.$t('news['+this.index+'].description');

        });
        if(!this.index){
            this.index=this.$route.query.index;
        }
    }
}
</script>