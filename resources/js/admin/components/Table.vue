<!-- THE CODE IS NOT AS DIFFICULT AS IT SEEMS -->
<!-- BE PATIENT -->
<template>
	<div class="d-flex flex-column rounded-lg">
		<div class="d-flex align-items-center mt-2" v-if="pagination">
			<div class="d-flex align-items-center" v-if="sortable">
				<label>{{$t('sort_by')}}</label>
				<Dropdown class="ml-2" :items="sorting_fields.fields" :itemOnClick="changeSortingOrder" :title="sort_by.order_by" dropdownStyles="left:0;right:unset;" dropdownShownStyles="max-height:20em;"/>
				<input class="ml-2" type="checkbox" v-model="sort_mode" @change="changeSortingMode"/>
				&nbsp;
				<label>{{$t('asc')}}</label>
			</div>
			<div class="d-flex align-items-center ml-auto">
				<label>{{$t('per_page')}}</label>
				<Dropdown class="ml-2" :items="per_page_list" :itemOnClick="changePerPage" :title="per_page" dropdownStyles="overflow:hidden;" dropdownShownStyles="max-height:20em;"/>
			</div>
		</div>
		<div class="table rounded-lg" :class="{'height-unset':!pagination}">
			<table class="text-choosable">
				<thead>
					<tr :ref="'row_'+index" class="text-grey" :class="{'sticky-row':stickyHeaders}" v-for="(head,index) in (isHeadObj ? heads.data :[heads])" :key="index">
						<th class="text-center bg-lightgrey" :rowspan="heads.max_rows" v-if="!selectable.available && index==0">
							<!-- if not selectable -->
							#
						</th>
						<th class="text-center no-border-right bg-orange  text-white" :rowspan="heads.max_rows" v-else-if="index==0">
							&nbsp;
						</th>
						<!-- if there's a status, BATCHES ONLY -->
						<th v-if="status && index==0"
							class="bg-lightgrey"
							:class="{'cursor-pointer' : simpleSortable}" 
							:rowspan="heads.max_rows"
							@click="simpleSortable ? sort('status',statusReverse) : ()=>{}"
						>
							{{$t('status')}}
							<span>
								<CaretUp class="transition" :class="{rotate:!(statusReverse && sorting=='status')}" v-if="simpleSortable"/>
							</span>
						</th>
						<!-- for in heads -->

						<th v-for="(name,i) in head" :key="i"
							class="bg-lightgrey"
							:class="[{'cursor-pointer':name.link!=null && simpleSortable},{'bg-orange  text-white' :selectable.available}]"
							@click="name.link!=null && simpleSortable ? sort(name.link,name.reverse,index) : ()=>{}"
							:rowspan="name.rowspan"
							:colspan="name.colspan"
						>
							<!-- displayed name in table -->
							{{$tc(name.name,1)}}
							<span>
								<!-- for sorting -->
								<CaretUp class="transition" :class="{rotate:!(name.reverse && sorting==name.link)}" v-if="name.link!=null && simpleSortable"/>
							</span>
						</th>
						<!-- all clickables share one table head -->
						<th class="bg-lightgrey" :class="{'bg-orange  text-white' :selectable.available}" :rowspan="heads.max_rows" v-if="clickables && index==0" ></th>
					</tr>
				</thead>
				<tbody>
					<!-- if there's a pagination then search in array.data, else just in array -->
					<tr v-for="(info,index) in (pagination ? array.data : array)" :key="index">
						<td class="text-center" v-if="!selectable.available">
							{{info['rn'] ? info['rn'] : index+1}}
						</td>
						<td class="text-center" v-else>
							<Checkbox :checked="selected.find(elem=>JSON.stringify(elem)==JSON.stringify(info))!=null" @change="addSelection(info)" />
						</td>
						<!-- BATCHES ONLY -->
						<td v-if="status"
							class="cursor-pointer"
							@click="showModal(ShowStatus,{id:info.id})"
						>
						<div class="px-3 py-2 text-center rounded"
							:class="info.status =='Open' ? 'outline-red' : info.status=='Checked' ? 'outline-orange' :'outline-green' "
						>
							{{info['status']}}
						</div>
						</td>
						<!-- default data elements
						for in heads  -->
						<td v-for="(name,i) in ( isHeadObj ? heads.links :heads)" :key="i"
							:class="[
							{'cursor-pointer':(service.available || showMore.available && showMore.title==undefined)},
							{'text-no-wrap':name.is_date},
							name.class_func ? name.class_func(info,name):{}
							]" 
							@click="tdOnClick(info,name)"
						>
							<span v-if="name.display_func" v-html="name.display_func(info,name)" />
							<span v-else-if="info[name.link]!=null">
								<span v-if="!Array.isArray(info[name.link])">
									{{
										name.is_date ? new Date(info[name.link]).toDateInputValue() : info[name.link]
									}}
								</span>
								<div v-for="(item,index) in info[name.link]" :key="index" v-else >
									[{{item}}]
								</div>
							</span>
							<edit class="d-inline-block float-right" v-if="typeof name.edit_icon=='function' ? name.edit_icon(info,name) : name.edit_icon"/>
						</td>
						<!-- if there are clickable elements in table -> Show more, Edit, ReCreate, Delete or Service -->
						<td class="text-center" v-if="clickables">
							<div class="d-flex align-items-center justify-content-around">
								<button type="button" class="outline-green" @click="showMore.func(info)"  v-if="showMore.available && showMore.title!=undefined">
									{{$t(showMore.title)}}
								</button>
								<div class="icon" :class="{'ml-4':showMore.available}" @click="editit(info,false)"  v-if="editObj.available">
									<Edit class="cursor-pointer"/>
								</div>
								<div class="ml-2 icon" @click="editit(info,true)" v-if="editObj.reCreate">
									<Redo class="cursor-pointer"/>
								</div>
								<div class="ml-2 icon" @click="deleteit(info.id || info.inv_id)" v-if="deleteObj.available">
									<Delete class="cursor-pointer"/>
								</div>
								<button type="button" class="ml-2 outline-green" @click="service.func(info)" v-if="service.available">
									<span class="icon"><Book /></span>
									<span>{{$t(service.title)}}</span>
								</button>
								<button type="button" class="ml-2" :class="custom_func.class" @click="custom_func.func(info)" v-if="custom_func.available">
									<span>{{$t(custom_func.title)}}</span>
								</button>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<!-- If the table has selectable elements then there is a "select all" checkbox below the table -->
		<!-- SELECTABLE ONLY -->
		<div class="bg-lightgrey selectable d-flex align-items-center justify-content-between" v-if="selectable.available">
			<div class="d-flex align-items-center" :class="{'cursor-pointer':selectable.showSelected}" >
				<Checkbox v-model="selectedAll" @change="selectAll()"/> &nbsp; &nbsp;
				<span @click="selectable.showSelected ? selectable.showSelected(selected) : ()=>{}">{{$t('select_all',{num:this.selected.length})}}</span>
			</div>
			<div class="d-flex align-items-center pad">
				<div class="d-flex align-items-center" v-if="selectable.selected">
					<button class="outline-black" @click="selectable.copy(selected)" >{{$t('show_in_table')}}</button>
					<button class="outline-black mx-3" @click="$eventHub.$emit('selectRefresh')">{{$t('clear_selected')}}</button>
				</div>

				<button type="button" class="outline-black" @click="selectable.func(selected)" v-if="selectable.func!=null">
					{{$t(selectable.button_title)}}
				</button>
			</div>
		</div>
		<!-- If there's a pagination -> then, there is a pagination : ) -->
		<div class="d-flex justify-content-between align-items-center mt-2" v-if="pagination">
			<pagination :data="array" :link="link" :commit="commit" />
			<div class="pad">
				<button type="button" class="d-flex align-items-center cancel-button" @click="last(link,commit)" v-if="refreshable">
					<span class="text-black font-size-24">
						<Refresh />
						&nbsp;
					</span>
					{{$t('refresh')}}
				</button>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
