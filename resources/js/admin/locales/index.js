import Vue from 'vue'
import VueI18n from 'vue-i18n'
import en from './en.js'
import ru from './ru.js'
import kz from './kz.js'

const languages = {
    en: en,
    ru: ru,
    kz: kz
}

const messages = Object.assign(languages)

Vue.use(VueI18n)

export default new VueI18n({
    locale: 'en',
    messages,
    silentTranslationWarn: true
})