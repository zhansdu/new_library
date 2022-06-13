<template>
    <table-div
        :heads="heads"
        :data="array"
        :selectable="selectable_copy"
        :link="link"
        :commit="commit"
        :pagination="pagination"
        :clickables="clickables"
        :sortable="sortable"
        :custom_func="custom_func"
    />
</template>
<script>
import TableDiv from "./Table";
export default {
    props:{
        heads:Array,
        // barcodes,selected -> data
        data:Array,
        selectable:Object,
        link:String,
        commit:String,
        pagination:Boolean,
        clickables:Boolean,
        sortable:Boolean,
        custom_func:Object,
        func:Function
    },
    components: { TableDiv },
    data(){
        return{
            array:JSON.parse(JSON.stringify(this.data)),
            selectable_copy:{},
        }
    },
    methods:{
        copy(selected){
            let res={res:selected};
			this.$store.dispatch('setStore',{label:this.commit,data:{ data:res , pagination:false }});
        }
    },
    created(){
        this.selectable_copy=copy(this.selectable);
        this.selectable_copy.selected=this.data;
        this.selectable_copy.copy=this.copy;
        this.selectable_copy.func=this.func
    }
};
</script>
