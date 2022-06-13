export default {
    setStore(store, data) {
        for (let key in data.data) {
            store.state[data.label][key] = data.data[key];
        }
    },
}