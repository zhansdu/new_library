export default {
    setFullPageLoading(state, data) {
        state.fullPageLoading = data;
    },
    setUser(state, data) {
        state.user = data;
    },
    setQueryName(state, data) {
        state.search.query.name = data;
    }
}