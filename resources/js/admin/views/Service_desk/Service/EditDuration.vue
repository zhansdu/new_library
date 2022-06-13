<template>
    <div class="p-3">
        <div class="font-size-20">{{$t('edit_duration')}}</div>
        <form class="d-flex justify-content-between my-4" @submit.prevent="confirm()">

            <div class="pad w-100">
                <input type="text" v-model="duration" class="mr-4 height-unset" />
				<label class="placeholder">{{$t('days')}}</label>
            </div>

            <div class="pad w-100">
                <input type="date" v-model="due_date" class="mr-4"/>
				<label class="placeholder">{{$t('date')}}</label>
            </div>

            <div class="pad w-100">
                <button type="submit" class="bg-green" >{{$t('edit')}}</button>
            </div>
        </form>
    </div>
</template>
<script>
import {message_success,message_error} from '../../../mixins/messages'
export default {
    mixins:[
        message_success,
        message_error
    ],
    props:{
        info:Object
    },
    data(){
        return{
            duration:this.info.duration,
            due_date:''
        }
    },
    methods:{
        confirm(){
            if(this.due_date){
                this.info.due_date=this.due_date;
                const date1 = new Date();
                const date2 = new Date(this.due_date);
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                this.info.duration=diffDays;
            }
            else if(this.duration){
                this.info.due_date=null;
                this.info.duration=this.duration;  
            }
            else{
                this.message_error('edit_duration',{});
                return 0;
            }
            this.message_success('edit_duration',{});
            this.$emit('close');
        }
    }
}
</script>