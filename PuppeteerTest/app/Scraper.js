"use strict";
let express = require('express');
let cors = require('cors');
const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
//const AdblockerPlugin = require('puppeteer-extra-plugin-adblocker');
const RecaptchaPlugin = require('puppeteer-extra-plugin-recaptcha');
const jsdom = require('jsdom');
const { JSDOM } = jsdom;

const lockfile = require('./lockfile');
const helper = require('./helper');
const argv = require('yargs').argv;
var mkdirp = require('mkdirp');
const sharp = require('sharp');
const config = require('../config');
const fs = require('fs-extra');
const path = require('path');

class Scraper {
    constructor(options) {

        options = helper.merge_options([{
            headless: false,
            port: 3001,
            devtools: false,
            args: []
        },options, argv]);

        options.devtools = true;

        if(argv.port !== undefined && argv.port > 80) {
            options.port = argv.port;
        }

        /*
         * Tor proxy benutzen
         */
        if(argv.tor !== undefined && argv.tor == '1') {
            if(!options.args) {
                options.args = [];
            }
            options.args.push('--proxy-server=socks5://127.0.0.1:9050');
        }

        puppeteer.use(StealthPlugin());
        //puppeteer.use(AdblockerPlugin());

        puppeteer.use(
            RecaptchaPlugin({
                provider: {
                    id: '2captcha',
                    token: 'd0a6811a3bd712e57c5d6494fc73efb5'
                },
                visualFeedback: true // colorize reCAPTCHAs (violet = detected, green = solved)
            })
        );



        this.response_callbacks = [];
        this.options = options;
        this.app = express();
        this.app.use(cors());
        this.app.use(express.urlencoded({extended: false}));
        this.uniquename = this.constructor.name + '_' + this.options.account;
        this.lockfile = '.lockfile.' + this.uniquename;
        this.browser_profile_dir = './chrome_profiles/' + this.uniquename;
        this.screenshot_dir = './screenshots/' + this.uniquename;
        this.unlockBrowser();

        mkdirp(this.screenshot_dir, (err) => {

            if(!err) {
                this.app.use('/screenshots', express.static(this.screenshot_dir));
            }

        });

        this.app.get('/screenshot/:width', async (req, res) => {
            let width = parseInt(req.params.width);

            let imagebuffer = await this.page.screenshot();
            let output = await sharp(imagebuffer).resize(width).toBuffer();

            res.end(output, 'binary');

        });

        this.app.get('/screenshot', async (req, res) => {
            try {
                let output = await this.page.screenshot();
                res.end(output, 'binary');
            }
            catch (e) {
                res.json({
                    error: 1
                });
            }

        });

        this.get('/reboot', async (res) => {
            await this.restartBrowser();

            this.success(res, {
                pong: 1
            });

        });

        this.get('/close', async (res) => {
            await this.closeBrowser();

            this.success(res, {
                pong: 1
            });

        });

    }

    get(uri, handler, options) {

        options = helper.merge_options([
            {
                lock: true
            },
            options
        ]);

        this.app.get(uri, async (req, res) => {
            if(options.lock){
                this.lockBrowser();
            }

            try {
                await handler(res, req);
            }
            catch (e) {
                console.log(e);
                await this.restartBrowser();
                if(options.lock){
                    this.unlockBrowser();
                }

                try {
                    await handler(res, req);
                }
                catch (e) {
                    console.log('Fehler doppelt');
                    await this.restartBrowser();
                    if(options.lock){
                        this.unlockBrowser();
                    }
                    this.error(res);
                }
            }

            if(options.lock) {
                this.unlockBrowser();
            }
        });
    }

    post(uri, handler) {
        this.app.post(uri, async (req,res) => {
            await this.lockBrowser();
            try {
                await handler(req.body, res, req);
            }
            catch (e) {
                console.log(e);
                console.log('puppeteer fehler neuversuch...');
                await helper.sleep(3000);
                await this.restartBrowser();

                this.unlockBrowser();


                try {
                    await handler(req.body, res, req);
                }
                catch (e) {
                    console.log('weiterer Fehler starte Browser neu');
                    await this.restartBrowser();

                    this.unlockBrowser();

                    this.error(res);
                }
            }
            this.unlockBrowser();
        });
    }

    success(res, data) {
        res.json(data);
    }

    error(res) {
        res.status(500).send('Something broke!');
    }

    async lockBrowser() {
        console.log('lock browser');
        while (lockfile.exists(this.lockfile)) {
            await helper.sleep(500);
        }

        lockfile.create(this.lockfile);
    }

    unlockBrowser() {
        lockfile.remove(this.lockfile);
    }

