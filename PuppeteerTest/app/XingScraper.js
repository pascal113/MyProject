const Scraper = require('./Scraper').Scraper;
const fs = require('fs');
const XingParser = require('../../shared/parser/XingParser').XingParser;


class XingScraper extends Scraper {

    async search(term) {
        if(term !== undefined) {

            if (term.indexOf('xing.com') === -1) {

                let input_selector = 'div[data-qa="search-keywords"] input';
                let all_results_selector = 'div[data-qa="search-suggestions"] section > a[href^="/search/members"]';
                let members_listing_wrapper_selector = 'div[data-qa="results-list"]';

                if (!this.checkUrl('https://www.xing.com/search/members')) {
                    await this.checkLogin('https://www.xing.com/search/members');
                }
                await this.page.waitForSelector(input_selector);
                console.log('search: ' + term);
                //await this.page.type(, term.trim());
                //await this.page.click('button[data-qa="search-button"]');

                await this.clearInputTypeSubmit(input_selector, term);

                //await this.page.waitForSelector(all_results_selector);
                //await this.page.click(all_results_selector);

                console.log('Warte auf Ergebnisse!');
                await this.page.waitForSelector(members_listing_wrapper_selector);

                console.log('...');

                let count_people = await this.scrapeCountPeople();
                let people = [];
                if (count_people > 0) {
                    people = await this.scrapePeople();
                }

                return {
                    count: count_people,
                    people: people
                };
            }
            else {
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

    /*
     * SCRAPE PEOPLE
     */
    async scrapePeople() {


        let data = await this.page.evaluate(() => {

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

            let dom = document;
            var data = [];
            let items = dom.querySelectorAll('a[class^="search-card-style-containerLink-"]');
            //let items = dom.querySelectorAll('div[class^="search-card-style-container-"]');

            for(let i=0;i<items.length;i++) {
                let li = items[i];

                // x85e3j-0 bXwVzC
                let name_addon = dom.querySelectorAll('[class*=search-result-card-SearchResultCard-flag-]');
                if(name_addon.length > 0) {
                    name_addon[0].parentElement.removeChild(name_addon[0]);
                }

                var person = {
                    name: queryText(li, '[class^="search-result-card-SearchResultCard-badgedTitle-"], [class^="MemberCard-style-title-"]'),
                    firstname: '',
                    lastname: '',
                    title: queryText(li, '.x85e3j-0.gSbewr, [class^="search-result-card-SearchResultCard-badgedTitle-"] + .cVxNbL, [class^="MemberCard-style-occupationMdWrapper-"] > span > strong'),
                    image: 'https://static-exp1.licdn.com/sc/h/244xhbkr7g40x6bsu4gi6q4ry',
                    location: queryText(li, '.x85e3j-0.lhmgjS > strong'),
                    uid: li.getAttribute('href').split('/')[2],
                    platform_id: 1
                };

                var dom_image = li.querySelector('div[class^="search-result-card-SearchResultCard-imageWrapper-"] img');

                let parts = person.uid.split('/');
                person.uid = parts[(parts.length-1)];
                person = splitName(person);

                if(dom_image) {
                    person.image = dom_image.getAttribute('src');
                }

                if(person.name !== undefined && person.name != '') {
                    data.push(person);
                }

            }

            return data;
        });

        return data;




        /*

        let parser = new XingParser();

        let document_dom = await this.getDomDocument();

        let result = parser.candidateListing(document_dom);

        console.log(result);

        return result;

         */
    }

    async scrapeCountPeople() {

        await this.page.waitForSelector('div[data-qa="results-overview"]');

        var result = await this.page.evaluate(() => {
            let dom_count = document.querySelector('div[data-qa="results-overview"]');
            console.log(dom_count.innerText);
            if(dom_count) {
                if (dom_count.innerText.indexOf('keine Ergebnisse') !== -1) {
                    return false;
                }
                return dom_count.innerText;
            }
            return false;
        });

        if(result !== false) {
            if(result.indexOf('Mitglied') !== -1) {
                result = result.split('Mitglied')[0];
                result = result.trim();
                if(result === 'Ein') {
                    result = '1';
                }
                result = this.filterInt(result);

                return result;
            }
            else {
                return 0
            }
        }

        return 0;
    }

    async checkLogin(url) {
        await this.page.goto(url);
        await this.page.waitForTimeout(100);

        let pageurl = await this.page.url();
        console.log('pageurl: ' + pageurl);

        /*
         * accept cookies
         */
        if(await this.selectorExists('#consent-accept-button')) {
            await this.page.click('#consent-accept-button');
        }

        if (pageurl.indexOf('https://login.xing.com') !== -1 ) {

            console.log('login 1und1 yeah!');

            console.log('frame?');

            await this.page.waitForSelector('iframe');

            await this.page.waitForTimeout(3000);

            let elementHandle = await this.page.$('iframe#stm');

            let frame = await elementHandle.contentFrame();

            /*
               let html = await frame.evaluate(async () => {
                   return document.documentElement.innerHTML;
               });

               await fs.writeFileSync('/tmp/xing-login.html', html);

               process.exit();
               */

            await this.page.waitForTimeout(3000);

            let html = await frame.evaluate(() => {
                return document.querySelector('body').innerHTML;
            });

            if(await this.frameSelectorExists(frame, 'input[name=username]')) {

                await frame.waitForSelector('input[name=username]');
                await frame.type('input[name="username"]', this.options.account, { delay: 12});
                await frame.type('input[name="password"]', this.options.password, { delay: 8});
                await frame.click('button[type="submit"]');

            }
            else {

                await this.page.waitForSelector('input[name=username]');
                await this.page.type('input[name="username"]', this.options.account, { delay: 12});
                await this.page.type('input[name="password"]', this.options.password, { delay: 8});
                await this.page.click('button[type="submit"]');

            }

            await this.page.waitForTimeout(15000);
        }
    }

    /*
     * SCRAPE PEOPLE
     */
    async scrapeProfile(rdata) {

        console.log(rdata);

        /*
        name: 'Alexander Wirz',
          firstname: 'Alexander',
          lastname: 'Wirz',
          title: 'Geschäftsführer / Managing Director Technology & Recruiting',
          image: 'https://www.xing.com/image/3_a_c_db542650e_7137980_8/alexander-wirz-foto.96x96.jpg',
          location: 'Köln',
          uid: 'Alexander_Wirz',
          status_id: '5',
          candidate_id: '26999'

         */

        if(await this.selectorExists('a[href^="/profile/' + rdata.uid + '"]')) {
            await this.page.click('a[href^="/profile/' + rdata.uid + '"]');
        }
        else {
            await this.page.goto('https://www.xing.com/profile/' + rdata.uid + '/');
        }

        await this.page.waitForSelector('[class^="userName-userName-userNameRow-"]');

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
            var btn_msg = document.querySelector('[class^="skins-primary-button-"]');
            if(btn_msg) {
                if(btn_msg.innerText.indexOf('Nachricht') > -1) {
                    person.connected = true;
                }
            }

            /*
             * parse letzte job position
             */
            let dom_position = document.querySelectorAll('[class^="entryOverview-entryOverview-occupation-"]');
            if(dom_position && dom_position.length > 0) {
                person.position = dom_position[0].innerText;
            }

            /*
             * parse letzten arbeitgeber
             */
            let dom_company = document.querySelectorAll('[class^="entryOverview-entryOverview-organization-"]');
            if(dom_company && dom_company.length > 0) {
                person.company = dom_company[0].innerText;
            }

            /*
             * parse foto
             */
            let dom_image = document.querySelector('[class*="photo-photoContent-userImage-"] image');
            if(dom_image) {
                person.avatar = dom_image.getAttribute('xlink:href');
            }

            console.log(person);

            return person;

        });

        console.log(result);

        return result;
    }

    /*
     * SCRAPE PROFILE URL
     */
    async scrapeProfileUrl(url) {

        await this.page.waitForSelector('div[class^="main-main-photoContainer-"] img');
        await this.page.waitForTimeout(500);

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
                if (url.indexOf('/cv') === -1) {
                    if (url.substr(-1, 1) !== '/') {
                        url += '/';
                    }
                    url += 'cv';
                }

                let pattern = /profile\/([a-zA-ZäÄöÖüÜß0-9-_]+)\/?.*/;
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
                platform_id: 2
            };

            /*
             * name
             */
            let dom_profile_div = document.querySelector('div[id="XingIdModule"]');

            if (dom_profile_div) {
                console.log('profile div found');

                /*
                 * name
                 */
                person.name = queryText(dom_profile_div, 'h1');

                if (person.name.indexOf('Premium') > -1) {
                    person.name = person.name.replace('Premium', '').trim();
                }
                else if (person.name.indexOf('Basic') > -1) {
                    person.name = person.name.replace('Basic', '').trim();
                }

                /*
                 * title
                 */
                let dom_title_div = dom_profile_div.querySelector('div[class*="sc-1fzyldz-1"]');
                person.title = queryText(dom_title_div, 'p');

                /*
                 * location
                 */
                let dom_location = document.querySelector('div[class*="sc-v9s4gl-1"] > p');
                person.location = queryText(dom_location, 'strong');

                /*
                 * image
                 */
                let dom_image_div = document.querySelector('div[class^="main-main-photoContainer-"]');
                person.image = dom_image_div.querySelector('img').getAttribute('src');

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

exports.XingScraper = XingScraper;
