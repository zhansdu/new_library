export const download_file = {
    methods: {
        download_file(response, name, extension) {
            let now = new Date;

            name += '_' + now.getFullYear() + '.' + now.getMonth() + '.' + now.getDate() + '_' + now.getHours() + '.' + now.getMinutes() + '.' + extension;


            const url = window.URL.createObjectURL(new Blob([response.data]));

            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', name);
            document.querySelector('#app').appendChild(link);
            link.click();

        }
    }
}
export const print_file = {
    methods: {
        print_file(response) {
            const url = window.URL.createObjectURL(new Blob([response.data], { type: "application/pdf" }));
            let w = window.open(url);
            w.print();
        }
    }
}