<template>
	<div :class="modal ? 'd-flex justify-content-center bg-greyer padding mh-100 overflow-y-auto' :'padding'">
		<div class="d-flex align-items-start bg-white border-top py-4" :class="{'content px-5':modal}">
			<div class="d-none d-md-block mr-5" v-if="printing!='print'">
				<div class="image rounded bg-grey" :style="'background-image: url('+this.data.image+')'"></div>
				<div class="d-flex align-items-center cursor-pointer py-2 mt-2" @click="copyLink()">
					<Save />
					<span class="ml-2">{{$t('copy_link')}}</span>
				</div>
				<div class="d-flex align-items-center cursor-pointer py-2" @click="printPage()">
					<Print />
					<span class="ml-2">{{$t('print_page')}}</span>
				</div>
			</div>
			<div class="flex-fill">
				<div class="d-flex flex-fill">
					<div class="flex-fill">
						<div class="overflow-hidden title font-weight-bold font-size-24 cursor-pointer">{{data.title}}</div>
						<div class="text-grey mt-2">
							<div v-if="data.author">{{data.author}}, {{data.year}}</div>
						</div>
						<div class="d-flex text-center text-blue mt-3">
							<div class="rounded-lg bg-lightblue p-1 px-3" v-if="data.type">{{$t(data.type)}}</div>
							<div class="rounded-lg bg-lightblue p-1 px-3 ml-3" v-if="data.call_number">{{data.call_number}}</div>
						</div>
						<div class="d-block d-sm-none mt-3 text-center">
							<div class="bg-lightgrey rounded-lg p-2 text-no-wrap">
								{{data.available + ' / ' +data.total}}
								{{$t('availability')}}
							</div>
						</div>
						<div class="mt-3" v-if="data.description">
							<div class="text-grey font-size-14">
								{{$t('description')}}
							</div>
							<div class="mt-1" v-html="data.description" />
						</div>
						<div class="mt-3" v-if="data.content">
							<div class="text-grey font-size-14">
								{{$t('content')}}
							</div>
							<div class="mt-1" v-html="data.content" />
							<div class="text-blue cursor-pointer font-weight-bold mt-2">
								<div @click="expandContent(data,true)" v-if="!contentExpanded">{{$t('expand')}}</div>
								<div @click="expandContent(data,false)" v-else>{{$t('shrink')}}</div>
							</div>
						</div>
						<div class="mt-3" v-if="data.subject_terms">
							<div class="text-grey font-size-14">
								{{$t('subject_terms')}}
							</div>
							<div class="mt-1 d-flex flex-wrap" >
								<div v-for="(sub,index) in data.subject_terms" :key="index">
									<button class="btn outline-blue mr-2 mt-2" v-html="sub" @click="searchBySubjectTerm(sub)"></button>
								</div>
							</div>
						</div>
					</div>
					<div class="d-none d-sm-block text-center col-2 px-0">
						<div class="bg-lightgrey rounded-lg p-2 text-no-wrap">
							{{data.available + ' / ' +data.total}}
							{{$t('availability')}}
						</div>
					</div>
				</div>
				<div class="d-none d-sm-block" v-if="data.general.length>0">
					<div class="title mt-4">
						<div class="text">
							{{$t('general_details')}}
						</div>
						<div class="tline"/>
					</div>
					<table class="w-100">
						<tbody>
							<tr class="py-1" v-for="(info,index) in data.general.filter(info=>info.value)" :key="index">
								<td class="text-grey">{{$t(info.key)}}:</td>
								<td>{{$t(info.value)}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="d-none d-sm-block" v-if="data.physical.length>0">
					<div class="title mt-4">
						<div class="text">
							{{$t('physical_details')}}
						</div>
						<div class="tline"/>
					</div>
					<table class="w-100">
						<tbody>
							<tr class="py-1" v-for="(info,index) in data.physical.filter(info=>info.value)" :key="index">
								<td class="text-grey">{{$t(info.key)}}:</td>
								<td>{{$t(info.value)}}</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div class="d-block d-sm-none">
					<div v-for="(info,index) in array_data.filter(info=>info.value)" :key="index">
						<div class="text-grey mt-3">
							{{$t(info.key)}}:
						</div>
						<div>
							{{info.value}}
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- fixed stuff -->
		<div class="close_icon cursor-pointer" @click="closeModal()" v-if="modal">
			<X/>
		</div>
	</div>
</template>
<script type="text/javascript">
	import {goTo} from '../../mixins/goTo'
	import {getBookImage} from '../../mixins/search'

	import RightLittle from '../../../common/assets/icons/RightLittle'
	import X from '../../../common/assets/icons/X'
	import Save from '../../../common/assets/icons/Save'
	import Print from '../../../common/assets/icons/Print'

	import { performSearch } from '../../mixins/search'

	export default{
		props:{
			modal:Boolean,
			id:[String,Number]
		},
		mixins:[goTo,getBookImage,performSearch],
		components:{RightLittle,X,Save,Print},
		data(){
			return{
				data:{
					image:'',
					isbn:'',
					description:'',
					content:'',
					general:[],
					physical:[]
				},
				array_data:[],
				xml:[],
				link:'',
				contentExpanded:false,
				printing:false
			}
		},
		methods:{
			searchBySubjectTerm(subject_term){
				let key='all'
				let value = subject_term

				let options=[{key:key,value:value}]
				let request={
					search_options:options
				};
				let query = this.$t(key) + ' : ' + value;

				
				this.performSearch(request,query,options);
				this.closeModal();
			},
			expandContent(data,bool){
				if(this.xml && data.content){
					if(bool){
						data.content=this.getFromCatalog(this.xml,'505.a').split('--').join('<br>');
						this.contentExpanded=true;
					}
					else{
						data.content=data.content.split(/--|<br>/).splice(0,2).join('--')
						this.contentExpanded=false;
					}
				}
			},
			closeModal(){
				this.$emit('close');
				document.documentElement.classList.remove('overflow-hidden');
			},
			copyLink(){
				var copyText = document.createElement('input');
				copyText.value=this.link;
				document.body.appendChild(copyText);

				copyText.select();
				copyText.setSelectionRange(0, 99999); /* For mobile devices */
				try{
					document.execCommand("copy");
					alert('Copied successfully '+copyText.value);
				}catch(e){
					alert('Something went wrong');
				}
				
				document.body.removeChild(copyText);
			},
			printPage(){
				if(!this.printing){
					let routeData = this.$router.resolve({name: 'full_book', query: {id: this.data.id,mode:'print',contentExpanded:this.contentExpanded}});
					window.open(routeData.href, '_blank');
					return 0;
				}
				window.print();
			},
			capitalize(s){
				let string = s.slice();
				if (typeof string !== 'string') return ''
					return string.charAt(0).toUpperCase() + string.slice(1)
			},
			objectWithoutKey(object,key){
				const {[key]: deletedKey, ...otherKeys} = object;
				return otherKeys;
			},
			loadData(){
				this.$store.commit('setFullPageLoading',true);
				this.$http.get('/media/show/'+this.id).then(response=>{
					this.xml=response.data.xmlInfo;
					this.data= Object.assign(this.data,this.importFromXML(response));
					this.convertToArray(this.data);
					this.array_data=this.data.general.concat(this.data.physical);
					try{
						this.getBookImage(this.data,!this.data.description);
					}catch(e){}
				}).catch(error=>{
					console.error(error);
				}).then(()=>{
					this.$store.commit('setFullPageLoading',false);
				}).then(()=>{
					if(this.printing){
						this.expandContent(this.data,this.$route.query.contentExpanded=='true');
					}
				}).then(()=>{
					if(this.printing){
						this.printPage();
						window.close();
					}
				});
			},
			importFromXML(response){
				// need to have image in data
				let data={general:{},physical:{}};
				data=Object.assign(data,response.data.res);
				let res=response.data.res;
				data.general.title=res.title;
				data.general.title_reminder='';
				data.general.author=res.author;
				data.general.isbn=res.isbn;
				data.general.year=res.year;
				data.general.publisher= res.city+', '+res.publisher;
				data.general.series='';
				data.general.volume=res.volume;
				data.general.edition='';
				data.general.notes='';
				data.general.genre='';
				data.general.call_number=res.call_number;
				data.general.language=res.language;
				data.general.type=res.type;
				data.general.attribution='';
				data.general.link=this.link;

				let xml=this.xml;

				if(xml){
					data.description=this.getFromCatalog(xml,'520.a');
					let moreDescription=this.getFromCatalog(xml,'520.b');
					if(moreDescription){
						data.description+='<br>'+moreDescription;
					}
					data.content=this.getFromCatalog(xml,'505.a');
					this.expandContent(data,false);

					data.subject_terms=this.getFromCatalog(xml,'650.x');
					if(data.subject_terms){
						data.subject_terms=data.subject_terms.split(', ');						
					}

					data.general.attribution=this.getFromCatalog(xml,'245.c');
					data.general.title_reminder=this.getFromCatalog(xml,'245.b');
					data.general.series=this.getFromCatalog(xml,'490.a');
					data.general.volume=this.getFromCatalog(xml,'490.v');
					data.general.edition=this.getFromCatalog(xml,'250.a');
					data.general.notes=this.getFromCatalog(xml,'504.a');
					data.general.genre=this.getFromCatalog(xml,'655.a');

					data.physical.page_number=this.getFromCatalog(xml,'300.a');
					data.physical.dimensions=this.getFromCatalog(xml,'300.c');
					data.physical.accompany=this.getFromCatalog(xml,'300.e');
					data.physical.other=this.getFromCatalog(xml,'300.b');
				}
				
				return data;
			},
			getFromCatalog(xml,code){
				let str=''

				xml.filter(elem=>elem.id==code).forEach(elem=>{
					str+=elem.data+', ';
				});


				str= str.slice(0,-2);
				
				return str;
			},
			convertToArray(object){
				let general=[];
				let physical=[];
				for(let key in object.general){
					let obj={key:key,value:object.general[key]}
					general.push(obj)
				}
				for(let key in object.physical){
					let obj={key:key,value:object.physical[key]}
					physical.push(obj)
				}
				object.general=general;
				object.physical=physical;
				console.log(object);
			},
		},
		created(){
			if(!this.id){
				this.id=this.$route.query.id;
				if(!this.id){
					this.goTo('home');
				}
			}

			this.link=window.location.protocol+'//'+window.location.hostname+'/book_full?id='+this.id;

			if(!this.printing){
				this.printing=this.$route.query.mode;
			}

			this.loadData();
		}
	}
</script>
<style scoped>
.image{
	min-width:12em;
	max-width:12em;
	min-height:15em;
	max-height:15em;
	background-repeat: no-repeat;
	background-size: 100% 100%;
}
.bg-greyer{
	background: rgba(0,0,0, 0.3 ) !important;
}
.title{
	display:flex;
	align-items:flex-end;
}
.title>.text{
	padding-right:.3125em;
	font-size:1.5em;
}
.title>.tline{
	height:1px;
	flex:1;
	background:#DADADA;
	margin-bottom:.5em;
}
td{
	border-top: none;
	padding-left: 0;
}

.mh-100{
	min-height: 100%;
}

.content{
	max-width:1120px;
	width:100%;
	min-height: 100vh;
	height:100%;
}
.close_icon{
	color:white;
	position: absolute;
	top:8%;
	right:4%;
	font-size:2em;
}
.overflow-y-auto{
	overflow-y: auto;
}
@media screen and (max-width: 1300px) {
	.close_icon{
		top:1%;
		color:black;
		font-size:1.5em;
	}
}

td {
  padding-top: .5em;
  vertical-align: top;
}

td.text-grey{
	width:30%;
}
</style>