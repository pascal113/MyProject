require('./vue')

window.$('document').ready(function() {
    // Scroll to input on invalid, including space for semi-transparent header bar
    window.$(':input').on('invalid', function() {
        var input = window.$(this)
        var form = window.$('form.form-edit-add')
        var navbar = window.$('.navbar-fixed-top')

        // only handle if this is the first invalid input
        if (input[0] === form.find(':invalid').first()[0]) {
            // height of the nav bar plus some padding
            var navbarHeight = navbar.height() + 100

            // the position to scroll to (accounting for the navbar)
            var elementOffset = input.offset().top - navbarHeight

            // the current scroll position (accounting for the navbar)
            var pageOffset = window.pageYOffset - navbarHeight

            // don't scroll if the element is already in view
            if (elementOffset > pageOffset && elementOffset < pageOffset + window.innerHeight) {
                return true
            }

            // note: avoid using animate, as it prevents the validation message displaying correctly
            window.$('html,body').scrollTop(elementOffset)
        }

        return null
    })
})

window.Vue.component('admin-menu-bbc-com', require('./components/voyager/admin_menu.vue').default)

new window.Vue({ el: '#adminmenu-bbc-com' })

require('./admin-customize-tinymce.js')
require('featherlight')

if (process.env.APP_ENV === 'local' && process.env.BROWSER_SYNC_PORT) {
    require('./forceBrowserSync.js') // eslint-disable-line global-require
}
