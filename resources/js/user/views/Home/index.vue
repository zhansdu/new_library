<template>
    <div>
        <!-- search -->
        <!-- <div
            class="d-flex flex-column flex-xl-nowrap bg-lightgrey padding pt-5 home"
            :class="[{'bg-blue':noData},'background-' + weekInfo.bg_color]"
        > -->
         <div
            class="d-flex flex-column flex-xl-nowrap bg-lightgrey padding pt-5 home background-image"
        >
            <div class="d-flex flex-column w-100 mt-5 align-items-center">
                <Search
                    class="pt-4 pb-4 col-md-8 col-xl-6 px-0 flex-0 text-white z-index-6"
                />
            </div>
            <!-- <img class="lines w-100 pe-none" src="/images/Lines.png" /> -->
            <div class="d-flex flex-column align-items-center flex-fill" v-if="!noData">
                <div
                    class="l_info"
                    :class="{ 'text-white': $store.getters.theme == 'light' }"
                >
                    <div class="l_info_name">
                        {{
                            $t(
                                "avatars[" +
                                    weekDate.month +
                                    "][" +
                                    weekDate.day +
                                    "].name"
                            )
                        }}
                    </div>
                    <div class="l_info_birthday">
                        {{
                            $t(
                                "avatars[" +
                                    weekDate.month +
                                    "][" +
                                    weekDate.day +
                                    "].birthday"
                            )
                        }}
                    </div>
                    <div
                        class="l_info_info"
                        v-html="
                            $t(
                                'avatars[' +
                                    weekDate.month +
                                    '][' +
                                    weekDate.day +
                                    '].info'
                            )
                        "
                    />
                </div>
                <img
                    :src="'/images/avatars/' + weekInfo.avatar"
                    class="avatar pe-none mt-auto"
                />
            </div>
        </div>

        <!-- upcoming events -->
        <div class="padding bg-white py-5">
            <div class="d-flex justify-content-between">
                <span class="font-size-32 font-weight-bold text-red">{{
                    $t("upcoming_events").toUpperCase()
                }}</span>
                <a
                    href="https://sdu-kz.libcal.com/calendar/training-sessions-and-events"
                    target="_blank"
                    class="d-flex align-items-center link text-black"
                >
                    {{ $t("see_all_events") }}
                    <span class="ml-2 font-size-14">
                        <right-normal />
                    </span>
                </a>
            </div>
            <div class="d-flex align-items-start mt-5 ">
                <div class="p-0 bg-white mr-5 rounded">
                    <iframe
                        class="no-border calendar-height"
                        src="https://api3-eu.libcal.com/embed_mini_calendar.php?mode=month&iid=4105&cal_id=7853&l=5&tar=0&h=457&audience=&c=&z="
                    />
                </div>
                <slide-events
                    v-if="!$mobileCheck()"
                    :events="config.news"
                    :number_shown="3"
                    :fixed_size_fraction="3"
                />
            </div>
        </div>
        <!-- library's video content -->
        <div
            class="position-relative bg-lightgrey padding py-5 overflow-hidden"
        >
            <span
                class="font-size-32 font-weight-bold text-red"
                v-html="$t('vid_content').toUpperCase()"
            />
            <div class="mt-3 mb-3">
                {{ $t("video_content") }}
            </div>
            <div
                class="d-flex flex-column align-items-center flex-sm-row justify-content-between align-items-sm-start  flex-wrap flex-xl-nowrap w-100 position-relative z-index-2 mt-5"
            >
                <video-block
                    v-for="(video, index) in config.videos"
                    :key="index"
                    :video="video"
                    :num="index"
                    class="mt-2"
                />
            </div>
            <img class="videos_image" src="/images/Video.svg" />
            <div class="mt-5">
                <a
                    class="link text-black"
                    href="https://www.youtube.com/channel/UCmuuTTBkfi8aUgUc56VY8kA"
                    target="_blank"
                >
                    {{ $t("see_all_videos") }}
                    <span class="ml-2 "><right-normal /></span>
                </a>
            </div>
        </div>
        <!-- faq -->
        <div
            class="padding py-5 bg-white d-flex flex-wrap justify-content-between bg-image"
        >
            <div class="col-12 col-md-4 px-0">
                <div class="font-size-32 font-weight-bold text-black">
                    {{ $t("faq").toUpperCase() }}
                </div>
                <div class="mt-5 font-weight-bold">
                    {{ $t("faq_question") }}
                </div>
                <div class="mt-1 font-size-14">{{ $t("faq_answer") }}</div>
                <div class="mt-4">
                    <div
                        id="s-la-widget-7614"
                        class="d-none mt-20 full-width width-100-lg s-la-widget s-la-widget-embed"
                        :class="{ 'd-block': $i18n.locale == 'en' }"
                    ></div>
                    <div
                        id="s-la-widget-7815"
                        class="d-none mt-20 full-width width-100-lg s-la-widget s-la-widget-embed"
                        :class="{ 'd-block': $i18n.locale == 'ru' }"
                    ></div>
                    <div
                        id="s-la-widget-7814"
                        class="d-none mt-20 full-width width-100-lg s-la-widget s-la-widget-embed"
                        :class="{ 'd-block': $i18n.locale == 'kz' }"
                    ></div>
                </div>
            </div>
            <div class="mt-3 col-12 col-md-6 mt-md-0 px-0">
                <div
                    id="s-la-widget-7615"
                    class="d-none no-border-top s-la-widget s-la-widget-embed"
                    :class="{ 'd-block': $i18n.locale == 'en' }"
                ></div>
                <div
                    id="s-la-widget-7792"
                    class="d-none no-border-top s-la-widget s-la-widget-embed"
                    :class="{ 'd-block': $i18n.locale == 'ru' }"
                ></div>
                <div
                    id="s-la-widget-7809"
                    class="d-none no-border-top s-la-widget s-la-widget-embed"
                    :class="{ 'd-block': $i18n.locale == 'kz' }"
                ></div>
            </div>
        </div>
        <!-- fixed stuff -->
        <chat />
    </div>
