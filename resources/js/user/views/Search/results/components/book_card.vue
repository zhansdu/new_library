<template>
	<div class="d-flex border-top py-3">
		<div class="mr-5 flex-grow-1 flex-sm-grow-0">
			<div class="d-flex">
				<checkbox :checked="selected.data.includes(data.id)" @change="checkBook()"/>
				<div class="d-flex d-sm-none text-center text-blue ml-3">
					<div class="rounded-lg bg-lightblue p-1 px-3" v-if="data.type">{{$t(data.type)}}</div>
					<div class="rounded-lg bg-lightblue p-1 px-3 ml-3" v-if="data.call_number">{{data.call_number}}</div>
				</div>
			</div>
			<div>
				<div class="d-flex">
					<div class="image rounded bg-grey mt-3" :style="'background-image: url('+this.info.image+')'"></div>
					<div class="d-block d-sm-none ml-3 mt-3" >
						<div class="bg-lightgrey rounded-lg p-2 text-no-wrap">
							{{data.availability}}
							{{$t('availability')}}
						</div>
					</div>
				</div>
				<div class="d-block d-sm-none mt-3 overflow-hidden title font-weight-bold font-size-24 cursor-pointer" @click="showMore()">{{data.title}}</div>
			</div>
		</div>
		<div class="d-none d-sm-block flex-grow-1">
			<div class="d-flex text-center text-blue">
				<div class="rounded-lg bg-lightblue p-1 px-3" v-if="data.type">{{$t(data.type)}}</div>
				<div class="rounded-lg bg-lightblue p-1 px-3 ml-3" v-if="data.call_number">{{data.call_number}}</div>
			</div>
			<div class="mt-3 overflow-hidden title font-weight-bold font-size-24 cursor-pointer" @click="showMore()">{{data.title}}</div>
			<div class="text-grey mt-2">
				<div v-if="data.author">{{$t('author')}}: {{data.author}}</div>
				<div v-if="data.publisher">{{$t('publisher')}}: {{data.publisher}}</div>
				<div v-if="data.year">{{$t('year')}}: {{data.year}}</div>
			</div>
		</div>
		<div class="d-none d-sm-block text-center col-2 px-0">
			<div class="bg-lightgrey rounded-lg p-2 text-no-wrap">
				{{data.available}} / {{data.total}}
				{{$t('availability')}}
			</div>
			<div class="position-relative mt-4" v-if="data.available==0">
				<Reserve class="reserve transition" :class="{shown:reserve_shown}" :change_shown="reserve"  />
				<button class="bg-white border border-green text-green px-0 w-100" @click="reserve()">{{$t('reserve')}}</button>
			</div>
			<button class="bg-white border border-grey text-grey px-0 w-100 mt-4" @click="showMore()">{{$t('show_details')}}</button>
		</div>
	</div>
</template>
<script type="text/javascript">
	import Checkbox from '../../../../components/checkbox'
	import Full from '../../../Full/Book'
	import Reserve from './reserve'

	import {getBookImage} from '../../../../mixins/search'
	import showModal from '../../../../mixins/modal'

	import {mapGetters} from 'vuex'
	export default{
		components:{
			Checkbox,
			Reserve
		},
		mixins:[getBookImage,showModal],
		props:{
			data:Object
		},
		data(){
			return{
				info:{
					image:'',
					isbn:''
				},
				reserve_shown:false,
				Reserve
			}
		},
		computed:{
			...mapGetters(['selected'])
		},
		watch:{
			data(){
				this.info.isbn=this.data.isbn;
				this.getBookImage(this.info);
			}
		},
		methods:{
			checkBook(){
				let id=this.data.id;
				if(this.selected.data.includes(id)){
					this.selected.data=this.selected.data.filter(item=>item!=id);
				}
				else{
					this.selected.data.push(id);
				}
			},
			showMore(){
				this.showModal(Full,{id:this.data.id,modal:true,width:'100%',height:'100%',styles:'overflow:hidden;background:transparent'});
				document.documentElement.classList.add("overflow-hidden");
			},
			reserve(){
				this.reserve_shown=!this.reserve_shown;
			}
		},
		created(){
			this.info.isbn=this.data.isbn;
			this.getBookImage(this.info);
		}
	}
</script>
<style scoped>
.image{
	min-width:8.75em;
	max-width:8.75em;
	min-height:10em;
	max-height:10em;
	background-size: 100% 100%;
}
.title{
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	text-overflow: ellipsis;
}
.reserve{
	position:absolute;
	overflow: hidden;
	bottom:100%;
	right:100%;
	width:0;
	max-height:0;
	border-radius:0.3125em;
}
.shown{
	width:18em;
	max-height:18em;
}
</style>