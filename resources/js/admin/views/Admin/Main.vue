<template>
    <form class="d-flex" @submit.prevent="loadResults()">
		<!-- modules -->
		<div class="mt-2 ml-2 bg-white rounded-lg col-2">
			<div class="mt-3 ml-3 mb-2">{{$t('search_by_modules')}}</div>
			<div class="pl-3 pr-3 pb-3 pt-2 border-top">
				<div v-for="(module) in admin.modules" :key="module.id">
					<div class="p-1 mt-2 bg-lightgrey rounded cursor-pointer" :class="{'bg-orange':admin.module_id==module.id}" @click="searchByModule(module.id)" >{{$tc(module.route_name,1)}}</div>
				</div>
			</div>
		</div>
		<!-- everything else -->
		<div class="mt-2 mx-2 bg-white rounded-lg flex-fill p-3">
			<div class="d-flex align-items-center justify-content-between">
				<div class="d-flex w-75">
					<div class="d-flex mr-2">
						<div class="border border-grey mr--1 text-center cursor-pointer d-flex align-items-center px-5"
							v-for="(type,index) in types" :key="index"
							@click="chooseType(type)"
							:class="[
								{'border-left-radius' : index==0},
								{'border-right-radius':index==types.length-1},
								{'border-orange text-orange':type.key==search_type}
						]">
							{{$t(type.key)}}
						</div>
					</div>
					<div class="flex-fill align-self-stretch">
						<input-div 
							classes="border-grey w-100"
							:search='true'
							:onSubmit="loadResults"
							v-model="admin.search.add_options.all"
							:placeholder="$t('user_id_user')"
						/>
					</div>
				</div>
				<div class="d-flex align-items-center" >
					<button type="submit">
						<span><Search /> &nbsp;</span>
						<span>{{($t('search'))}}</span>
					</button>
				</div>
			</div>
			<div class="mt-5">
				<div v-if="search_module ? admin_module.searching : admin.searching">
					<table-div 
					class="mt-5" 
					:heads="heads"
					:data="search_module ? admin_module.data.res : admin.data.res" 
					:link="search_module ? 'manage/users/by_module' : 'service/user/'+this.search_type" 
					:commit="search_module ? 'admin_module': 'admin'"
					:pagination="true"
					:sortable="false"
					:custom_func="custom_func"
					/>
				</div>
			</div>
		</div>
	</form>
</template>
<script type="text/javascript">
// mixins
import {goTo} from '../../mixins/goTo'
import {getResults} from '../../mixins/common'

// common components
import TableDiv from '../../components/Table'
import InputDiv from '../../components/Input'

// icons
import Search from '../../../common/assets/icons/Search'

import {mapGetters} from 'vuex'

export default{
	mixins:[goTo,getResults],
	components:{
		TableDiv,
		InputDiv,
		Search
	},
	computed:{
		...mapGetters(['admin','admin_module'])
	},
	data(){
		return{
			heads:[
                {name:'full_name',link:'full_name',display_func:this.DisplayFullname},
                {name:'username',link:'username',display_func:this.DisplayUsername}
            ],
			custom_func: {
                available: true,
                class: "outline-green",
                func: this.goToControlPage,
                title: "edit_rec"
            },
			types:[{key:'student'},{key:'employee'}],
			search_type:'employee',
			search_module:false
		}
	},
	methods:{
		DisplayFullname(item){
			if(item.student){
				return item.student.name + ' ' + item.student.surname
			}
			else if(item.employee){
				return item.employee.name + ' ' + item.employee.surname
			}
			else{
				return item.full_name
			}
		},
		DisplayUsername(item){
			if(item.student){
				return item.student.username
			}
			else if(item.employee){
				return item.employee.username
			}
			else{
				return item.username
			}
		},
		goToControlPage(user){
			this.goTo('administration_control',{ user:user });
		},
		loadVisualization(){
			this.$store.commit('setFullPageLoading',true);
			this.$http.get('manage/visualization').catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
		},
		searchByModule(id){
			this.search_module=true;
			this.$store.getters.admin_module.search.add_options.module_id=id;
			this.getResults('manage/users/by_module','admin_module');
		},
		// search by users
		loadResults(){
			this.$store.dispatch('setStore',{label:'admin',data:{page:0,module_id:''}});
			this.getResults('service/user/'+this.search_type,'admin');
			
		},
		chooseType(type){
			this.search_type=type.key;
		}
	},
	created(){
		this.loadVisualization();
	}
}
</script>
<style scoped>

.border-orange{
	/*fu.... so bad code .... HATE IT!*/
	border-width:0.09375em;
	z-index: 1;

	/*never use !important ... do never!!!*/
	border-left:0.09375em solid #ff9d29 ;
	border-right:0.09375em solid #ff9d29 ;
}

.border-left-radius{
	border-top-left-radius: 0.3125em;
	border-bottom-left-radius: 0.3125em;
}

.border-right-radius{
	border-top-right-radius: 0.3125em;
	border-bottom-right-radius: 0.3125em;	
}

.mr--1{
	margin-right: -0.0625em;
}
</style>