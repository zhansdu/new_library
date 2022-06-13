export const setTheme = {
    methods: {
        setTheme(theme) {
            let id = 'theme'
            if (theme) {
                if (document.getElementById(id)) {
                    document.getElementById(id).remove();
                }
                var head = document.getElementsByTagName('head')[0];
                var link = document.createElement('link');
                link.id = id;
                link.rel = 'stylesheet';
                link.type = 'text/css';
                link.href = '/css/' + theme + '_theme.css';
                link.media = 'all';
                head.appendChild(link);
                this.$store.state.theme = theme;
                localStorage.setItem('theme', theme);
            }
        }
    }
}