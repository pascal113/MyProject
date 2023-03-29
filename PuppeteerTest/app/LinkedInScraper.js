const Scraper = require('./Scraper').Scraper;
const fs = require('fs');
const helper = require('./helper');
const axios = require('axios');
const LinkedinParser = require('../../shared/parser/LinkedinParser').LinkedinParser;

class LinkedInScraper extends Scraper {

    async messages(params) {

        if(!this.checkUrl( 'https://www.linkedin.com/messaging/thread/')) {
            await this.checkLogin( 'https://www.linkedin.com/messaging/');
            await this.page.waitForSelector('#messaging-nav-item');
        }
        else {
            await this.page.click('#messaging-nav-item a');
        }

        await this.page.waitForSelector('li.msg-conversation-listitem');

        let message_ob_ids = await this.page.evaluate(async () => {
            let elements = document.querySelectorAll('li.msg-conversation-listitem ');

            let messages = [];

            for (let element of elements) {

                element.click();

                let obj = {};

                //obj.name = element.querySelector('h3.msg-conversation-listitem__participant-names').innerText;
                obj.id = element.getAttribute('id');


                messages.push(obj);

            }

            return messages;

        });

        await this.scrollToBottom(this.page);

        for(let i = 0; i<message_ob_ids.length; i++){
            await this.page.click('#' + message_ob_ids[i].id + ' a');
            await helper.sleep(2000);
            message_ob_ids[i].messages = await this.page.evaluate(() => {

            });
        }



    }

    async search(term) {

        console.log('/search');

        if(term !== undefined) {

            if (term.indexOf('linkedin.com') === -1) {

                if (!this.checkUrl('https://www.linkedin.com/search/results/people/')) {
                    await this.checkLogin('https://www.linkedin.com/search/results/people/?origin=DISCOVER_FROM_SEARCH_HOME');
                    console.log('loggedin');
                    await this.page.waitForTimeout(2000);
                }

                console.log('click in search bar');
                await this.page.click('#global-nav-search');
                await this.page.waitForSelector('.search-global-typeahead--focused');
                await this.page.evaluate(() => {
                    //document.querySelector('.search-global-typeahead--focused input').value = '';
                    document.querySelector('#global-nav-typeahead input').value = '';
                });
                //await this.page.type('.search-global-typeahead--focused input', term);
                await this.page.type('#global-nav-typeahead input', term);
                await this.page.keyboard.press('Enter');
                console.log('start search');

                let people = [];
                let count_people = 0;

                //await this.clearInputTypeSubmit( 'input.search-global-typeahead__input', term);
                //await this.page.waitForSelector('.reusable-search__entity-results-list li, .reusable-search-filters__no-results');
                await this.page.waitForSelector('.reusable-search__entity-result-list li, .reusable-search-filters__no-results');

                if (await this.selectorExists('.reusable-search-filters__no-results') === false) {

                    //await this.page.waitForSelector('.search-results__cluster-bottom-banner');
                    //await this.page.click('.search-results__cluster-bottom-banner a');

                    //await this.page.waitForSelector('.search-results-container > .t-black--light');

                    /* Klick auf "Alle Personen anzeigen", falls das mal nötig wird */
                    /*if (await this.selectorExists('.search-results__cluster-bottom-banner a.app-aware-link') !== false) {

                        await this.page.click('.search-results__cluster-bottom-banner a.app-aware-link');
                        await this.page.waitForSelector('.search-results-container h2.pb2');
                    }*/

                    count_people = await this.scrapeCountPeople();
                    people = await this.scrapePeople();

                }

                return {
                    count: count_people,
                    people: people
                };
            }
            else {
                console.log('scrape profile url');

                await this.page.goto(term, {timeout: 30000, waitUntil: "domcontentloaded"});

                let count_people = 1;
                let people = [];

                people = await this.scrapeProfileUrl(term);

                return {
                    count: count_people,
                    people: people
                };
            }

            await scraper.lockBrowserEnd('linkedin');
        }
    }

