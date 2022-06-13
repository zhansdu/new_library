<template>
	<form action="https://searchbox.ebsco.com/search/" target="_blank" class="d-flex">
		<input name="schemaId" value="search" type="hidden" />
		<input name="custid" value="ns242285" type="hidden" />
		<input name="groupid" value="main" type="hidden" />
		<input name="profid" value="eds" type="hidden" />
		<input name="authtype" value="ip,guest" type="hidden" />
		<input name="scope" value="site" type="hidden" />
		<input name="site" value="eds-live" type="hidden" />
		<input name="direct" value="true" type="hidden" />
		<input name="bquery" type="text" class="w-100 border-grey no-border-right no-border-right-radius mr--5" :placeholder="$t('search_eresources')">
		<button type="submit" class="p-3 w-min-120 bg-black" v-if="!$mobileCheck()">{{$t('search')}}</button>
	</form>
</template>
<script></script>
