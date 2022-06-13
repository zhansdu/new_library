<template>
    <div class="d-flex">
        <div class="m-2 bg-white rounded w-100 p-3">
            <div class="d-flex justify-content-end">
                <div class="mr-2">
                    <button @click="loadKSU">{{$t('load_ksu')}}</button>
                </div>
                <div>
                    <button @click="downloadKSU">{{$t('download_ksu')}}</button>
                </div>
            </div>
            <div v-if="ksu.searching">
                <table-div
                    class="mt-4" 
                    :heads="heads"
                    :data="ksu.data.res"
                    :sortable="false"
                    :simpleSortable="false"
                    :clickables="false"
                    :commit="commit"
                    :link="link"
                />
            </div>
        </div>
    </div>
</template>

<script>

import TableDiv from '../../../components/Table.vue'

import {download_file} from '../../../mixins/files'
import {message_error} from '../../../mixins/messages'

import {mapGetters} from 'vuex'

export default {
    mixins:[
        download_file,
        message_error
    ],
    components:{
        TableDiv
    },
    computed:{
		...mapGetters(['ksu'])
    },
    data(){
        return{
            heads:{
                data:[
                    [
                        {name:'invoice_date',rowspan:2},
                        {name:'suppliers',rowspan:2},
                        {name:'doc_year',rowspan:2},
                        {name:'batch_id',rowspan:2},
                        {name:'on_balance',colspan:3},
                        {name:'no_balance',colspan:3},
                        {name:'total',colspan:3},
                        {name:'by_lang',colspan:4},
                        {name:'by_type',colspan:5},
                        {name:'e_books',colspan:6},
                    ],
                    [
                        {name:'titles'},
                        {name:'items'},
                        {name:'sum'},
                        {name:'titles'},
                        {name:'items'},
                        {name:'sum'},
                        {name:'titles'},
                        {name:'items'},
                        {name:'sum'},
                        {name:'kz'},
                        {name:'rus'},
                        {name:'foreign_lang'},
                        {name:'null_lang'},
                        {name:'scientific_literature'},
                        {name:'textbooks'},
                        {name:'educational_methodical_literature'},
                        {name:'popular_science_literature'},
                        {name:'other'},
                        {name:'titles'},
                        {name:'items'},
                        {name:'sum'},
                        {name:'kz'},
                        {name:'rus'},
                        {name:'foreign_lang'}
                    ]
                ],
                links:[
                    {link:'invoice_date'},
                    {link:'supplier'},
                    {link:'doc_year'},
                    {link:'batch_id'},
                    {link:'in_balance_titles'},
                    {link:'in_balance_items'},
                    {link:'in_balance_price'},
                    {link:'not_in_balance_titles'},
                    {link:'not_in_balance_items'},
                    {link:'not_in_balance_price'},
                    {link:'total_titles'},
                    {link:'total_items'},
                    {link:'total_price'},
                    {link:'kz_lang_materials'},
                    {link:'ru_lang_materials'},
                    {link:'other_lang_materials'},
                    {link:'null_lang'},
                    {link:'scientific_literature'},
                    {link:'textbooks'},
                    {link:'educational_methodical_literature'},
                    {link:'popular_science_literature'},
                    {link:'other'},
                    {link:'disc_titles'},
                    {link:'disc_items'},
                    {link:'disc_totalcost'},
                    {link:'disc_language_kz'},
                    {link:'disc_language_ru'},
                    {link:'disc_language_other'},
                ],
                max_rows:2
            },
            link:'ksu_report',
            commit:'ksu'
        }
    },
    methods:{
        loadKSU(){
            this.$store.commit('setFullPageLoading',true);
            this.$http.get(this.link+'/get').then(response=>{
                this.$store.dispatch('setStore',{label:this.commit,data:{searching:true,data:response.data}});
            }).catch(e=>{
                this.message_error('load',e);
            }).then(()=>{
                this.$store.commit('setFullPageLoading',false);                
            })
        },
        downloadKSU(){
            this.$store.commit('setFullPageLoading',true);
            this.$http.get(this.link+'/export?locale='+this.$i18n.locale,{responseType:'blob'}).then(response=>{
                this.download_file(response,'ksu','xlsx');
            }).catch(e=>{
                this.message_error('download',e);
            }).then(()=>{
                this.$store.commit('setFullPageLoading',false);
            })
        }
    }
}

</script>

<style scoped>

</style>