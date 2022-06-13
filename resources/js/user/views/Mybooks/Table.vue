<template>
     <table class="table my-5">
          <thead>
              <tr class="text-grey">
                  <th class="header text-center bg-lightgrey" v-for="(item, index) in heads" :key="index">
                      {{$t(item)}}
                  </th>
                  <th class="header text-center bg-lightgrey" v-if="!reserve">
                  </th>
                  <th class="header text-center bg-lightgrey"  v-if="reserve">
                  </th>
              </tr>
          </thead>
          <tbody>
              <tr v-for="(item,index) in data" :key="index">
                  <td class="text-center" v-for="(name,index) in heads" :key="index">
                      <span v-if="name=='status'"
                      :class="
                      [{'text-red':item[name]=='overdue' | item[name] == 'Expired'},
                      {'text-green':item[name]=='returned' | item[name] == 'In queue'}]"
                      >
                        {{item[name]}}
                      </span>
                      <span v-else>
                        {{item[name]}}
                      </span>
                  </td>
                  <td class="text-center" v-if="!reserve">
                      <button class="border border-green bg-white text-green" :disabled="item.status=='Expired'" @click="renewItem(item)">{{$t('renew')}}</button>
                  </td>
                  <td class="text-center" v-if="reserve">
                      <button class="border border-red bg-white text-red" :disabled="item.status=='Expired'" @click="cancelReserve(item)">{{$t('cancel')}}</button>
                  </td>
              </tr>
          </tbody>
      </table>
</template>
<script>
export default{
    props:{
        data:Array,
        heads:Array,
        reserve:Boolean
    },
    methods:{
        cancelReserve(book){
            this.$http.post('/media/cancel_reservation',{material_id:book.material_id})
        },
        renewItem(item){
            this.$http.post('/media/renew',{loan_id:item.loan_id,inv_id:item.inv_id,user_cid:this.$store.state.user.user_cid})
        }
    }
}
</script>
<style scoped>
table {
	border-collapse: collapse;
	width: 100%;
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

.table{
	position: relative;
	max-height: max(68vh,31.25em);
	overflow: auto;
	border-bottom:0.0625em solid #E8E8E8;
	border-top:0.0625em solid #E8E8E8;
}

.header{
	position: sticky;
	top: 0;
	border-top: none;
	z-index: 1;
}
</style>