// mixins 
import showModal from '../mixins/showModal'
import {last} from '..//mixins/common'

// icons
import Edit from '../../common/assets/icons/Edit'
import Redo from '../../common/assets/icons/Redo'
import Delete from '../../common/assets/icons/Delete'
import CaretUp from '../../common/assets/icons/CaretUp'
import Refresh from '../../common/assets/icons/Refresh'
import Book from '../../common/assets/icons/Book'
// components
import ShowStatus from '../views/Acquisitions/Batches/ShowStatus'
import DeleteModal from'./DeleteModal'
import pagination from './Pagination'
import Dropdown from './Dropdown'
import Checkbox from './Checkbox'

export default{
	props:{
		heads:[Object,Array],
		data:[Object,Array],
		editObj: {
			type:Object,
			default(){return {}}
		},
		deleteObj:{
			type:Object,
			default(){return {}}
		},
		custom_func:{
			type:Object,
			default(){return {}}
		},
		showMore:{
			type:Object,
			default(){return{}}
		},
		service:{
			type:Object,
			default(){return {}}
		},
		// prop, indicating that you're on bathches page and you need to see the status
		status:Boolean,

		link:{
			type:String,
			default(){return ''}
		},
		commit:{
			type:String,
			default(){return ''}
		},
		pagination:{
			type:Boolean,
			default(){
				return true
			}
		},
		sortable:{
			type:Boolean,
			default(){
				return true
			}
		},
		simpleSortable:{
			type:Boolean,
			default(){
				return true
			}
		},
		stickyHeaders:{
			type:Boolean,
			default(){
				return true
			}
		},
		refreshable:{
			type:Boolean,
			default(){
				return true
			}
		},
		selectable:{
			type:Object,
			default(){
				return {
					available:false
				}
			}
		},
		clickables:{
			type:Boolean,
			default(){
				return true
			}
		}
	},
	mixins:[showModal,last],
	components:{
		Edit,
		Redo,
		Delete,
		CaretUp,
		Book,
		Refresh,
		Dropdown,
		Checkbox,
		pagination
	},
	computed:{
		// for sort by
		sorting_fields(){
			return this.$store.getters[this.commit].sorting_fields;
		},
		isHeadObj(){
			let heads=this.heads;
			return !Array.isArray(heads) && heads!=null && typeof heads=='object';
		}
	},
	data(){
		return{
			ShowStatus:ShowStatus,
			DeleteModal:DeleteModal,
			statusReverse:false,
			sorting:'create_date',
			array:this.data,
			per_page: this.pagination ? this.$store.state[this.commit].per_page : 10,
			per_page_list:[10,25,50,100,500],
			sort_by:this.pagination ? this.$store.state[this.commit].sort_by : '',
			selected:this.selectable.selected ? this.selectable.selected ?? [] : [],
			selectedAll:false,
			sort_mode:''
		}
	},
	watch:{
		data(newVal){
			this.array=newVal;
		}
	},
	methods:{
		distanceFromTop(){
			let rows=document.getElementsByClassName('sticky-row');
			let prev=rows[0];
			Array.from(rows).forEach((elem)=>{
				if(elem!=prev){
					let top=parseInt(prev.style.top);
					if(isNaN(top)){
						top=0;
					}
					elem.style.top=prev.offsetHeight+top +'px';
				}
				prev=elem;
			})
		},
		tdOnClick(info,name){
			if(this.service.available){
				this.service.showMore(info);
			}
			if(this.showMore.available && this.showMore.title == undefined){
				this.showMore.func(info);
			}
			if(name.custom_func){
				name.custom_func(info,name);
			}
		},
		editit(info,reCreate){
			let props={
				data:info,
				lastCreated:this.editObj.lastCreated
			};
			if(reCreate){
				props.reCreate=true;
			}
			else{
				props.edit=true;
			}
			this.showModal(this.editObj.component,props);
		},
		deleteit(id){
			this.showModal(this.DeleteModal,{link:this.link,commit:this.commit,id:id,width:'33.8%'});
		},

		// js's default sorting with my validation
	
		// main sorting 
		sort(field,reverse,index){
			if(reverse==null){
				if(index!=null){
					this.heads[index].reverse=false;
				}
				else{
					this.statusReverse=false;
				}
			}
			let array=[];
			if(this.pagination){
				array=this.array.data;
			}
			else{
				array=this.array;
			}
			array.sort(this.sortBy(field,reverse));
			if(index!=null){
				this.heads[index].reverse=!this.heads[index].reverse
			}
			else{
				this.statusReverse=!this.statusReverse;
			}
			this.sorting=field;
		},
		// helping function ( validation)
		sortBy(field,reverse){
			let validate=(a)=>{
				let x=copy(a)
				if(x==null){
					x=!reverse ? 'z' : '-999999999999';
					return x;
				}
				if(isNaN(x)){
					x=x.toUpperCase();
				}
				else{
					x=parseInt(x);
				}
				return x;
			};
			return (a,b)=>{
				a=validate(a[field]);
				b=validate(b[field]);
				return reverse ? (a<b)-(b<a) : (b<a) - (a<b);
			}
		},
		// here the functions for sorting end

		// sorting by backend
		getSortingFields(){
			this.$http.get(this.link+'/sort-fields').then(response=>{
				response.data.res.fields.forEach(item=>{
					item.name=item.key.replace(/.\./,"");
				})
				response.data.res.modes.forEach(item=>{
					item.name=item.key;
				})
				this.$store.dispatch('setStore',{label:this.commit,data:{sorting_fields:response.data.res}});
			})

		},
		// here soring by backend ends

		change(){
			let data={
				per_page:this.per_page
			}
			if(this.sortable){
				data.sort_by=this.sort_by
			}
			this.$store.dispatch('setStore',{label:this.commit,data:data});
			this.last(this.link,this.commit);
		},

		changePerPage(per_page){
			this.per_page=per_page;
			this.$store.dispatch('setStore',{label:this.commit,data:{page:0}});
			this.change();
		},
		changeSortingOrder(item){
			this.sort_by.order_by=item.key;
			this.change();
		},
		changeSortingMode(item){
			if(this.sort_mode){
				this.sort_by.order_mode='asc'
			}
			else{
				this.sort_by.order_mode='desc'
			}
			this.change();
		},

		// selecting
		addSelection(info){
			let selected = this.selected;
			let found=false;
			for(let i = 0;i<selected.length;i++){
				if(JSON.stringify(selected[i])==JSON.stringify(info)){
					selected.splice(i,1);
					found=true;
					break;
				}
			}
			if(!found){
				if(this.selectable.if!=null){
					if(this.selectable.if(info)){
						selected.push(info);
					}
				}
				else{
					selected.push(info);
				}
			}
		},
		selectAll(){
			let array = this.pagination ? this.array.data : this.data;
			let empty=(array)=>{
				while(array.length>0){
					array.pop();
				}
			}

			empty(this.selected);

			if(this.selectedAll){
				try{
					// a deep copy
					array.forEach(element=>{
						if(this.selectable.if!=null){
							if(this.selectable.if(element)){
								this.selected.push(element);
							}
						}
						else{
							this.selected.push(element);
						}
					})
				}catch(e){
					empty(this.selected);
				}
			}
		}
	},
	created(){
		if(this.commit){
			if(this.sortable && this.$store.getters[this.commit].sorting_fields.fields.length==0){
				this.getSortingFields();
			}
		}
		this.$eventHub.$on('selectRefresh',()=>{
			this.selected=[];
			this.selectedAll=false;
		})
	},
	mounted(){
		if(this.isHeadObj){
			this.distanceFromTop();
		}

	}
}
</script>
<style scoped>
table {
	border-collapse: separate;
	width: 100%;
	border-spacing: 0px;
}

td, th {
	border: 0.0625em solid #E8E8E8;
	padding: 1em 1.25em;
}

th{
	text-align: left;
	font-weight: 500;
}

tbody tr:hover{
	box-shadow: 0 0 0.4375em rgba(8, 38, 115, 0.2);
}

input{
	width:unset;
	height: unset;
}

.table{
	position:relatvie;
	max-height: max(68vh,31.25em);
	overflow: auto;
	border-bottom:0.0625em solid #E8E8E8;
	border-top:0.0625em solid #E8E8E8;
}

.no-border-right{
	border-right:none;
}

.selectable{
	padding: 1em 1.25em;
}

.sticky-row{
	position: sticky;
	top: 0;
}
</style>