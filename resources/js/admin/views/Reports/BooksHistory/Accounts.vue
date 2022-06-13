<template>
    <div class="table">
            <table class="text-choosable">
                <thead>
                    <tr class="text-grey">
                        <th class="header text-center bg-lightgrey">
                            #
                        </th>
                        <th
                            class="header text-center bg-lightgrey cursor-pointer"
                            v-for="(name, index) in heads"
                            :key="index"
                            @click="sort(name.link, name.reverse, index)"
                        >
                            {{ $tc(name.name, 1) }}
                            <span>
                                <!-- for sorting -->
                                <CaretUp
                                    class="transition"
                                    :class="{ rotate: !name.reverse }"
                                />
                            </span>
                        </th>
                        <th class="header bg-lightgrey"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data, index) in data" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td v-for="(name, i) in heads" :key="i">
                            {{ data[name.link] }}
                        </td>
                        <td>
                            <button @click="showMore(data)">{{$t('show_more')}}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
</template>
<script type="text/javascript">
// icons
import CaretUp from "../../../../common/assets/icons/CaretUp";

// mixins
import showModal from "../../../mixins/showModal";

//components
import AccountVue from "./Account.vue";

export default {
    components: {
        CaretUp
    },
    mixins:[showModal],
    props: {
        id: String,
        data:Array,
        heads:Array
    },
    methods: {
        // main sorting
        sort(field, reverse, index) {
            if (reverse == null) {
                if (index != null) {
                    this.heads[index].reverse = false;
                } else {
                    this.statusReverse = false;
                }
            }
            let array = [];
            array = this.data;
            array.sort(this.sortBy(field, reverse));
            if (index != null) {
                this.heads[index].reverse = !this.heads[index].reverse;
            } else {
                this.statusReverse = !this.statusReverse;
            }
            this.sorting = field;
        },
        // helping function ( validation)
        sortBy(field, reverse) {
            let validate = a => {
                let x = copy(a);
                if (x == null) {
                    x = !reverse ? "z" : "-999999999999";
                    return x;
                }
                if (isNaN(x)) {
                    x = x.toUpperCase();
                } else {
                    x = parseInt(x);
                }
                return x;
            };
            return (a, b) => {
                a = validate(a[field]);
                b = validate(b[field]);
                return reverse ? (a < b) - (b < a) : (b < a) - (a < b);
            };
        },
        showMore(data){
            this.showModal(AccountVue,{
                info:data
            })
        }
    },
};
</script>
<style scoped>
table {
    border-collapse: collapse;
    width: 100%;
}

td,
th {
    border: 0.0625em solid #e8e8e8;
    padding: 1em 1.25em;
}

th {
    text-align: left;
    font-weight: 500;
}

tbody tr:hover {
    box-shadow: 0 0 0.4375em rgba(8, 38, 115, 0.2);
}

input {
    width: unset;
    height: unset;
}

.table {
    position: relative;
    max-height: max(68vh, 31.25em);
    overflow: auto;
    border-bottom: 0.0625em solid #e8e8e8;
    border-top: 0.0625em solid #e8e8e8;
}

.header {
    position: sticky;
    top: 0;
    border-top: none;
    z-index: 1;
}
</style>