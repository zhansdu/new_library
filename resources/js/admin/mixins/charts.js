export default {
    data() {
        return {
            options: {
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    line: {
                        fill: false
                    },
                    point: {
                        backgroundColor: '#FFF'
                    }
                }
            },
            weekLabels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            monthLabels: ['January', 'February', 'March', 'April', 'May', 'June', 'Jule', 'August', 'September', 'October', 'November', 'December'],
        }
    },
    methods: {
        withOptions(options) {
            return Object.assign({}, this.options, options);
        }
    }
};
export const lineOptions = {
    data() {
        return {
            lineOptions: {
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: Math.round,
                    },
                    labels: false
                }
            }
        }
    }
}

export const PieOptions = {
    data() {
        return {
            pieOptions: {
                responsive: true,
                legend: {
                    display: true,
                    position: 'bottom',
                },
            }
        }
    }
}