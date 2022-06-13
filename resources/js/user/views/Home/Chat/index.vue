<template>
	<div ref="parent" class="chat_parent bg-white hoverable">
		<div class="d-flex align-items-center cursor-pointer h-100" @click="showChat()" v-if="!chatShown">
			<span class="d-flex text-black font-size-20 justify-content-center align-items-center flex-shrink-0 chat_icon">
				<component :is="Chat"/>
			</span>
			<span refs="ask" class="mr-3 text-no-wrap ml-3">{{$t('ask_a_librarian')}}</span>
		</div>
		<div class="h-100 d-flex flex-column w-0" :class="{'w-100':chatShown}">
			<div class="d-flex justify-content-between p-4">
				<div class="font-weight-bold">{{$t('chat_librarian')}}</div>
				<div @click="close()" class="cursor-pointer"><Times /></div>
			</div>
			<div class="height-1 w-100 bg-lightgrey"/>
			<div class="align-self-start mt-40 w-100 h-100" style="z-index: 1;">
				<div class="d-none border border-width h-100" id="libchat_591323eae0c67c543ac18bf22cf2e1a7" :class="{'d-block':$i18n.locale=='en'}"/>
				<div class="d-none border border-width h-100" id="libchat_26182d2d0a7628dba14f5685b439f7b5" :class="{'d-block':$i18n.locale=='ru'}"/>
				<div class="d-none border border-width h-100" id="libchat_2bd2632bd2b55389a65a46993bf9f779" :class="{'d-block':$i18n.locale=='kz'}"/>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
// icons
import Chat from '../../../../common/assets/icons/Chat'
import Times from '../../../../common/assets/icons/X'

// mixins
import {chatScripts,loadScript} from '../../../mixins/libguide'

export default{
	mixins:[loadScript],
	components:{
		Times
	},
	data(){
		return{
			Chat,
			chatShown:false,
		}
	},
	methods:{
		showChat(){
			this.chatShown=true;
			this.$refs['parent'].classList.remove('hoverable');
			this.$refs['parent'].classList.add('chat');
		},
		close(){
			this.chatShown=false;
			this.$refs['parent'].classList.remove('chat');
			setTimeout(()=>{
				this.$refs['parent'].classList.add('hoverable');
			},400);
		},
		loadExternalLibguideScripts(){
			chatScripts.forEach(item=>{
				this.loadScript(item)
			})
		}
	},
	created(){
		this.loadExternalLibguideScripts();
	}
}	
</script>
<style scoped>
.chat_parent{
	position: fixed;
	bottom:3.125em;
	right:3.125em;
	z-index: 10;
	max-width: 3.75em;
	height:3.75em;
	overflow: hidden;
	border-radius: 3.125em;
	box-shadow: 0 0.25em 0.625em #434343;
	transition:400ms;
}
.hoverable{
	width:unset;
}
.hoverable:hover{
	max-width: 18em;
}
.hoverable:hover .chat_icon{
	transform:rotate(-360deg);
	color:white !important;
	background-color: #9E2629;
}
.chat_icon{
	width: 3.125em;
	height:3.125em;
	border-radius: 3.125em;
	transition:400ms;
	color: #9E2629;
}

.chat{
	height: 26em;
	width: 25em;
	background-color: white;
	border-radius: 0;
	border-top-right-radius: 1.875em;
	border-top-left-radius: 1.875em;
	box-shadow: 0 0.25em 1.875em #434343;
	max-width: 62.5em;
}
.w-0{
	width:0;
}
</style>