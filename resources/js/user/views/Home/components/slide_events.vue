<template>
	<div ref="slide_items" class="position-relative d-none d-lg-flex align-items-center w-min-0 w-100">
		<div class="overflow-hidden w-100">
			<div ref="events" class="d-flex align-items-start transition">
				<div v-for="(event,index) in events" :key="index">
					<!-- <event-vue :event="event" :index="index" :events="events" :itemWidth="itemWidth"/> -->
					<event-v2 :event="event" :index="index" :events="events" :itemWidth="itemWidth" />
				</div>
			</div>
		</div>
		<div class="left_button cursor-pointer border border-black rotate" @click="move(-1)" v-if="current_item_number!=0">
			<right-little />
		</div>
		<div class="right_button cursor-pointer border border-black" @click="move(1)" 
			v-if = " events.length>this.number_shown && 
			current_item_number!=(events.length-this.number_shown) && 
			current_item_number!=events.length-1"
			>
			<right-little />
		</div>
	</div>
</template>
<script type="text/javascript">
// identication in sublime text 3
// icons
import RightLittle from '../../../../common/assets/icons/RightLittle'
import EventVue from './Event.vue'
import EventV2 from './Event.V2.vue'

export default{
	props:{
		events:{
			type:Array,
			default(){
				return [1,2,3,4,5]
			}
		},
		number_shown:{
			type:[Number,String],
			default(){
				return 3
			}
		},
		fixed_size_fraction:{
			type:[Number,String],
			default(){
				return 0
			}
		}
	},
	components:{RightLittle,EventVue,EventV2},
	data(){
		return{
			current_item_number:0,
			itemWidth:'300',
			fontSize:parseFloat(getComputedStyle(document.body).fontSize)
		}
	},
	watch:{
		events(){
			this.resize();
			this.setDates();
		}
	},
	methods:{
		setDates(){
			this.events.forEach(event=>{
				event.date=new Date(event.date);
				if(event.date_until){
					event.date_until=new Date(event.date_until);
				}
			})
		},
		move(num){
			let events = this.$refs['events'];
			if((this.current_item_number+num)>=0 && (this.current_item_number+num+this.number_shown)<=this.events.length){
				this.current_item_number+=num;
				events.style.transform="translateX("+(-this.current_item_number*this.itemWidth - this.current_item_number*this.fontSize*3 ) +'px) translateY(0px)';
			}
		},
		changeItemWidth(){
			setTimeout(()=>{
				let parent=this.$refs['slide_items'];
				let divide_num=0;
				if(this.fixed_size_fraction){
					this.itemWidth=((parent.offsetWidth - (this.number_shown-1)*(this.fontSize*3)))/this.fixed_size_fraction
				}
				else{
					if(this.number_shown<this.events.length){
						divide_num=this.number_shown;
					}
					else{
						divide_num=this.events.length;
					}
					this.itemWidth=((parent.offsetWidth - (this.number_shown-1)*this.fontSize*3))/divide_num;
				}
			},300)
		},
		resize(){
			this.changeItemWidth();
			setTimeout(()=>{
				this.move(0);
			},310)
		}
	},
	created(){
		this.setDates();
	},
	beforeMount(){
		window.addEventListener("resize", this.resize);
		this.changeItemWidth();
	},
	destroyed() {
		window.removeEventListener("resize", this.resize);
	}
}
</script>
<style scoped>
.right_button,.left_button{
	position: absolute;
	width:3em;
	height:3em;
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
	background-color: white;
	box-shadow: 0 0.125em 0.625em rgba(141, 155, 164, 0.32);
}
.right_button{
	right:-1.5em;
}
.left_button{
	left:-1.5em;
}
.fixed-size{
	width:2.125em;
	height:2.125em;
}

</style>