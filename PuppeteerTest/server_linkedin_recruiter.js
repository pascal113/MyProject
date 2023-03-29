'use strict';

const LinkedInScraper = require('./app/LinkedInScraper').LinkedInScraper;

let scraper = new LinkedInScraper();

scraper.post('/search', async (data, res) => {
    let result = await scraper.search(data.term);
    scraper.success(res, result);
});

scraper.get('/ping', async (res) => {

    scraper.success(res, {
        pong: 1
    });

},{lock:false});

scraper.run();