    async run() {
        /*
         * START WEBSERVER
         */
        this.app.listen(this.options.port,() => {
            console.log('Server started on port ' + this.options.port);
        });

        try {
            /*
             * START BROWSER
             */
            await this.startBrowser();
        }
        catch (e) {
            await helper.sleep(10000);
            await this.restartBrowser();
        }
    }

    async startBrowser() {

        let browser_is_started = false;
        while (!browser_is_started) {
            try {

                this.browser = await puppeteer.launch({
                    headless: this.options.headless,
                    userDataDir: this.browser_profile_dir,
                    defaultViewport: {width: 1024, height: 700},
                    devtools: this.options.devtools,
                    args: this.options.args
                });

                this.page = await this.browser.newPage();
                this.page.bringToFront();

                this.page
                    .on('console', message =>
                        console.log(`${message.type().substr(0, 3).toUpperCase()} ${message.text()}`));
                    /*
                    .on('pageerror', ({ message }) => console.log(message))
                    .on('response', response =>
                        console.log(`${response.status()} ${response.url()}`))
                    .on('requestfailed', request =>
                        console.log(`${request.failure().errorText} ${request.url()}`));

                     */

                this.page.setDefaultNavigationTimeout(20000);

                /*
                await this.page.goto('https://bot.sannysoft.com');
                await this.page.waitFor(5000);
                await this.page.screenshot({ path: this.uniquename + '-testresult.png', fullPage: true });
                console.log(`All done, check the screenshot. ✨`);
                */

                await this.page.goto('https://www.google.de');
                browser_is_started = true;
            }
            catch (e) {
                console.error(e);
                browser_is_started = false;
                await this.closeBrowser();
                await helper.sleep(3000);
            }

        }

        this.page.on('response', response => {
            this.response_callbacks.forEach((cb) => {
                cb(response);
            });
        });
    }

    async closeBrowser() {
        await this.browser.close();
    }

    async restartBrowser() {

        await this.startBrowser();
    }

    checkUrl(url) {
        if(this.page.url().substring(0,url.length) == url) {
            return true;
        }
        return false;
    }

    async clearInput(selector) {
        await this.page.focus( selector );
        await this.page.keyboard.down( 'Control' );
        await this.page.keyboard.press( 'A' );
        await this.page.keyboard.up( 'Control' );
        await this.page.keyboard.press( 'Backspace' );
    }

    async clearInputType(selector, text) {
        await this.clearInput(selector);
        await this.page.type(selector, text,{
            delay: 2
        });
    }

    async clearInputTypeSubmit(selector, text) {
        await this.clearInputType(selector, text);
        await this.page.keyboard.press('Enter');
    }

    async scrollToBottom(page) {
        await this.page.evaluate(async () => {
            await new Promise((resolve, reject) => {
                var totalHeight = 0;
                var distance = 100;
                var timer = setInterval(() => {
                    var scrollHeight = document.body.scrollHeight;
                    window.scrollBy(0, distance);
                    totalHeight += distance;

                    if(totalHeight >= scrollHeight){
                        clearInterval(timer);
                        resolve();
                    }
                }, 150);
            });
        });
    }

    async selectorExists(selector) {
        let check = await this.page.evaluate(async (selector) => {

            let dom_result = document.querySelector(selector);

            if(dom_result) {
                return true;
            }
            else {
                return false;
            }

        }, selector);

        return check;
    }

    async frameSelectorExists(frame, selector) {
        let check = await frame.evaluate(async (selector) => {

            let dom_result = document.querySelector(selector);

            if(dom_result) {
                return true;
            }
            else {
                return false;
            }

        }, selector);

        return check;
    }

    filterInt(value) {
        value = value.trim().split('.').join('');
        return parseInt(value);
    }

    onResponse(callback) {
        this.response_callbacks.push(callback);
    }

    onResponseDelete() {
        this.response_callbacks = [];
    }

    async getDomDocument() {

        let html = await this.page.evaluate(() => {
            return (new XMLSerializer().serializeToString(document.doctype)) + document.documentElement.outerHTML;
        });

        let dom = await new JSDOM(html);

        return dom.window.document;

    }

    async injectParser(name, code) {

        let base_parser = await fs.readFileSync(path.join(__dirname, '../../shared/parser/Parser.js'));
        let ext_parser = await fs.readFileSync(path.join(__dirname, '../../shared/parser/' + this.capitalizeFirstLetter(name) + 'Parser.js'));

        base_parser = base_parser.toString().split('exports.')[0];
        ext_parser = ext_parser.toString().split('exports.')[0];

        await this.page.addScriptTag({ content: base_parser + ext_parser});

        return await this.page.evaluate();

    }

    capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
}

exports.Scraper = Scraper;
