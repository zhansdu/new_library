<!-- table and table functions are copied because there's reference error when importing table-->
<template>
    <div>
        <div class="table">
            <table class="text-choosable">
                <thead>
                    <tr class="text-grey">
                        <th class="header text-center bg-lightgrey">
                            #
                        </th>
                        <th
                            class="header text-center bg-lightgrey cursor-pointer"
                            v-for="(name, index) in heads"
                            :key="index"
                            @click="sort(name.link, name.reverse, index)"
                        >
                            {{ $tc(name.name, 1) }}
                            <span>
                                <!-- for sorting -->
                                <CaretUp
                                    class="transition"
                                    :class="{ rotate: !name.reverse }"
                                />
                            </span>
                        </th>
                        <th class="header text-center bg-lightgrey">
                            {{$t('show_more')}}
                        </th>
                        <th class="header text-center bg-lightgrey">
                            {{$t('show_id_list')}}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data, index) in data" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td v-for="(name, i) in heads" :key="i">
                            {{ data[name.link] }}
                        </td>
                        <td>
                            <button @click="showMore(data)">{{$t('show_more')}}</button>
                        </td>
                        <td>
                            <button @click="showIdList(data)">{{$t('show_id_list')}}</button>
                        </td>
                    </tr>
                    <tr class="font-weight-bold">
                        <td class="fixed">#</td>
                        <td class="fixed">{{$t('total')}}:</td>
                        <td class="fixed"></td>
                        <td class="fixed">{{total.count}}</td>
                        <td class="fixed"></td>
                        <td class="fixed">{{total.cost}}</td>
                        <td class="fixed"></td>
                        <td class="fixed"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-2">
            <div class="pad">
                <button
                    type="button"
                    class="outline-black"
                    @click="$emit('close')"
                >
                    {{ $t("ok") }}
                </button>
            </div>
        </div>
        <modal name="id-list">
            <div class="d-flex flex-column p-4">
                <div class="my-3 mx-auto font-size-20 font-weigth-bold">{{$t('inventory_list')}}</div>
                <div class="d-flex flex-wrap">
                    <div v-for="id in list" :key="id">{{id}}&nbsp;</div>
                </div>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
// icons
import CaretUp from "../../../../common/assets/icons/CaretUp";

// mixins
import showModal from "../../../mixins/showModal";

// components
import MoreVue from "../../../components/More.vue";

export default {
    components: {
        CaretUp
    },
    mixins:[showModal],
    props: {
        id: String
    },
    data() {
        return {
            data: [],
            heads: [
                { name: "titles", link: "title" },
                { name: "type", link: "sigle_type" },
                { name: "count", link: "count" },
                { name: "cost", link: "price" },
                { name: "cost_total", link: "total_sum" }
            ],
            list:''
        };
    },
    computed:{
        total(){
            let total={
                count:0,
                cost:0
            }
            this.data.forEach(data=>{
                total.count+=parseInt(data.count)
                total.cost+=parseInt(data.count*data.price)
            })
            return total
        }
    },
    methods: {
        getData() {
            this.$store.commit('setFullPageLoading',true);
            this.$http.get("item/by/batch/" + this.id).then(response => {
                this.data = response.data.res;
                this.data.sort((a,b)=>{
                    if ( a.title < b.title ){
                        return -1;
                    }
                    if ( a.title > b.title ){
                        return 1;
                    }
                    return 0;
                })
            }).catch(error=>{
				this.message_error('loading',error);
			}).then(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
        },
        // main sorting
        sort(field, reverse, index) {
            if (reverse == null) {
                if (index != null) {
                    this.heads[index].reverse = false;
                } else {
                    this.statusReverse = false;
                }
            }
            let array = [];
            array = this.data;
            array.sort(this.sortBy(field, reverse));
            if (index != null) {
                this.heads[index].reverse = !this.heads[index].reverse;
            } else {
                this.statusReverse = !this.statusReverse;
            }
            this.sorting = field;
        },
        // helping function ( validation)
        sortBy(field, reverse) {
            let validate = a => {
                let x = copy(a);
                if (x == null) {
                    x = !reverse ? "z" : "-999999999999";
                    return x;
                }
                if (isNaN(x)) {
                    x = x.toUpperCase();
                } else {
                    x = parseInt(x);
                }
                return x;
            };
            return (a, b) => {
                a = validate(a[field]);
                b = validate(b[field]);
                return reverse ? (a < b) - (b < a) : (b < a) - (a < b);
            };
        },
        showMore(data){
            this.$store.commit('setFullPageLoading',true);
            let search_data={
                search_options:[{
                    key:'id',
                    operator:'and',
                    value:data.inv_list.split(',')[0]
                }]
            }
            let heads=[
                {name:'barcode',link:'barcode'},
                {name:'inventory_number',link:'id'},
                {name:'batches_number',link:'batch_id'},
                {name:'isbn',link:'isbn'},
                {name:'author',link:'author'},
                {name:'titles',link:'title'},
                {name:'publishers',link:'publisher'},
                {name:'pub_year',link:'pub_year'},
                {name:'pub_city',link:'pub_city'},
                {name:'suppliers',link:'supplier'},
                {name:'cost',link:'cost'},
                {name:'speciality',display_func:this.display_speciality},
                {name:'currency',link:'currency'},
                {name:'type_of_item',link:'item_type'},
                {name:'type_of_supply',link:'sup_type'},
                {name:'location',link:'location_title'},
                {name:'fill_date',link:'create_date',is_date:true},
                {name:'created_by',link:'username'},
                {name:'init_status',link:'init_status'},
                {name:'print_status',link:'print_status'}
            ]
            this.$http.post('item/search',search_data).then(response=>{
                this.showModal(MoreVue,{
                    data:response.data.res.data[0],
                    heads:heads,
                    width:'35%'
                })
            }).then(()=>{
                this.$store.commit('setFullPageLoading',false);
            })
        },
        showIdList(data){
            this.list=data.inv_list.split(',');
            this.$modal.show('id-list');
        }
    },
    mounted() {
        this.getData();
    }
};
</script>
<style scoped>
table {
    border-collapse: collapse;
    width: 100%;
}

td,
th {
    border: 0.0625em solid #e8e8e8;
    padding: 1em 1.25em;
}

th {
    text-align: left;
    font-weight: 500;
}

tbody tr:hover {
    box-shadow: 0 0 0.4375em rgba(8, 38, 115, 0.2);
}

input {
    width: unset;
    height: unset;
}

.table {
    position: relative;
    max-height: max(68vh, 31.25em);
    overflow: auto;
    border-bottom: 0.0625em solid #e8e8e8;
    border-top: 0.0625em solid #e8e8e8;
}

.header {
    position: sticky;
    top: 0;
    border-top: none;
    z-index: 1;
}

.fixed{
    position:sticky;
    bottom:0;
    background-color: white;
    z-index: 1;
}
</style>
