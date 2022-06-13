import { goTo, scrollTo } from './goTo';
export const filters = {
    methods: {
        setFilters(request) {
            let filter = this.$store.getters.filter_search;
            let filter_data = this.$store.getters.filter_data;
            let filters = [];

            if (filter.types.length > 0 && (filter_data.type_key.length != filter.types.length)) {
                let types = [{ key: 'type_key', value: filter.types.map(type => type.value) }];
                filters = filters.concat(types);
            }
            if (filter.languages.length > 0 && (filter_data.language.length != filter.languages.length)) {
                let languages = [{ key: 'language', value: filter.languages }];
                filters = filters.concat(languages);
            }
            if (filter.year) {
                if (filter.year.value != '') {
                    let year = [{ key: 'year', value: filter.year.value }];
                    filters = filters.concat(year);
                }
            }

            if (filters.length > 0) {
                request.filter = filters;
            }
        }
    }
}
export const selectData = {
    data() {
        return {
            select_data: [
                {
                    key: 'title',
                    name: 'title'
                },
                {
                    key: 'all',
                    name: 'all_search'
                },
                {
                    key: 'author',
                    name: 'author'
                },
                {
                    key: 'publisher',
                    name: 'publisher'
                },
                {
                    key: 'isbn',
                    name: 'isbn'
                },
                {
                    key: 'call_number',
                    name: 'call_number'
                }
            ]
        }
    }
}
export const getBookImage = {
    methods: {
        getBookImage(info, description) {
            // we use fetch() because there's cors mistake when use this.$http
            this.$store.commit('setFullPageLoading', true);
            fetch("https://www.googleapis.com/books/v1/volumes?q=isbn:" + info.isbn).then(response => {
                response.json().then(async data => {
                    try {
                        if (description) {
                            info.description = data.items[0].volumeInfo.description;
                        }
                        info.image = data.items[0].volumeInfo.imageLinks.thumbnail;
                    } catch (e) {
                        await fetch("https://www.googleapis.com/books/v1/volumes?q=isbn:0" + info.isbn).then(response => {
                            response.json().then(data => {
                                try {
                                    if (description) {
                                        info.description = data.items[0].volumeInfo.description;
                                    }

                                    info.image = data.items[0].volumeInfo.imageLinks.thumbnail;
                                } catch (e) {
                                    info.image = '';
                                }
                            })
                        })
                    }
                }).catch(e => { console.error(e) }).finally(() => {
                    this.$store.commit('setFullPageLoading', false)
                })
            })
        }
    }
}
export const performSearch = {
    mixins: [goTo, scrollTo],
    methods: {
        performSearch(request, query, options) {
            this.$store.commit('setFullPageLoading', true);
            this.$http.post('media/search', request).then(response => {
                this.$store.dispatch('setSearches', response);
                this.$store.commit('setQuery', '"' + query + '"')
                this.$store.commit('setSearchRequest', options);
                this.$store.commit('setSearching', true)
            }).catch(() => {
                this.$store.commit('setResults', {});
            }).then(() => {
                this.goTo('search');
                this.$store.commit('setFullPageLoading', false);
                setTimeout(() => { this.scrollTo('results') }, 100);
            })
        }
    }
}