import { message_success, message_error } from './messages'
export default {
    mixins: [message_success, message_error],
    methods: {
        readFromRfid(link, data, after) {
            const request = new XMLHttpRequest();
            let error_m = 'read_from_rfid';

            const url = 'http://localhost/LibraryWebService/LibraryWebService.asmx/' + link;
            request.open('POST', url, false);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            request.addEventListener("readystatechange", () => {
                if (request.readyState === 4 && request.status === 200) {
                    if (after != null) {
                        let convert = require('xml-js');
                        let json = convert.xml2json(request.responseText, { compact: true, spaces: 4 });
                        json = JSON.parse(json);
                        after(json);
                    }
                } else {
                    this.message_error(error_m, {})
                    throw 'rfid problem'
                }
            });
            request.addEventListener('error', () => {
                this.message_error(error_m, {})
                throw 'rfid problem'
            })

            try {
                request.send(data);
            } catch (e) {
                this.message_error(error_m, {})
                throw 'rfid problem'
            }
        }
    }
}