    async checkLogin(url) {
        await this.page.goto(url, {timeout: 30000, waitUntil: "domcontentloaded"});
        await this.page.waitForTimeout(2000);
        let pageurl = '';
        pageurl = await this.page.url();
        pageurl = pageurl+'';
        console.log('pageurl: ' + pageurl);
        if(pageurl.indexOf('/login') !== -1 || pageurl.indexOf('linkedin.com/start/join') !== -1 || pageurl.indexOf('linkedin.com/signup/cold-join') !== -1) {

            if(pageurl.indexOf('linkedin.com/signup/cold-join') !== -1) {
                await this.page.goto('https://www.linkedin.com/login');
                await this.page.waitForTimeout(2000);
            }

            let bodyHTML = await this.page.evaluate(() => document.body.innerHTML);
            fs.writeFile('/tmp/test2.html', bodyHTML, function(err) {

                if(err) {
                    return console.log(err);
                }

                console.log("The file was saved!");
            });

            let elementHandle = await this.page.$('iframe.authentication-iframe');
            let usernameHandle = await this.page.$('input#username');

            /*
             * iframe login?
             */
            if(elementHandle) {
                this.iframeLogin(elementHandle);
            }
            else if(usernameHandle){

                this.loginMethodOne();
            }
            else {
                this.loginMethodTwo();
            }

            await this.page.waitForTimeout(6000);

            bodyHTML = await this.page.evaluate(() => document.body.innerHTML);
            fs.writeFile('/tmp/test3.html', bodyHTML, function(err) {

                if(err) {
                    return console.log(err);
                }

                console.log("The file was saved!");
            });

            await this.page.waitForTimeout(4000);


            /*
             * Warte auf OTP-Token Eingabe
             */
            await this.page.waitForSelector('input.input_verification_pin', {timeout: 4000});
            await this.page.click('#recognizedDevice');

            console.log('get', 'otp_token');

            /*
             * Wir authorisieren uns plump als Raphael und holen uns den opt key vom dummy linkedin user
             */
            const login_response = await axios.get('https://hunter.jungwild.io/api/v1/platformaccount/otptoken/22', {
                headers: {
                    'Authorization': 'Bearer d6626aee43eaafc468acba8c825d7905'  // Raphaels auth_token
                }
            });

            console.log(login_response.data);

            let otp_token = login_response.data.otp;

            const otp_login = await this.page.evaluate((otp_token) => {
                document.querySelector('.input_verification_pin').value = otp_token;

                setTimeout(()=>{
                    return document.querySelector('form.pin-verification-form button[type=submit]').click();
                },300);
            }, otp_token);


            //await page.click('.login__form_action_container > button');
            //await this.page.waitForSelector('.launchpad__title');
            await this.page.goto(url);

        }
    }

    async loginMethodTwo() {
        try {
            await this.page.waitForSelector('.signin-link > a.sign-in-link',  { timeout: 2000 });
            await this.page.click('.signin-link > a.sign-in-link');

            await this.page.waitForTimeout(1000);

            await this.page.waitForSelector('.g-recaptcha', { timeout:5000 });

            await this.page.type('#username', this.options.account);
            await this.page.type('#password', this.options.password);
            await this.page.click('button[type="submit"]');

            try {
                await this.page.waitForTimeout(2000);
                await this.page.solveRecaptchas();
                await this.page.waitForTimeout(5000);
            }
            catch (e) {
                console.log(e.message);
            }
        }
        catch (e) {
            console.log(e.message);
        }
    }

    async loginMethodOne() {
        try{
            await this.page.type('#username', this.options.account);
            await this.page.type('#password', this.options.password);
            await this.page.click('button[type="submit"]');
            try {
                await this.page.waitForTimeout(2000);
                await frame.solveRecaptchas();
                await this.page.waitForTimeout(5000);
            }
            catch (e) {
                console.log(e.message);
            }
        }
        catch (e) {
            console.log(e.message);
        }
    }

    async iframeLogin(elementHandle) {

        try {
            let frame = await elementHandle.contentFrame();

            await this.page.waitForTimeout(2000);

            await frame.waitForSelector('.signin-link > a.sign-in-link',  { timeout: 2000 });
            await frame.click('.signin-link > a.sign-in-link');

            await this.page.waitForTimeout(1000);

            await frame.type('#username', this.options.account, { delay: 12});
            await frame.type('#password', this.options.password, { delay: 8});
            await frame.click('button[type="submit"]');

            await frame.waitForSelector('.g-recaptcha', { timeout:5000 });
            try {
                await this.page.waitForTimeout(2000);
                await frame.solveRecaptchas();
                await this.page.waitForTimeout(5000);
            }
            catch (e) {
                console.log(e.message);
            }
        }
        catch (e) {
            try {
                let elementHandle = await this.page.$('iframe.authentication-iframe');
                let frame = await elementHandle.contentFrame();

                await this.page.waitForTimeout(1000);

                await frame.waitForSelector('.main__sign-in-link',  { timeout: 2000 });
                await frame.click('.main__sign-in-link');

                await this.page.waitForTimeout(1000);

                await frame.type('#username', this.options.account);
                await this.page.waitForTimeout(112);
                await frame.type('#password', this.options.password);
                await this.page.waitForTimeout(96);
                await frame.click('button[type="submit"]');

                await frame.waitForSelector('.g-recaptcha', { timeout:5000 });
                try {
                    await this.page.waitForTimeout(2000);
                    await frame.solveRecaptchas();
                    await this.page.waitForTimeout(5000);
                }
                catch (e) {
                    console.log(e.message);
                }
            }
            catch (e) {
                console.log("schon auf der login seite?");
            }
        }
    }

