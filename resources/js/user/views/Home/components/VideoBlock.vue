<template>
    <div class="rounded border border-black p-3 bg-white fixed-size">
        <a class="cursor-pointer text-black" :href="video.link" target="_blank">{{$t('videos['+num+'].title')}}</a>
        <div class="height-1 bg-blue w-100 mt-3"></div>

        <iframe class="video-size mt-3"
        :src="video.link_embed"
        frameborder="0" 
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen 
        v-if="videoShown"/>
    </div> 
</template>
<script>
import Play from '../../../../common/assets/icons/Play'
import X from '../../../../common/assets/icons/X'
export default {
    props:{
        video:[Object,String],
        num:[Number,String]
    },
    components:{
        Play,X
    },
    data(){
        return{
            videoShown:true
        }
    },
    methods:{
        showVideo(){
            this.videoShown=!this.videoShown;
        }
    }
}
</script>
<style scoped>
.play_icon_p{
    min-height: 2.5em;
    max-height: 2.5em;
    min-width:2.5em;
    max-width:2.5em;
    background-color: #FF9D29;
    border-radius:50%;
    position: relative;
}
.play_icon{
    position:absolute;
    top:50%;
    left:50%;

    -webkit-transform: translate(-50%, -50%);
    -moz-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    -o-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
}
.fixed-size{
    width:25vw;
    min-width: 18.75em;
}
.video-size{
    width:max(100%, 25em);
    max-width:100%;
    height:12.5em;
}
</style>