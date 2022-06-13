<template>
	<div>
		<search class="padding mt-5 py-4"/>
		<results class="mt-5 py-4" v-if="searching"/>
	</div>
</template>
<script type="text/javascript">
// components

import search from './search'
import results from './results'

import {mapGetters} from 'vuex'

export default{
	components:{search,results},
	computed:{
		...mapGetters(['searching'])
	}
}
</script>