<template>
	<div class="d-flex flex-column ">
		<div class="d-flex align-items-center justify-content-between">
			<Back classes="mt-2 ml-2 bg-white" />
			
		</div>
		<form class="d-flex">
			<!-- modules -->
			<div class="mt-2 ml-2 bg-white rounded-lg col-2">
			<div class="mt-3 ml-3 mb-2">{{$t('all_modules')}}</div>
				<div class="pl-3 pr-3 pb-3 pt-2 border-top">
					<div v-for="(module) in modules" :key="module.id">
						<div class="p-1 mt-2 bg-lightgrey rounded cursor-pointer" :class="{'bg-orange':selected_module_id==module.id}" @click="permissionsByModule(module.id)" >{{$tc(module.route_name,1)}}</div>
					</div>
				</div>
			</div>
			<!-- everything else -->
			<div class="mt-2 mx-2 bg-white rounded-lg flex-fill p-3">
				<div class="p-2 m-2 border border-grey rounded d-flex flex-column">
					<div class="d-flex flex-wrap">
						<div class="my-1 mx-2 py-1 px-2 rounded bg-lightblue" v-for="(permission,index) in chosen" :key="permission.id">
							{{permission.display_name}}
							<span class="text-orange ml-2 cursor-pointer" @click="remove_permission(permission,index)"><X /></span>
						</div>
					</div>
					<div class="d-flex flex-fill justify-content-end mt-3">
						<div class="d-flex align-items-center" >
							<button class="outline-black mr-2" type="button" @click="chooseAll()" v-if="selected_module_id">
								{{$t('choose_all_permissions')}}
							</button>
							<button type="button" class="outline-orange" @click="save()">
								<span>{{($t('save'))}}</span>
							</button>
						</div>
					</div>
				</div>
				<div class="mt-5">
					<div class="d-flex flex-wrap">
						<div class="my-1 mx-2 py-1 px-2 rounded bg-lightblue" v-for="(permission,index) in permissions" :key="permission.id">
							{{permission.display_name}}
							<span class="text-orange ml-2 cursor-pointer" @click="add_permission(permission,index)"><Plus /></span>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</template>
<script>
import {goTo} from '../../mixins/goTo'
import Back from '../../components/Back'
import Plus from '../../../common/assets/icons/Plus'
import X from '../../../common/assets/icons/X'
import { message_success,message_error } from '../../mixins/messages'
export default {
    props:{
        user:Object,
    },
	mixins:[goTo,message_success,message_error],
	components:{Plus,X,Back},
    data(){
        return{
			modules:[],
			permissions:[],
			link:'/manage',
			selected_module_id:'',
			chosen:[]
        }
    },
    methods:{
		getVisualization(){
			this.$store.commit('setFullPageLoading',true)
			this.$http.get(this.link+'/users/'+this.user.user_cid+'/visualization/permissions').then(response=>{
				this.modules=response.data.res
			}).catch(error=>{
				this.message_error('loading',error)
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false)
			})
		},
		find_module_index(id){
			for(let i = 0;i<this.modules.length;i++){
				if(this.modules[i].id==id){
					return i
				}
			}
		},
		permissionsByModule(id){
			this.selected_module_id=id;
			let index=this.find_module_index(id);
			this.permissions=this.modules[index].visualization;
		},
		add_permission(permission,index){
			this.permissions.splice(index,1);
			permission.location={module_id:this.selected_module_id}
			this.chosen.push(permission);
		},
		remove_permission(permission,index){
			this.modules[this.find_module_index(permission.location.module_id)].visualization.push(permission);
			this.chosen.splice(index,1);
			this.permissionsByModule(permission.location.module_id);
		},
		chooseAll(){
			for(let i=0;i<this.permissions.length;i++){
				let permission=this.permissions[i]
				permission.location={module_id:this.selected_module_id}
				this.chosen.push(permission);				
			}
			this.permissions.splice(0,this.permissions.length);
		},
		save(){
			this.$confirm(this.$t('give_permissions_confirm',{username:this.user.username})).then(()=> {
				this.$store.commit('setFullPageLoading',true);
				let permissions = this.chosen.map(permission=>permission.id);
				let modules = this.chosen.map(permission=>permission.location.module_id);
				this.$http.post(this.link+'/users/'+this.user.user_cid+'/give_permissions',{permissions_ids:permissions,modules_ids:modules}).then(response=>{
					this.message_success('give_permissions',response);
					this.refresh();
				}).catch(e=>{
					this.message_error('give_permissions',e);
				}).finally(()=>{
					this.$store.commit('setFullPageLoading',false);
				});
			});
		},
		refresh(){
			this.getVisualization();
			this.selected_module_id='';
			this.permissions=[];
			this.chosen=[];
		}
    },
	created(){
		if(this.user==null){
			this.goTo('administration_main')
		}
		else{
			this.refresh();
		}
	}
}
</script>