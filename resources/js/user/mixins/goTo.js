export const goTo = {
    methods: {
        goTo(place, props) {
            if (this.$route.name != place) {
                this.$router.push({ name: place, params: props });
                this.$emit('close');
            }
        }
    }
}
export const scrollTo = {
    methods: {
        scrollTo(link, yOffset) {
            try {
                var yLen = yOffset || 0;
                const element = document.getElementById(link);
                const y = element.getBoundingClientRect().top + window.pageYOffset + yLen;
                window.scrollTo({ top: y, behavior: 'smooth' });
            } catch (e) {}
        }
    }
}