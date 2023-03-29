'use strict'

// This has the unfortunate side effect of clearing any session flash data when this runs.
// As a result, you are less likely to see error messages on the login screen in particular.
// This only runs on local, though, and serves a more important purpose there, so this stays.

const currentPort = window.location.port || undefined
const browserSyncPort = process.env.BROWSER_SYNC_PORT || undefined

if (currentPort && browserSyncPort && currentPort !== browserSyncPort) {
    window.location.host = window.location.host.replace(/:[0-9]+$/, `:${browserSyncPort}`)
}
