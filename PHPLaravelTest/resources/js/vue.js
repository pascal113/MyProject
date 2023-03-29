import Vue from 'vue'

window.Vue = Vue
window.Toasted = require('vue-toasted').default

window.Vue.use(window.Toasted, {
    duration: 3000,
    position: 'top-left',
})

window.fireVueMethod = function(componentName, methodName) {
    window.app.$refs[componentName][methodName]()
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them.
 *
 * Eg. ./components/ExampleComponent.vue -> <ExampleComponent></ExampleComponent>
 */

try {
    const appFiles = require.context('./components/', true, /\.vue$/i)
    appFiles.keys().map(key => {
        const name = key
            .replace(/^.\//, '')
            .replace(/\//g, '-')
            .replace(/\.vue$/, '')
            .toLowerCase()

        return window.Vue.component(name, appFiles(key).default)
    })

    const fpcmsFiles = require.context(
        '../../vendor/fpcs/flexible-page-cms/resources/assets/js/components/',
        true,
        /\.vue$/i,
    )
    fpcmsFiles.keys().map(key => {
        const name = key
            .replace(/^.\//, '')
            .replace(/\//g, '-')
            .replace(/\.vue$/, '')
            .toLowerCase()

        return window.Vue.component(name, fpcmsFiles(key).default)
    })
}
catch (error) {
    // Do nothing
}
