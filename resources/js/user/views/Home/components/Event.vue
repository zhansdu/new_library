<template>
    <div class="bg-transparent border border-grey rounded flex-shrink-0 transition font-size-18 p-3 bg-image" :class="index==events.length-1 ? '' : 'mr-2'" :style="{width:itemWidth+ 'px',backgroundImage:'url('+event.img.data+')'}">
        <div class="d-flex">
            <div class="mr-5 d-flex flex-column">
                <span class="font-size-32 m-auto">{{event.date.getDate()}}</span>
                <span class="mt-2">{{$t('months['+event.date.getMonth()+']')+ ', ' + event.date.getFullYear()}}</span>
            </div>
            <div class="text-grey d-flex flex-column justify-content-around">
                <div>{{$t('news['+index+'].place')}}</div>
                <div class="d-flex">{{event.time_from }}
                    <span v-if="event.time_until">
                        &nbsp;
                        {{'- ' + event.time_until}}
                        <span v-if="event.date_until">{{' ('+event.date_until.getDate()+' '+$t('months['+event.date_until.getMonth()+']')+')'}}</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="bg-orange my-3 height-1"></div>
        <div>
            <div class="text-grey">{{$t(event.type)}}</div>
            <router-link
                :to="{name:'full_event',params:{index:index},query:{index:index}}"
                class="d-flex align-items-center justify-content-between text-black cursor-pointer"
                v-if="event.type=='announcement'"
            >
                <div class="font-weight-bold font-size-24">
                    {{$t('news['+index+'].title')}}
                </div>
                <div class="rounded-circle bg-white fixed-size d-flex align-items-center justify-content-center">
                    <right-little />
                </div>
            </router-link>
            <a
                :href="event.libguide_link"
                target="_blank"
                class="d-flex align-items-center justify-content-between text-black cursor-pointer"
                v-else
            >
                <div class="font-weight-bold font-size-24">
                    {{$t('news['+index+'].title')}}
                </div>
                <div class="rounded-circle bg-white fixed-size d-flex align-items-center justify-content-center">
                    <right-little />
                </div>
            </a>
        </div>
    </div>
</template>
<script>
import RightLittleVue from "../../../../common/assets/icons/RightLittle.vue"
export default{
    components:{
        RightLittle:RightLittleVue
    },
    props:{
        event:Object,
        index:String | Number,
        events:Array,
        itemWidth:String | Number
    }
}
</script>
<style scoped>
.bg-image{
	background-position: center; /* Center the image */
    background-repeat: no-repeat; /* Do not repeat the image */
    background-size: cover;
}
</style>