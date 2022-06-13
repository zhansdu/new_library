<template>
    <div class="d-flex">
        <div class="m-2 bg-white rounded d-flex flex-column flex-fill p-3">
            <form class="d-flex justify-content-between" @submit.prevent="loadReport">
                <div class="d-flex col-6">
                    <input type="text" class="mr-2" :placeholder="$t('year')" v-model="year" >
                </div>
                <div class="d-flex">
                    <button type="submit" class="mr-2">{{$t('apply')}}</button>
                    <button type="button" class="mr-2" @click="year=null;loadReport()">{{$t('load_all')}}</button>
                    <button type="button" class="mr-2" @click="downloadReport">{{$t('download_report')}}</button>
                </div>
            </form>
            <div class="col-12 mt-5" v-if="searching">
                <line-chart :data="data" :options="withOptions(lineOptions)"/>
            </div>
        </div>
    </div>
</template>
<script>

import {download_file} from '../../../mixins/files'
import {message_error} from '../../../mixins/messages'
import chartMixins,{lineOptions} from '../../../mixins/charts'

import LineChart from '../../../plugins/charts/Line'

export default {
    mixins:[
        download_file,
        message_error,
        chartMixins,
        lineOptions
    ],
    components:{
        LineChart
    },
    data(){
        return{
            link:'stat_report',
            data:{
				datasets:[
				{
					label:this.$t('total_borrow'),
                    link:"total_borrow",
					data:[]
				},
				{
					label:this.$t('on_hands'),
                    link:"on_hands",
					data:[]
				}
				]
			},
            year:null,
            searching:false
        }
    },
    methods:{
        loadReport(){
            this.$store.commit('setFullPageLoading',true);
            this.$http.get(this.link+'/get'+(this.year ? '?year='+this.year:'')).then(response=>{
                this.loadData(response.data.res);
            }).catch(e=>{
                this.message_error('load',e);
            }).then(()=>{
                this.$store.commit('setFullPageLoading',false);                
                this.searching=true;
            })
        },
        downloadReport(){
            this.$store.commit('setFullPageLoading',true);
            this.$http.get(this.link+'/export?locale='+this.$i18n.locale+(this.year ? '&year='+this.year:''),{responseType:'blob'}).then(response=>{
                this.download_file(response,'report','xlsx');
            }).catch(e=>{
                this.message_error('download',e);
            }).then(()=>{
                this.$store.commit('setFullPageLoading',false);
                this.searching=true;
            })
        },
        loadData(data){
            let total_b=[],on_h=[],labels=[],copy={};

            copy.datasets=this.data.datasets;
            
            data.forEach(elem=>{
                total_b.push(elem.total_borrow);  
                on_h.push(elem.on_hands);  
                labels.push(elem.year + ' ' + elem.month);  
            })
            
            copy.labels=labels;
            copy.datasets.forEach(elem=>{
                if(elem.link=='total_borrow'){
                    elem.data=total_b
                }
                else{
                    elem.data=on_h
                }
            });
            this.data=copy;
        },
		setData(){
			this.data.datasets.forEach(item=>{
				if(item.link=="total_borrow"){
					item.borderColor="#FF9D29"
				}
				else{
					item.borderColor="#9013FE"
				}
			})
		}
    },
    created(){
		this.setData();
    }
}
</script>
<style scoped>

</style>