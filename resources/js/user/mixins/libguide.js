export const loadScript = {
        methods: {
            async loadScript(src) {
                let externalScript = document.createElement('script');
                externalScript.setAttribute('src', src);
                document.head.appendChild(externalScript);
            }
        }
}
export const chatScripts = [
    // chatScripts
    // en
    'https://sdu-kz.libanswers.com/load_chat.php?hash=591323eae0c67c543ac18bf22cf2e1a7',
    // ru
    'https://sdu-kz.libanswers.com/load_chat.php?hash=26182d2d0a7628dba14f5685b439f7b5',
    // kz
    'https://sdu-kz.libanswers.com/load_chat.php?hash=2bd2632bd2b55389a65a46993bf9f779'
]
export const faqQuestionScripts = [
    // faq questions
    // en
    'https://sdu-kz.libanswers.com/1.0/widgets/7615',
    // ru
    'https://sdu-kz.libanswers.com/1.0/widgets/7792',
    // kz
    'https://sdu-kz.libanswers.com/1.0/widgets/7809',
]

export const faqAskScripts = [
    // faq ask 
    // en
    'https://sdu-kz.libanswers.com/1.0/widgets/7614',
    // ru
    'https://sdu-kz.libanswers.com/1.0/widgets/7815',
    // kz
    'https://sdu-kz.libanswers.com/1.0/widgets/7814',
]