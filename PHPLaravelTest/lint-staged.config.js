module.exports = {
    '*.{css,scss}': files => [
        `prettier --write "${files.join('" "')}" !public/** !vendor/** !node_modules/**`,
        `git add "${files.join('" "')}"`,
    ],
    '*.{js}': files => [ `prettier --write "${files.join('" "')}"` ],
    '*.{vue}': files => [ `prettier --write --no-semi "${files.join('" "')}"` ],
    '*.{js, vue}': files => [
        `eslint --fix "${files.join('" "')}"`,
        `git add "${files.join('" "')}"`,
    ],
    '*.php': files => [
        `./vendor/bin/ecs check "${files.join(
            '" "',
        )}" --fix --config ./vendor/fpcs/php-coding-standard/easy-coding-standard-config.php`,
        `./vendor/bin/phpcbf "${files.join('" "')}"`,
        `git add "${files.join('" "')}"`,
    ],
    'resources/views/emails/**/*.mjml': () => [
        'yarn compile-emails',
        'git add resources/views/emails/*.blade.php',
    ],
}
