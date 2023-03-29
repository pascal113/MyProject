/* eslint-disable import/no-extraneous-dependencies */
const { registerDependencies } = require('mjml-validator')
const { BodyComponent } = require('mjml-core')

registerDependencies({
    'mj-body': [ 'mj-clear-php' ],
    'mj-column': [ 'mj-clear-php' ],
    'mj-text': [ 'mj-clear-php' ],
    'mj-clear-php': [],
})

class MjClearPhp extends BodyComponent {
    render() {
        return this.renderMJML('<mj-spacer height="0" />')
    }
}

// Tell the parser that our component won't contain other mjml tags
MjClearPhp.endingTag = true

module.exports = { default: MjClearPhp }