    /*
     * SCRAPE PEOPLE COUNT
     */
    async scrapeCountPeople() {

        await this.page.waitForSelector('.search-results-container');

        var result = await this.page.evaluate(() => {

            let dom_count = document.querySelector('.search-results-container > .t-black--light');
            if(dom_count) {
                return dom_count.innerText;
            }
            return false;
        });

        if(result !== false) {
            if(result.indexOf('Ergebnisse') !== -1) {
                result = result.split('Ergebnisse')[0];
                //result = result.split('sehen')[1];
                result = result.replace('Etwa', '');
                result = result.trim();
                result = this.filterInt(result);

                return result;
            }
        }

        return false;
    }

    /*
     * SCRAPE PEOPLE
     */
    async scrapePeople() {

        //await this.page.waitForSelector('.reusable-search__entity-results-list');
        await this.page.waitForSelector('.reusable-search__entity-result-list');
        //await this.scrollToBottom();

        let parser = new LinkedinParser();

        let document_dom = await this.getDomDocument();

        let result = parser.candidateListing(document_dom);

        return result;
    }


    /*
     * SCRAPE PEOPLE
     */
    async scrapeProfile(rdata) {

        if(await this.selectorExists('a[href="/in/' + rdata.uid + '/"]')) {
            await this.page.click('a[href="/in/' + rdata.uid + '/"]');
        }
        else {
            await this.page.goto('https://www.linkedin.com/in/' + rdata.uid + '/');
        }

        await this.page.waitForSelector('.pv-top-card-v3--list');

        await this.scrollToBottom();

        let result = await this.page.evaluate(() => {
            var person = {
                connected: false,
                company: '',
                position: '',
                avatar: ''
            };

            /*
             * prüfen ob person vernetzt ist
             */
            var btn_connect = document.querySelector('#ember54');
            if(btn_connect) {
                if(btn_connect.innerHTML.indexOf('Vernetzen') === -1) {
                    person.connected = true;
                }
            }

            /*
             * parse letzte job position
             */
            let dom_position = document.querySelector('#experience-section .pv-entity__summary-info h3.t-black');
            if(dom_position) {
                person.position = dom_position.innerText;
            }

            /*
             * parse letzten arbeitgeber
             */
            let dom_company = document.querySelector('#experience-section .pv-entity__summary-info .pv-entity__secondary-title');
            if(dom_company) {
                person.company = dom_company.innerText;
            }

            /*
             * parse foto
             */
            let dom_image = document.querySelector('.presence-entity.pv-top-card-section__image > img');
            if(dom_image) {
                person.avatar = dom_image.getAttribute('src');
            }

            return person;

        });

        console.log(result);

        return result;
    }

    /*
     * SCRAPE PROFILE URL
     */
    async scrapeProfileUrl(url) {

        await this.page.waitForSelector('.pv-top-card--list');

        let result = await this.page.evaluate((url) => {

            let safeText = function(dom_node) {
                if(dom_node) {
                    let out = dom_node.textContent.trim();
                    out = out.replace(/(\r\n|\n|\r)/gm, "").trim();
                    out = out.trim();
                    return out;
                }

                return '';
            };

            let queryText = function(dom, selector) {
                let dom_node = dom.querySelector(selector);
                if(dom_node) {
                    return safeText(dom_node);
                }

                return '';
            };

            let getUid = function(url) {
                let pattern = /\/in\/([a-zA-ZäÄöÖüÜß0-9-_]+)\/?/;
                let uid = url.match(pattern);

                if (uid.length > 1) {
                    return uid[1];
                }

                return '';
            };

            let splitName = function(person) {

                if(person && person.name) {
                    /* remove emojis */
                    let parts = person.name.replace(/[^ -\u2122]\s+|\s*[^ -\u2122]/g, '').split(' ');
                    person.firstname = parts[0];
                    parts.shift();
                    person.lastname = parts.join(' ');

                    return person;
                }

                return person;

            };

            var data = [];
            var person = {
                name: '',
                firstname: '',
                lastname: '',
                image: '',
                location: '',
                uid: '',
                platform_id: 1
            };

            /*
             * name
             */
            let dom_profile_div = document.querySelector('[id^="ember"]');

            if (dom_profile_div) {
                console.log('profile div found');

                /*
                 * name
                 */
                let name = safeText(document.querySelector('.pv-text-details__left-panel h1'));
                person.name = name;

                /*
                 * title
                 */
                let title = safeText(document.querySelector('.pv-text-details__left-panel .text-body-medium'));
                person.title = title;

                /*
                 * image
                 */
                let dom_img_div = document.querySelector('.pv-top-card__non-self-photo-wrapper');
                person.image = dom_img_div.querySelector('img').getAttribute('src');

                /*
                 * location
                 */
                let location = safeText(document.querySelector('.pv-text-details__left-panel > span'));
                person.location = location;

                /*
                 * uid
                 */
                person.uid = getUid(url);

                person = splitName(person);
            }
            else {
                console.log('no profile div found');
            }

            data.push(person);

            return data;

        }, url);

        return result;
    }

}

exports.LinkedInScraper = LinkedInScraper;
