<template>
    <div class="d-flex">
        <div class="d-flex flex-column bg-white mt-2 w-100 py-3 px-4">
            <div class="font-size-20 font-weight-bold"> {{$t('themes')}} :</div>
            <div class="d-flex mt-2">
                <div class="d-flex align-items-center mr-4">
                    <input type="radio" class="mr-2" value="dark" v-model="theme" @change="setTheme(theme)">
                    <label class="font-size-18 font-weight-bold text-no-wrap">{{$t('dark')}}</label>
                </div>
                <div class="d-flex align-items-center mr-4">
                    <input type="radio" class="mr-2" value="light" v-model="theme" @change="setTheme(theme)">
                    <label class="font-size-18 font-weight-bold text-no-wrap">{{$t('light')}}</label>
                </div>
                <div class="d-flex align-items-center">
                    <input type="radio" class="mr-2" value="default" v-model="theme" @change="setTheme(theme)">
                    <label class="font-size-18 font-weight-bold text-no-wrap">{{$t('default')}}</label>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {setTheme} from '../../mixins/settings'
export default {
    mixins:[setTheme],
    data(){
        return{
            theme:localStorage.getItem('theme')
        }
    }
}
</script>
<style scoped>
input[type=radio]{
    width:unset;
}
</style>