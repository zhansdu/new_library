<template>
    <form @submit.prevent="save(na)">
        <div class="p-3">
            <Tabs tabClasses="font-weight-normal font-size-18" :components="[{name:'en'},{name:'ru'},{name:'kz'}]" :tabOnClick="(tab)=>{changeLan(na,tab)}"/>
        </div>
        <div class="d-flex">
            <div class="p-2 col-6 border-right">
                <div class="pad w-100">
                    <input type="text" v-model="na[na.editing_lan].title"  required/>
                    <label class="placeholder required">{{$t('title')}}</label>
                </div>
                <div class="pad w-100 mt-2">
                    <input type="text" v-model="na[na.editing_lan].place" required/>
                    <label class="placeholder required">{{$t('place')}}</label>
                </div>
                <div class="pad w-100 mt-2">
                    <select v-model="na.type" required>
                        <option value="announcement">{{$t('announcement')}}</option>
                        <option value="event">{{$t('event')}}</option>
                    </select>
                    <label class="placeholder required">{{$t('type')}}</label>
                </div>
                <div class="pad w-100 mt-2 d-flex">
                    <input type="file" id="img" :value="na.img.details" @change="imgOnChange($event,na)">
                    <label class="placeholder">{{$t('background-image')}}</label>
                    <button type="button" class="ml-2 w-25" @click="clearImage(na)">{{$t('clear')}}</button>
                </div>
            </div>
            <div class="p-2 col-6">
                <div class="pad w-100">
                    <input type="date" v-model="na.date" required/>
                    <label class="placeholder required">{{$t('date')}}</label>
                </div>
                <div class="pad w-100 mt-2">
                    <input type="time" v-model="na.time_from" required/>
                    <label class="placeholder required">{{$t('time_from')}}</label>
                </div>
                <div class="pad w-100 mt-2">
                    <input type="time" v-model="na.time_until" />
                    <label class="placeholder">{{$t('time_until')}}</label>
                </div>
                <div class="pad w-100 mt-2">
                    <input type="date" v-model="na.date_until" />
                    <label class="placeholder">{{$t('date_until')}}</label>
                </div>
            </div>  
        </div>
        <div class="pad mt-2 mx-2" v-if="na.type=='event'">
            <input type="text" v-model="na.libguide_link" required>
            <label class="placeholder required">{{$t('libguide_link')}}</label>
        </div>
        <div class="d-flex flex-column" v-else>
            <div class="p-2">
                <div class="pad w-100">
                    <quill-editor class="editor" :options="editorOption" v-model="na[na.editing_lan].description"/>
                    <label class="placeholder required">{{$t('description')}}</label>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column align-items-start p-2">
            <div class="col-12">
                <div class="d-flex justify-content-between col-8" v-if="na.create_date">
                    <div>{{$t('create_date') + ' : ' + new Date(na.create_date).toDateInputValue()}}</div>
                    <div>{{$t('created_by') + ' : ' + na.create_account.name + ' ' + na.edit_account.surname}}</div>
                </div>
                <div class="d-flex justify-content-between col-8" v-if="na.edit_date">
                    <div>{{$t('edit_date') + ' : ' + new Date(na.edit_date).toDateInputValue()}}</div>
                    <div>{{$t('edited_by') + ' : ' + na.edit_account.name + ' ' + na.edit_account.surname}}</div>
                </div>
            </div>
        </div>
         <div class="d-flex justify-content-end mt-2">
            <div class="mr-2">
                <button type="button" @click="$emit('close')">{{$t('cancel')}}</button>
            </div>
            <div>
                <button type="submit">{{$t('save')}}</button>
            </div>
        </div>
    </form>
</template>
<script>
// quill editor
import 'quill/dist/quill.snow.css'
import htmlEditButton from "quill-html-edit-button";

import { Quill, quillEditor } from 'vue-quill-editor'
Quill.register("modules/htmlEditButton", htmlEditButton);

var toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  ['blockquote', 'code-block'],

  [{ 'header': 1 }, { 'header': 2 }],               // custom button values
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
  [{ 'direction': 'rtl' }],                         // text direction

  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
  [{ 'font': [] }],
  [{ 'align': [] }],

  ['image','video']
];
// /quill editor

import TabsVue from "../../../common/components/Tabs.vue"

import {save} from '../../mixins/website'
import { message_success } from '../../mixins/messages';

import { mapGetters } from 'vuex';

export default {
    mixins:[save,message_success],
    computed:{
        ...mapGetters(['website'])
    },
    components:{
        Tabs:TabsVue,
        quillEditor,
    },
    data(){
        return{
            na:{
                extending:false,
                create_date: new Date(),
                create_account: this.$store.getters.user,
                edit_account:this.$store.getters.user,
                type:'announcement',
                editing_lan:'en',
                img:{},
                en:{
                    title:''
                },
                kz:{
                    title:''
                },
                ru:{
                    title:''
                }
            },
            editorOption: {
                modules:{
                    toolbar:toolbarOptions,
                    htmlEditButton:{}
                }
            }
        }
    },
    methods:{
        imgOnChange(e,na){
            async function reduce_image_file_size(base64Str, MAX_WIDTH = 450, MAX_HEIGHT = 450) {
                let resized_base64 = await new Promise((resolve) => {
                    let img = new Image()
                    img.src = base64Str
                    img.onload = () => {
                        let canvas = document.createElement('canvas')
                        let width = img.width
                        let height = img.height

                        if (width > height) {
                            if (width > MAX_WIDTH) {
                                height *= MAX_WIDTH / width
                                width = MAX_WIDTH
                            }
                        } else {
                            if (height > MAX_HEIGHT) {
                                width *= MAX_HEIGHT / height
                                height = MAX_HEIGHT
                            }
                        }
                        canvas.width = width
                        canvas.height = height
                        let ctx = canvas.getContext('2d')
                        ctx.drawImage(img, 0, 0, width, height)
                        resolve(canvas.toDataURL()) // this will return base64 image results after resize
                    }
                });
                return resized_base64;
            }
            function getBase64(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => resolve(reader.result);
                    reader.onerror = error => reject(error);
                });
            }
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            getBase64(files[0]).then(async (data)=>{
                na.img.data=await reduce_image_file_size(data);
                this.message_success('loading',{});
            });
        },
        clearImage(na){
            na.img={};
        },
        changeLan(na,tab){
            na.editing_lan=tab.name;
        }
    }
}
</script>