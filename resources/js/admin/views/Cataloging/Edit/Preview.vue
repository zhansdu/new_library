<template>
	<div class="bg-greyer padding mh-100 overflow-auto">
		<div class="d-flex flex-column bg-white py-4 px-5 min-vh-100">
            <div class="align-self-center font-size-24 font-weight-bolder">{{$t('preview').toUpperCase()}}</div>
            <div class="text-grey font-size-12 mr-3">{{$t('section_tag')}}</div>
            <div class="d-flex align-items-start my-2" v-for="(section,index) in sections" :key="index">
                <div class="outline-orange px-3 py-2 rounded mr-3">{{section.tag}}</div>
                <div class="flex-fill border rounded py-2 px-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    {{$t('title')}}
                                </th>
                                <th>
                                    {{$t('subtags')}}
                                </th>
                                <th>
                                    {{$t('ind1')}}
                                </th>
                                <th>
                                    {{$t('ind2')}}
                                </th>
                                <th>
                                    {{$t('data')}}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(info,index) in section.data" :key="index">
                                <td class="w-25">
                                    {{info.title}}
                                </td>
                                <td class="little_td">
                                    {{info.field_code}}
                                </td>
                                <td class="little_td">
                                    {{info.ind1}}
                                </td>
                                <td class="little_td">
                                    {{info.ind2}}
                                </td>
                                <td class="w-25">
                                    {{info.data}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
		</div>
		<!-- fixed stuff -->
		<div class="exit cursor-pointer" @click="closeModal()">
			<X/>
		</div>
	</div>
</template>
<script type="text/javascript">
	import {goTo} from '../../../mixins/goTo'
	import X from '../../../../common/assets/icons/X'
	export default{
		props:{
			info:Array
		},
		mixins:[goTo],
		components:{X},
        data(){
            return{
                sections:[]
            }
        },
		methods:{
            makeSections(){
                let arr=[];
                this.info.forEach(section=>{
                    section.tags.forEach(tag=>{
                        let sec={
                            tag:tag.field_code,
                            data:tag.data
                        }
                        arr.push(sec);
                    })
                })
                this.sections=arr;
            },
			closeModal(){
				this.$emit('close');
				document.documentElement.classList.remove('overflow-hidden');
			}
		},
        created(){
            this.makeSections();
        }
	}
</script>
<style scoped>
.outline-orange:hover{
    color:inherit;
    border:inherit;
}
.padding{
	padding-right: 11vw;
	padding-left: 11vw;
	transition: padding .3s;
}
.bg-greyer{
	background: #a2a6a8;
}
.exit{
	color:white;
	position: absolute;
	top:10%;
	right:4%;
    font-size: 4em;
}
.mh-100{
	min-height: 100%;
}
.min-vh-100{
	padding-top: 5% !important;
}
td{
	border-top: 1px solid #E8E8E8;
}
th {
	text-align: left;
	font-weight: 500;
    border: none !important;
}
.little_td{
    width:7%;
}
</style>