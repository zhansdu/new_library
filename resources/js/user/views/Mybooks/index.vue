<template>
  <div class="padding mt-5">
      <div class="mt-3 font-size-32 font-weight-bold">
          {{$t('my_books')}}
      </div>
      <div class="d-flex justify-content-between mt-3">
          <div class="d-flex flex-column">
              <div class="bg-lightgrey rounded p-3">
                  <div class="image imageWidth imageHeight" :style="'background-image: url('+backgroundImage+')'"></div>
              </div>
              <div class="py-2 mt-auto bg-green d-flex flex-column align-items-center rounded-lg">
					<div class="imageWidth d-flex justify-content-between text-white" v-for="(value,key,index) in user.total" :key="index">
						<div>
							{{$t(key)+":"}}
						</div>
						<div>
							{{value}}
						</div>
					</div>
				</div>
          </div>
          <div class="bg-lightgrey rounded-lg p-3 ml-4 flex-fill" v-if='user.info'>
				<div class="d-flex" :class="{'mt-4':index!=0}" v-for="(value,key,index) in user.info" :key="index">
					<div class="text-grey">{{capitalize($t(key))}}:</div>
					<div class="ml-2">{{value}}</div>
					&nbsp;
				</div>
			</div>
      </div>
    
    <tabs-vue class="mt-5" :components="components"/>
  </div>
</template>
<script>
import TableDiv from "./Table.vue";
import TabsVue from "../../../common/components/Tabs.vue";
export default {
    components: { TableDiv,TabsVue },
    computed: {
        backgroundImage() {
            return this.user.photo;
        },
        components(){
            return[
                {
                    name:'my_books',
                    component:TableDiv,
                    props:{
                        heads:this.my_heads,
                        data:this.my_data
                    }
                },
                {
                    name:'my_reserves',
                    component:TableDiv,
                    props:{
                        heads:this.reserve_heads,
                        data:this.reserve_data,
                        reserve:true
                    }
                }
            ]
        }
    },
    data() {
        return {
            user: {},
            my_heads: [
                "issue_date",
                "authors",
                "barcode",
                "inv_id",
                "title",
                "status"
            ],
            my_data: [],
            reserve_heads:[
                "reservation_date",
                "author",
                "title",
                "status"
            ],
            reserve_data: []
        };
    },
    methods: {
        getInfo() {
            this.$http.get("/user/my-books").then(response => {
                this.user = response.data.res;
                this.user.info = objectWithoutKey(this.user.info, ["id", "user_cid"]);
                this.my_data = this.user.history;
            }).catch(e => {
                this.goTo("users");
            });
            this.$http.get('/user/reserve_list').then(response=>{
                this.reserve_data=response.data.res;
            })
        },
        objectWithoutKey(object, key) {
            return objectWithoutKey(object, key);
        },
        capitalize(string) {
            return capitalize(string);
        }
    },
    created() {
        this.getInfo();
    }
}
</script>
<style scoped>
.image{
	background-repeat: no-repeat;
	background-size: 100% 100%;
}
.imageWidth{
	width:13em;
}
.imageHeight{
	height: calc(13em * 4/3);
}
</style>
