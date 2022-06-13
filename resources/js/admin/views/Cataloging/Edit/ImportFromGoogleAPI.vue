<template>
    <div>
        <table-div
            :heads="heads"
            :data="data"
            :pagination="false"
            :selectable="selectable"
            :link="'link'" 
            :commit="'commit'"
        />
    </div>
</template>
<script>
import TableDiv from '../../../components/Table';

export default {
    props:{
        data:[Object,Array],
        edit_info:[Array],
        after:Function
    },
    components: { TableDiv },
    data(){
        return{
            heads:[
                {
                    name:'code',
                    link:'code'
                },
                {
                    name:'value',
                    link:'value'
                }
            ],
            selectable:{
                available:true,
                func:this.importIt,
                button_title:'save'
            }
        }
    },
    methods:{
        importIt(selected){
            let res=this.edit_info;
            selected.forEach(e=>{
                if(e.value){
                    let info = res.find(elem=>elem.id==e.code);
                    if(Array.isArray(e.value)){
                        e.value.forEach(value=>{
                            let new_data=copy(info);
                            new_data.is_added=true;
                            new_data.repeatable=null;
                            if(info.data){
                                new_data.data=value;
                                res.push(new_data);
                            }else{
                                info.data=value;
                            }
                        })
                    }else{
                        info.data=e.value;
                    }
                }
            });
            this.after(res);
        }
    },
    created(){
        this.data.sort((a,b)=>{
            if(a.code>b.code){
                return 1
            }
            else if(a.code==b.code){
                return 0
            }
            return -1
        })
    }
}
</script>
