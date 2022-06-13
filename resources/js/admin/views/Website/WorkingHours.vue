<template>
    <div class="d-flex">
        <form class="bg-white rounded m-2 w-100 py-3 px-4" @submit.prevent="save">
            <div class="d-flex">
                <div class="col-6">
                    <div class="pad">
                        <select v-model="website.data.w_h.start_day" required>
                            <option :value="index" v-for="(day,index) in $t('days')" :key="index">{{day}}</option>
                        </select>
                        <label class="placeholder required">{{$t('start_day')}}</label>
                    </div>
                    <div class="pad mt-2">
                        <input type="time" v-model="website.data.w_h.start_time" required>
                        <label class="placeholder required">{{$t('start_time')}}</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="pad">
                        <select v-model="website.data.w_h.end_day" required>
                            <option :value="index" v-for="(day,index) in $t('days')" :key="index">{{day}}</option>
                        </select>
                        <label class="placeholder required">{{$t('end_day')}}</label>
                    </div>
                    <div class="pad mt-2">
                        <input type="time" v-model="website.data.w_h.end_time" required>
                        <label class="placeholder required">{{$t('end_time')}}</label>
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