</template>
<script>
// components
import Search from "../Search/search";
import Chat from "./Chat";
import SlideEvents from "./components/slide_events";
import VideoBlock from "./components/VideoBlock";

// icons
import RightNormal from "../../../common/assets/icons/RightNormal";

// configs
import { total_info } from "../../configs/landing_bg";

// mixins
import {
    loadScript,
    faqAskScripts,
    faqQuestionScripts
} from "../../mixins/libguide";
import configs from "../../mixins/configs";

export default {
    mixins: [loadScript, configs],
    components: { Search, RightNormal, SlideEvents, Chat, VideoBlock },
    data() {
        return {
            total_info,
            weekDate: {},
            weekInfo: {}
        };
    },
    computed:{
        noData(){
            let it =this.$t("avatars[" + this.weekDate.month + "][" + this.weekDate.day + "]");
            return it=="avatars[" + this.weekDate.month + "][" + this.weekDate.day + "]";
        }
    },
    methods: {
        getWeekInfo() {
            this.setWeekDate();

            this.weekDate = JSON.parse(localStorage.getItem("weekDate"));

            let weekDate = this.weekDate;

            this.weekInfo = total_info[weekDate.month];

            if (weekDate.day == 4) {
                if (this.weekInfo.length < 5) {
                    weekDate.day -= 1;
                }
            }

            this.weekInfo = this.weekInfo[weekDate.day];

            this.$store.commit("setTheme", this.weekInfo.theme);
           
        },
        setWeekDate() {
            let date = new Date();

            let month = date.getMonth();
            let day = date.getDate();
            let dayOfWeek = date.getDay();

            // day of week == 0 -> means sunday, but I need sunday to be 7
            if (dayOfWeek == 0) {
                dayOfWeek = 7;
            }

            let diff = day - dayOfWeek;

            if (diff < 0) {
                diff = -14;
            }

            day = Math.trunc(diff / 7);

            if (day < 0) {
                month -= 1;
                day = 4;
                if (month == -1) {
                    month = 11;
                }
            }

            let res = { day, month };

            localStorage.setItem("weekDate", JSON.stringify(res));
        },
        loadExternalLibGuideScripts() {
            let srcs = faqAskScripts.concat(faqQuestionScripts);

            srcs.forEach(item => {
                this.loadScript(item);
            });
        }
    },
    mounted() {
        this.loadExternalLibGuideScripts();
        this.getWeekInfo();
        this.getConfigs();
    }
};
</script>
<style scoped>
.no-border {
    border: none;
}
.videos {
    height: 18em;
}
.calendar-height {
    height: 18.75em;
}
.videos_image {
    position: absolute;
    bottom: -40px;
    right: -40px;
    z-index: 1;
}
.z-index-2 {
    z-index: 2;
}
.background-red {
    background-color: #9e2629 !important;
}

.background-blue {
    background-color: #2d3270 !important;
}

.background-image,.bg-image{
    background-position: center; /* Center the image */
    background-repeat: no-repeat; /* Do not repeat the image */
    background-size: cover;
}
.background-image{
    background-image: url('/images/SDU.jpg');
}
.bg-image{
    background-image:url('/images/FaqLines.png');
}
</style>
