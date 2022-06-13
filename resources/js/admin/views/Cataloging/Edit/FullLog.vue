<template>
    <div>
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
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data, index) in data" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td v-for="(name, i) in heads" :key="i">
                            {{ data[name.link] }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-2">
            <div class="pad">
                <button
                    type="button"
                    class="outline-black"
                    @click="$emit('close')"
                >
                    {{ $t("ok") }}
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import CaretUp from "../../../../common/assets/icons/CaretUp";
import { message_error } from '../../../mixins/messages';
export default {
    props: {
        info: Object
    },
    mixins:[message_error],
    components: {
        CaretUp
    },
    data() {
        return {
            heads: [
                { name: "create_date", link: "made_at" },
                { name: "created_by", link: "made_by" }
            ],
            data: {}
        };
    },
    methods: {
        getData() {
            this.$http
                .get(
                    "/cataloging/material/" +
                        this.info.type_key +
                        "/" +
                        this.info.id +
                        "/history"
                )
                .catch(error=>{
                    this.message_error('loading',error)
                })
                .then(response => {
                    this.data = response.data.res;
                });
        }
    },
    created() {
        this.getData();
    }
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
</style>
