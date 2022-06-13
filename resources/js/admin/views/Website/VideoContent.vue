<template>
    <div class="d-flex">
        <form class="bg-white rounded m-2 w-100 py-3 px-4" @submit.prevent="save">
            <div class="d-flex justify-content-between align-items-center">
                <div class="font-weight-bold font-size-20">{{$t('video_content')}} :</div>
            </div>
            <div class="mt-3">
                <div class="py-4 border-bottom" v-for="(video,index) in website.data.videos" :key="index">
                    <div class="d-flex">
                        <div class="pad w-100">
                            <input type="text" v-model="video.title_en" required/>
                            <label class="placeholder required">{{$t('title')}} {{$t('en')}}</label>
                        </div>
                        <div class="pad w-100">
                            <input type="text" v-model="video.title_ru" required/>
                            <label class="placeholder required">{{$t('title')}} {{$t('ru')}}</label>
                        </div>
                        <div class="pad w-100">
                            <input type="text" v-model="video.title_kz" required/>
                            <label class="placeholder required">{{$t('title')}} {{$t('kz')}}</label>
                        </div>
                    </div>
                    <div class="d-flex mt-2">
                        <div class="pad w-100">
                            <input type="text" v-model="video.link" required/>
                            <label class="placeholder required">{{$t('link')}}</label>
                        </div>
                        <div class="pad w-100">
                            <input type="text" v-model="video.link_embed" required/>
                            <label class="placeholder required">{{$t('link_embed')}}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <div>
                    <button type="submit">{{$t('save')}}</button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'

import {loadData, save} from '../../mixins/website'

export default {
    mixins:[save,loadData],
    computed:{
        ...mapGetters(['website'])
    }
}
</script>