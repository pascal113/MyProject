const { exec } = require('child_process')
const path = require('path')

if (process.argv.includes('--refreshDatabase')) {
    refreshDatabase()
}

if (process.argv.includes('--seedDatabase')) {
    seedDatabase()
}

function refreshDatabase() {
    const isMigrated = process.argv.includes('--is-migrated')
    const isBeforeApplicationDestroyed = process.argv.includes('--is-before-application-destroyed')

    const cmd = `php artisan database:refresh --env=dusk ${isMigrated ? '--is-migrated' : ''} ${
        isBeforeApplicationDestroyed ? '--is-before-application-destroyed' : ''
    }`

    execCmd(cmd)
}

function seedDatabase() {
    const cmd =
        'php artisan voyager:install --env=dusk && php artisan flexible-page-cms:install --env=dusk && php artisan db:seed --env=dusk'

    execCmd(cmd)
}

function execCmd(cmd) {
    process.chdir(path.join(__dirname))

    exec(cmd, (err, stdout, stderr) => {
        if (err) {
            console.error(err) // eslint-disable-line no-console
        }
        else {
            console.log(`stdout: ${stdout || '(empty)'}`) // eslint-disable-line no-console
            console.log(`stderr: ${stderr || '(empty)'}`) // eslint-disable-line no-console
        }
    })
}
