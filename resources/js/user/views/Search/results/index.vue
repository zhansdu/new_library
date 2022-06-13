<template>
	<div id="results">
		<div class="d-flex padding font-weight-bold font-size-24">
			<span>{{$t('results_for')}}: </span>
			<span class="text-orange ml-2">{{query}}</span>
		</div>
		<div class="padding bg-lightgrey d-flex mt-3 py-3">
			<div class="d-flex align-items-center filter-width">
				<span>{{results.total}} {{$t('results')}},</span>
				<span class="ml-2">{{results.last_page}} {{$t('pages')}}</span>
			</div>
			<div class="d-flex justify-content-between flex-fill">
				<div class="d-flex align-items-center">
					<checkbox :checked="selected.all && selected.data.length>0" @change="selectAll()"/>
					<span class="ml-2">{{$t('select_all') + ' (' + selected.data.length+')'}}</span>
				</div>
				<div>
					<button @click="saveExcel()">{{$t('export_all')}}</button>
				</div>
			</div>
		</div>
		<div class="padding d-flex align-items-start mt-3">
			<filter-div	class="d-none d-md-block flex-shrink-0" />
			<div class="flex-grow-1">
				<book-card :data="result" v-for="(result,index) in results.data" :key="index"/>
				<pagination />
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
	import FilterDiv from './components/filter'
	import BookCard from './components/book_card'
	import Pagination from './components/pagination'
	
	import Checkbox from '../../../components/checkbox'
	
	import saveExcel from '../../../mixins/saveExcel'
	
	import {mapGetters} from 'vuex'
	export default{
		components:{
			FilterDiv,
			BookCard,
			Pagination,
			Checkbox
		},
		mixins:[saveExcel],
		computed:{
			...mapGetters(['query','results','selected'])
		},
	}
</script>