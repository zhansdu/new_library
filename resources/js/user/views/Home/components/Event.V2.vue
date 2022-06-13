<template>
    <div class="position-relative border border-black mr-5 rounded" :style="{width:itemWidth+ 'px'}">
        <div class="bg-image rounded-top w-100 bg-grey" :style="{backgroundImage:'url('+event.img.data+')',height:itemWidth*1.21*0.54-2+'px'}">
            <!-- instead of annoying mt which doesn't work here -->
            <div>&nbsp;</div>
            <div class="d-flex justify-content-center align-items-center flex-wrap bg-white rounded border mx-3" :style="{width:65/itemWidth*100+'%'}">
                <div class="py-2 px-2">
                    <div class="font-size-28 ln-0 text-center font-weight-bold">{{event.date.getDate()}}</div>
                    <div class="font-size-20 ln-0 text-center font-weight-bold">{{$t('months['+event.date.getMonth()+']').toUpperCase()}}</div>
                </div>
            </div>
        </div>
        <div class="p-4">
            <div class="d-flex flex-column font-size-18">
                <div>
                    <router-link
                        :to="{name:'full_event',params:{index:index},query:{index:index}}"
                        class="text-black cursor-pointer font-weight-bold"
                        v-if="event.type=='announcement'"
                    >
                        {{$t('news['+index+'].title')}}
                    </router-link>
                    <a
                        :href="event.libguide_link"
                        target="_blank"
                        class="text-black cursor-pointer font-weight-bold"
                        v-else
                    >
                        {{$t('news['+index+'].title')}}
                    </a>
                    <div class="text-grey">{{$t('news['+index+'].place')}}</div>
                    <div class="text-grey">
                        {{event.time_from }}
                        <span v-if="event.time_until">
                            {{'- ' + event.time_until}}
                            <span v-if="event.date_until">{{' ('+$t('months['+event.date.getMonth()+']')+' '+event.date.getDate()+' -'+event.date_until.getDate()+' '+$t('months['+event.date_until.getMonth()+']')+')'}}</span>
                        </span>
                    </div>
                </div>
                <div class="bg-red my-3 height-1"></div>
                <div>
                    <router-link
                        :to="{name:'full_event',params:{index:index},query:{index:index}}"
                        class="d-flex align-items-center justify-content-between text-grey cursor-pointer"
                        v-if="event.type=='announcement'"
                    >
                        <div >
                            {{$t(event.type)}}
                        </div>
                        <div class="rounded-circle bg-white fixed-size d-flex align-items-center justify-content-center">
                            <right-little />
                        </div>
                    </router-link>
                    <a
                        :href="event.libguide_link"
                        target="_blank"
                        class="d-flex align-items-center justify-content-between text-grey cursor-pointer"
                        v-else
                    >
                        <div>
                            {{$t(event.type)}}
                        </div>
                        <div class="rounded-circle bg-white fixed-size d-flex align-items-center justify-content-center">
                            <right-little />
                        </div>
                    </a>
                </div>
            </div>
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
.ln-0{
    line-height: 1;
}

.rounded,.rounded-top{
    border-radius: 5px !important;
}
.rounded-top{
    border-bottom-left-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
}
</style>