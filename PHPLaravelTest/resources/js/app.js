require('./bootstrap')

window.axios = require('axios').default

require('./vue')

window.$ = window.jQuery = require('jquery')
window.Popper = require('popper.js').default
require('slick-carousel')
require('featherlight')
window.tippy = require('tippy.js').default
require('vue-toasted')
window.mailcheck = require('mailcheck')

require('./global')

if (process.env.APP_ENV === 'local' && process.env.BROWSER_SYNC_PORT) {
    require('./forceBrowserSync.js') // eslint-disable-line global-require
}
