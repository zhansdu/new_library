export default {
    methods: {
        showModal(component, props) {
            let width = props ? props.width ?? '66.5%' : '66.5%';
            let height = props ? props.height ?? 'auto' : 'auto';
            let styles = props ? props.styles ?? 'padding:0.9375em;' : 'padding:0.9375em;';
            this.$modal.show(component, props, {
                width: width,
                height: height,
                classes: ['rounded'],
                styles: styles,
                shiftY: 0.3
            }, {});
        }
    }
}