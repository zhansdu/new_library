<template>
    <div class="d-flex">
        <div class="bg-white mt-2 w-100 py-3 px-4">
            <div class="mb-3">
                <back />
            </div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div>{{ $t("management", { info: info.title }) }}</div>
                    <div>{{ $t("state") + ": " + $t(state) }}</div>
                </div>
                <div class="d-flex">
                    <button
                        class="outline-black mx-3"
                        @click="printCallNumber()"
                    >
                        <Print class="mr-2" />
                        {{ $t("print_call_number") }}
                    </button>
                    <button class="outline-black" @click="preview()">
                        {{ $t("preview") }}
                    </button>
                    <button
                        class="outline-black mx-3"
                        @click="importFromGoogleAPIs(info.isbn)"
                    >
                        {{ $t("import_from_google_api") }}
                    </button>
                    <button class="outline-black" @click="exportXML()">
                        {{ $t("export_to_xml") }}
                    </button>
                </div>
            </div>
            <div class="d-flex mt-5">
                <div class="text-center px-2 py-3 border rounded">
                    <div class="text-grey font-size-14 py-2 my-1">
                        {{ $t("section") }}
                    </div>
                    <div
                        class="py-2 my-1 cursor-pointer"
                        :class="{
                            'px-3 border rounded border-orange text-orange':
                                sectionSelected == index
                        }"
                        @click="selectSection(section)"
                        v-for="(section, index) in sectioned"
                        :key="index"
                    >
                        {{ section.section }}
                    </div>
                </div>
                <div class="p-3 ml-3 border rounded flex-fill">
                    <div
                        class="d-flex align-items-center justify-content-between"
                    >
                        <div>
                            <div class="text-grey font-size-14 py-2 my-1">
                                {{ $t("section_tag") }}
                            </div>
                            <div class="d-flex">
                                <div
                                    class="rounded px-3 py-2 font-weight-bold bg-lightgrey text-grey cursor-pointer"
                                    :class="[
                                        {
                                            'bg-orange text-white':
                                                tagSelected.field_code ==
                                                tag.field_code
                                        },
                                        { 'ml-2': index != 0 }
                                    ]"
                                    @click="tagSelected = tag"
                                    v-for="(tag, index) in sectioned[
                                        sectionSelected
                                    ].tags"
                                    :key="index"
                                >
                                    {{ tag.field_code }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center font-weight-bold">
                        {{ tagSelected.title }}
                    </div>
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>
                                    {{ $t("title") }}
                                </th>
                                <th>
                                    {{ $t("subtags") }}
                                </th>
                                <th>
                                    {{ $t("ind1") }}
                                </th>
                                <th>
                                    {{ $t("ind2") }}
                                </th>
                                <th>
                                    {{ $t("data") }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(info, index) in tagSelected.data"
                                :key="index"
                            >
                                <td class="td_no_input w-25">
                                    <div
                                        v-if="
                                            info.is_added ||
                                                info.repeatable == undefined
                                        "
                                    >
                                        &nbsp;
                                    </div>
                                    <div v-else>
                                        {{ info.title }}
                                    </div>
                                </td>
                                <td class="little_td">
                                    <input
                                        type="text"
                                        class="little_input"
                                        v-model="info.field_code"
                                        disabled
                                    />
                                </td>
                                <td class="little_td">
                                    <input
                                        type="text"
                                        class="little_input"
                                        v-model="info.ind1"
                                    />
                                </td>
                                <td class="little_td">
                                    <input
                                        type="text"
                                        class="little_input"
                                        v-model="info.ind2"
                                    />
                                </td>
                                <td class="w-50">
                                    <!-- here comes cases -->
                                    <!-- if it's language -->
                                    <input-div
                                        v-model="info.data"
                                        classes="border-black input_static_height"
                                        :selectable="{
                                            available: true,
                                            data: authority.authors
                                        }"
                                        :head="'author'"
                                        :body="'author'"
                                        :autocomplete="{
                                            available: true,
                                            data: authority.authors
                                        }"
                                        :showBody="false"
                                        v-if="
                                            info.id == '100.a' ||
                                                info.id == '600.a'
                                        "
                                    />

                                    <input-div
                                        v-model="info.data"
                                        classes="border-black input_static_height"
                                        :selectable="{
                                            available: true,
                                            data: publishers
                                        }"
                                        :head="'name'"
                                        :body="'name'"
                                        :autocomplete="{
                                            available: true,
                                            data: publishers
                                        }"
                                        :showBody="false"
                                        v-else-if="info.id == '260.b'"
                                    />

                                    <textarea
                                        class="text-wrap"
                                        v-model="info.data"
                                        v-else-if="
                                            info.id == '520.a' ||
                                                info.id == '520.b' ||
                                                info.id == '505.a' ||
                                                info.id == '245.a'
                                        "
                                    />

                                    <input-div
                                        v-model="info.data"
                                        classes="border-black input_static_height"
                                        :selectable="{
                                            available: true,
                                            data: authority.languages
                                        }"
                                        :head="'title'"
                                        :body="'language'"
                                        :autocomplete="{
                                            available: true,
                                            data: authority.languages
                                        }"
                                        :showBody="true"
                                        v-else-if="info.id == '546.a'"
                                    />
                                    
                                    <input-div
                                        v-model="info.data"
                                        classes="border-black input_static_height"
                                        :selectable="{
                                            available: true,
                                            data: types
                                        }"
                                        :head="'title'"
                                        :body="'title'"
                                        :autocomplete="{
                                            available: true,
                                            data: types
                                        }"
                                        :showBody="false"
                                        v-else-if="info.id == '655.a'"
                                    />

                                    <input-div
                                        v-model="info.data"
                                        classes="border-black input_static_height"
                                        :selectable="{
                                            available: true,
                                            data: authority.subject_terms
                                        }"
                                        :head="'subject_term'"
                                        :body="'subject_term'"
                                        :autocomplete="{
                                            available: true,
                                            data: authority.subject_terms
                                        }"
                                        :showBody="false"
                                        v-else-if="info.id == '650.x'"
                                    />
                                    <!-- if it's just info -->
                                    <input
                                        type="text"
                                        class="w-100"
                                        v-model="info.data"
                                        v-else
                                    />
                                </td>
                                <td>
                                    <button
                                        class="outline-blue"
                                        @click="removeSubtag(info, index)"
                                        v-if="info.repeatable == undefined"
                                    >
                                        -
                                    </button>
                                    <button
                                        class="outline-blue"
                                        @click="addSubtag(info, index)"
                                        v-if="info.repeatable == 1"
                                    >
                                        +
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        <button
                            class="ml-2 width-unset outline-black"
                            @click="getEditInfo()"
                        >
                            {{ $t("refresh") }}
                        </button>
                        <button class="ml-2 width-unset" @click="save()">
                            {{ $t("save") }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="d-flex mt-4 align-items-center">
                <div class="d-flex flex-fill">
                    <div class="w-100">
                        {{ $t("created_by") }} : {{ created_info.created_by }}
                    </div>
                    <div class="w-100">
                        {{ $t("create_date") }} : {{ created_info.created_at }}
                    </div>
                </div>
                <div class="ml-auto mr-3">
                    <button class="outline-green" @click="showFullLog()">
                        {{ $t("show_full_log") }}
                    </button>
                </div>
                <div class="ml-auto">
                    <button class="bg-green" @click="setStatusCompleted()">
                        {{ $t("set_status_completed") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
// mixins
import { goTo } from "../../../mixins/goTo";
import { message_success, message_error } from "../../../mixins/messages";
import { download_file, print_file } from "../../../mixins/files";
import showModal from "../../../mixins/showModal";

// components
import Back from "../../../components/Back";
import InputDiv from "../../../components/Input";

// icons
import Print from "../../../../common/assets/icons/Print";

// relative components
import Preview from "./Preview";
import FullLog from "./FullLog";
import IFGA from "./ImportFromGoogleAPI.vue";
export default {
    mixins: [
        goTo,
        message_error,
        message_success,
        download_file,
        print_file,
        showModal
    ],
    props: {
        info: Object
    },
    components: { Back, Print, InputDiv },
    data() {
        return {
            edit_info: [],
            sectioned: [],
            commit: "cataloging",
            link: "cataloging/material",
            sectionSelected: 0,
            tagSelected: {},
            authority: {},
            created_info: {},
            state: "",
            types:[],
            publishers:[]
        };
    },
    methods: {
        getEditInfo() {
            this.$store.commit("setFullPageLoading", true);
            this.sectionSelected = 0;
            this.$http
                .get(this.link + "/" + this.info.type_key + "/" + this.info.id)
                .then(response => {
                    this.edit_info = response.data.res;
                    this.created_info = response.data.created_info;
                    this.state = response.data.state;
                    this.sortInfo(this.edit_info);
                    this.updateCatalogingInfo();
                })
                .catch(e => {
                    this.message_error('loading',e)
                })
                .then(() => {
                    this.$store.commit("setFullPageLoading", false);
                });
        },
        sortInfo(info) {
            return info.sort((a, b) => {
                if (a.id < b.id) {
                    return -1;
                }
                if (a.id > b.id) {
                    return 1;
                }
                return 0;
            });
        },
        updateCatalogingInfo() {
            this.divideIntoSections();
            this.divideIntoSubsections();
        },
        divideIntoSections() {
            let sections = [];

            //functions
            let findByChar = (array, char, toSearch) => {
                for (let i = 0; i < array.length; i++) {
                    let elem = array[i];
                    if (elem[toSearch] == char) {
                        return true;
                    }
                }
                return false;
            };

            for (let i = 0; i < this.edit_info.length; i++) {
                let info = this.edit_info[i];
                let char = info.id.charAt(0);

                if (!findByChar(sections, char, "section")) {
                    let section = { section: char, info: [info] };
                    sections.push(section);
                } else {
                    sections[char].info.push(info);
                }
            }

            this.sectioned = sections;
        },
        divideIntoSubsections() {
            this.sectioned.forEach(section => {
                section.tags = section.info.filter(
                    elem => elem.pid == undefined
                );
                section.tags.forEach(tag => {
                    tag.data = section.info.filter(
                        elem => elem.pid == tag.field_code
                    );
                });
            });
            this.tagSelected = this.sectioned[0].tags[0];
        },
        selectSection(section) {
            this.sectionSelected = section.section;
            this.tagSelected = section.tags[0];
        },
        addSubtag(info, index) {
            let new_data = copy(info);
            new_data.ind1 = "";
            new_data.ind2 = "";
            new_data.data = "";
            new_data.repeatable = null;

            this.tagSelected.data.splice(index + 1, 0, new_data);

            let lindex = this.sectioned[this.sectionSelected].info.indexOf(
                info
            );

            this.sectioned[this.sectionSelected].info.splice(
                lindex + 1,
                0,
                new_data
            );
        },
        removeSubtag(info, index) {
            this.tagSelected.data.splice(index, 1);

            let lindex = this.sectioned[this.sectionSelected].info.indexOf(
                info
            );

            this.sectioned[this.sectionSelected].info.splice(lindex, 1);
        },
        save() {
            let findInCatalog = code => {
                return this.edit_info.find(elem => elem.id == code).data ?? "";
            };

            let save = () => {
                this.$store.commit("setFullPageLoading", true);
                let joinSections = () => {
                    let res = [];
                    this.sectioned.forEach(section => {
                        section.info.forEach(data => {
                            if (data.is_added) {
                                delete data.is_added;
                            }
                            if (data.pid == undefined) {
                                data.data = null;
                            }
                        });
                        res = res.concat(section.info);
                    });
                    return res;
                };
                let res = joinSections();

                this.$http
                    .post(
                        this.link +
                            "/" +
                            this.info.type_key +
                            "/" +
                            this.info.id +
                            "/edit",
                        { data: res }
                    )
                    .then(response => {
                        this.message_success("edit", response);
                        this.tagSelected = {};
                        this.getEditInfo();
                    })
                    .catch(e => {
                        this.message_error("edit", e);
                    })
                    .finally(() => {
                        this.$store.commit("setFullPageLoading", false);
                    });
            };

            if (findInCatalog("245.a").length <= 0) {
                this.message_error("cataloging", "title_not_found");
            } else if (
                findInCatalog("010.a").length > 0 &&
                (findInCatalog("010.c").length <= 0 ||
                    findInCatalog("010.n").length <= 0 ||
                    findInCatalog("010.s").length <= 0 ||
                    findInCatalog("260.c").length <= 0)
            ) {
                this.$confirm(this.$t("lccn_incorrect")).then(result => {
                    if (result) {
                        save();
                    }
                });
            } else {
                save();
            }
        },
        exportXML() {
            this.$store.commit("setFullPageLoading", true);
            this.$http
                .get(
                    this.link +
                        "/export/" +
                        this.info.type_key +
                        "/" +
                        this.info.id
                )
                .then(response => {
                    this.download_file(
                        response,
                        "xml_" + this.info.title,
                        "xml"
                    );
                })
                .catch(e => {})
                .then(() => {
                    this.$store.commit("setFullPageLoading", false);
                });
        },
        printCallNumber() {
            this.$store.commit("setFullPageLoading", true);
            this.$http
                .get(
                    this.link +
                        "/print/" +
                        this.info.type_key +
                        "/" +
                        this.info.id,
                    { responseType: "blob" }
                )
                .then(response => {
                    this.print_file(
                        response,
                        "call_number_" + this.info.title,
                        "pdf"
                    );
                })
                .catch(e => {
                    this.message_error('loading',e)
                })
                .finally(() => {
                    this.$store.commit("setFullPageLoading", false);
                });
        },
        importFromGoogleAPIs(isbn) {
            // we use fetch() because there's cors mistake when use this.$http
            this.$store.commit("setFullPageLoading", true);
            fetch(
                "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn
            ).then(response => {
                response
                    .json()
                    .then(async res => {
                        try {
                            let data = res.items[0].volumeInfo;
                            let array = [];
                            array.push(
                                {
                                    value: data.title,
                                    code: "245.a"
                                },
                                {
                                    value: data.subtitle,
                                    code: "245.b"
                                },
                                {
                                    value: data.authors,
                                    code: "600.a"
                                },
                                {
                                    value: data.publisher,
                                    code: "260.b"
                                },
                                {
                                    value: data.publishedDate,
                                    code: "260.c"
                                },
                                {
                                    value:
                                        data.industryIdentifiers[1].identifier,
                                    code: "020.a"
                                },
                                {
                                    value: data.printType,
                                    code: "650.x"
                                },
                                {
                                    value: data.pageCount,
                                    code: "300.a"
                                },
                                {
                                    value: data.categories,
                                    code: "650.a"
                                },
                                {
                                    value: data.language,
                                    code: "546.a"
                                },
                                {
                                    value: data.description,
                                    code: "520.a"
                                },
                                {
                                    value: data.description,
                                    code: "520.b"
                                }
                            );

                            let after = res => {
                                this.edit_info = res;
                                this.sortInfo(this.edit_info);
                                this.updateCatalogingInfo();
                                this.message_success(
                                    "import_from_google_api",
                                    {}
                                );
                            };
                            this.showModal(IFGA, {
                                data: array,
                                edit_info: this.edit_info,
                                after
                            });
                        } catch (e) {
                            this.$store.commit("setFullPageLoading", false);
                            this.$prompt(
                                "Search google apis for isbn: " +
                                    isbn +
                                    " failed. Maybe you'd like to try to search with another isbn?"
                            ).then(text => {
                                this.importFromGoogleAPIs(text);
                            });
                        }
                    })
                    .catch(e => {
                        this.message_error('loading',e)
                    })
                    .finally(() => {
                        this.$store.commit("setFullPageLoading", false);
                    });
            });
        },
        preview() {
            this.showModal(Preview, {
                info: this.sectioned,
                width: "100%",
                height: "100%",
                styles: "overflow:hidden"
            });
            document.documentElement.classList.add("overflow-hidden");
        },
        showFullLog() {
            this.showModal(FullLog, {
                info: this.info
            });
        },
        setStatusCompleted() {
            this.$store.commit("setFullPageLoading", true);
            this.$http
                .post(
                    "/cataloging/material/" +
                        this.info.type_key +
                        "/" +
                        this.info.id +
                        "/complete"
                )
                .then(response => {
                    this.message_success("set_status_completed", response);
                })
                .catch(error => {
                    this.message_error("set_status_completed", error);
                })
                .then(() => {
                    this.$store.commit("setFullPageLoading", false);
                });
        },
        getAuthority() {
            this.$store.commit('setFullPageLoading',true);
            this.$http.get("/cataloging/authority").then(response => {
                this.authority = response.data.res;
            }).catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
        },
        getTypes(){
            this.$store.commit('setFullPageLoading',true);
            this.$http.get(this.link+'/genres').then(response=>{
                this.types=response.data.res;
            }).catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
        },
        getPublishers(){
            this.$store.commit('setFullPageLoading',true);
            this.$http.get('publisher/names').then(response=>{
                this.publishers=response.data.res;
            }).catch(error=>{
				this.message_error('loading',error);
			}).finally(()=>{
				this.$store.commit('setFullPageLoading',false);
			})
        }
    },
    created() {
        if (!this.info) {
            this.goTo("cataloging_search");
        } else {
            this.getEditInfo();
            this.getAuthority();
            this.getTypes();
            this.getPublishers();
        }
    }
};
</script>
<style scoped>
td {
    border: none;
}

th {
    text-align: left;
    font-weight: 500;
    border: 1px solid #e8e8e8 !important;
    border-left: none !important;
    border-right: none !important;
}
input {
    width: unset;
}
input,
input:disabled {
    border-color: inherit;
}
textarea {
    height: 150px;
}
.little_td {
    width: 7%;
}
.little_input {
    width: 2.5em;
    padding-left: 0.125em;
    padding-right: 0.125em;
    text-align: center;
}
.td_no_input {
    border: 1px solid #e8e8e8;
    border-left: none;
    border-radius: 0.3125em;
    padding: 1em 1.25em;
}
</style>
