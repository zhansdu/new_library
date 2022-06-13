<template>
	<div class="d-flex">
		<form class="bg-white m-2 p-3 w-100 rounded-lg" @submit.prevent="loadResults()">
			<div class="d-flex">
				<div class="pad w-100">
					<input type="text" placeholder=" " v-model="books_history.search.add_options.barcode">
					<label class="placeholder">{{$t('barcode')}}</label> 
				</div>
				<div class="pad w-100">
					<input type="text" placeholder=" " v-model="books_history.search.add_options.id">
					<label class="placeholder">{{$t('inventory_number')}}</label> 
				</div>
			</div>
			<div class="d-flex mt-2">
				<div class="pad w-100">
					<input type="text" placeholder=" " v-model="books_history.search.add_options.author">
					<label class="placeholder">{{$t('author')}}</label> 
				</div>
				<div class="pad w-100">
					<input type="text" placeholder=" " v-model="books_history.search.add_options.title">
					<label class="placeholder">{{$t('title')}}</label> 
				</div>
			</div>
			<div class="hidden transition" :class="{shown:advanced}">
				<div class="d-flex mt-2">
					<div class="w-100">
						<div class="ml-3 mb-3 font-size-14 text-grey font-weight-bold">{{$t('borrow_date')}}</div>
						<div class="d-flex">
							<div class="pad w-100">
								<input type="date" v-model="books_history.search.add_options.borrow_date.from">
								<label class="placeholder">{{$t('from')}}</label> 
							</div>
							<div class="pad w-100">
								<input type="date" v-model="books_history.search.add_options.borrow_date.to">
								<label class="placeholder">{{$t('until')}}</label> 
							</div>
						</div>
					</div>
					<div class="w-100">
						<div class="ml-3 mb-3 font-size-14 text-grey font-weight-bold">{{$t('due_date')}}</div>
						<div class="d-flex">
							<div class="pad w-100">
								<input type="date" v-model="books_history.search.add_options.due_date.from">
								<label class="placeholder">{{$t('from')}}</label> 
							</div>
							<div class="pad w-100">
								<input type="date" v-model="books_history.search.add_options.due_date.to">
								<label class="placeholder">{{$t('until')}}</label> 
							</div>
						</div>
					</div>
				</div>
				<div class="d-flex mt-2">
					<div class="w-100">
						<div class="ml-3 mb-3 font-size-14 text-grey font-weight-bold">{{$t('delivery_date')}}</div>
						<div class="d-flex">
							<div class="pad w-100">
								<input type="date" v-model="books_history.search.add_options.delivery_date.from">
								<label class="placeholder">{{$t('from')}}</label>
							</div>
							<div class="pad w-100">
								<input type="date" v-model="books_history.search.add_options.delivery_date.to">
								<label class="placeholder">{{$t('until')}}</label>
							</div>
						</div>
					</div>
					<div class="w-100">
						<div class="ml-3 mb-3 font-size-14">&nbsp;</div>
						<div class="pad">
							<input-vue
							v-model="books_history.search.add_options.status"
							:selectable="{available:true,data:statuses}"
							:autocomplete="{available:true,data:statuses}"
							:showBody="false"
							/>
							<label class="placeholder">{{$t('status')}}</label>
						</div>
					</div>
				</div>	
			</div>
			<div class="d-flex mt-3">
				<div class="link ml-2 font-weight-bold text-lightgrey text-decoration-underline" @click="advanced=!advanced">{{$t(advanced?'simple_search':'advanced_search')}}</div>
				<div class="ml-2 text-lightgrey">
					<right-little class="font-size-12 transition" :class="{right:advanced}"></right-little>
				</div>
				<div class="border-top"></div>
			</div>
			<div class="d-flex justify-content-end">
				<div class="pad">
					<button type="submit">{{$t('search')}}</button>
				</div>
				<div class="pad">
					<button type="button" @click="reset(commit)">{{$t('reset')}}</button>
				</div>
			</div>
			<div v-if="books_history.searching">
				<table-div 
				class="mt-5"
				:heads="heads"
				:data="books_history.data.res"
				:link="link"
				:commit="commit"
				:sortable="false"
				:custom_func="custom_func"
				/>
			</div>
		</form>
	</div>
</template>
<script type="text/javascript">
// components
import Table from '../../../components/Table'
import AccountsVue from './Accounts.vue'
import InputVue from '../../../components/Input.vue'
import RightLittle from '../../../../common/assets/icons/RightLittle.vue'

//mixins
import {getResults,reset} from '../../../mixins/common'
import showModal from '../../../mixins/showModal'

import {mapGetters} from 'vuex'
import { message_error } from '../../../mixins/messages'

export default{
	mixins:[getResults,reset,showModal],
	components:{'table-div':Table,InputVue,RightLittle},
	computed:{
		...mapGetters(['books_history'])
	},
	data(){
		return{
			types:[],
			heads:[
			{name:'barcode',link:'barcode'},
			{name:'inventory_number',link:'id'},
			{name:'type',link:'type'},
			{name:'titles',link:'title',countable:true},
			{name:'author',link:'author'},
			{name:'borrow_date',link:'borrow_date',is_date:true},
			{name:'due_date',link:'due_date',is_date:true},
			{name:'delivery_date',link:'delivery_date',is_date:true},
			{name:'status',link:'status',class_func:this.status_class_func},
			{name:'last_user_borrowed',link:'username'},
			],
			link:'/book-history',
			commit:'books_history',
			custom_func:{
				title:'show_more',
				func:this.showMore,
				available:true
			},
			statuses:[],
			advanced:false
		}
	},
	methods:{
		getTypes(){
			this.$store.commit('setFullPageLoading',true);
			this.$http.get(this.link+'/types').then(response=>{
				this.types=response.data.res;
			}).catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		},
		getStatuses(){
			this.$store.commit('setFullPageLoading',true);
			this.$http.get(this.link+'/statuses').then(response=>{
				this.statuses=response.data.res;
			}).catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		},
		loadResults(){
			this.$store.dispatch('setStore',{label:this.commit,data:{page:0}});
			this.getResults(this.link,this.commit);
		},
		status_class_func(info){
			let res={};
			if (info.status=='issued'){
				res['text-orange']=true;
			}
			else if(info.status=='returned'){
				res['text-green']=true;
			}
			else{
				res['text-red']=true;
			}
			return res;
		},
		showMore(data){
			this.$store.commit('setFullPageLoading',true);

            let heads=[
                {name:'username',link:'username'},
                {name:'borrow_date',link:'borrow_date'},
                {name:'due_date',link:'due_date'},
                {name:'delivery_date',link:'delivery_date'},
                {name:'status',link:'status'},
            ]
            this.$http.get('book-history/users/'+data.id).then(response=>{
                this.showModal(AccountsVue,{
                    data:response.data.res,
                    heads:heads
                })
            }).catch(e=>{
				this.message_error('loading',e);
			}).then(()=>{
                this.$store.commit('setFullPageLoading',false);
            })
		}
	},
	created(){
		this.getTypes();
		this.getStatuses();
	}
}
</script>
<style scoped>
.hidden{
	overflow: hidden;
	max-height: 0;
}
.shown{
	max-height: 30em;
	overflow: visible;
}

.right{
	transform: rotate(-90deg);
}

.text-decoration-underline{
	text-decoration: underline;
}
</style>