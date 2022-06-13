<template>
    <div >
        <div class="bg-white border border-grey py-5 px-3">
            <div class="d-flex justify-content-center">
                <span class="font-size-20 font-weight-bold">{{$t('reserve')}}</span>
            </div>
            <div class="d-flex align-items-center mt-3">
                <div>{{$t('until')}}: </div>
                <input class="ml-2 border-grey" type="date"/>
            </div>
            <div class="d-flex flex-fill justify-content-end mt-4">
                <button class="border bg-white border-green text-green" @click="ok()">{{$t('ok')}}</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:{
        change_shown:Function
    },
    methods:{
        ok(){
            this.change_shown();
        }
    }
}
</script>
<style scoped>
.border{
    border-radius: .3125em;
}
</style>