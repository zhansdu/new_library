export default {
    methods:{
        xml2json(xml){
            let convert= require('xml-js');
            let json=convert.xml2json(xml,{compact:true,spaces:4});
            json=JSON.parse(json);
            return json
        }
    }
}