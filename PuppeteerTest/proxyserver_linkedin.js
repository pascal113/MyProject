'use strict';

const LinkedInScraper = require('./app/LinkedInScraper').LinkedInScraper;

let scraper = new LinkedInScraper({
    port: 3001
});

scraper.post('/search', async (data, res) => {
    let result = await scraper.search(data.term);
    scraper.success(res, result);
});

scraper.post('/messages', async (data, res) => {
    let result = await scraper.messages(data);
    scraper.success(res, result);
});

scraper.post('/profile', async (data, res) => {
    let result = await scraper.scrapeProfile(data);
    scraper.success(res, result);
});

scraper.get('/ping', async (res) => {

    scraper.success(res, {
        pong: 1
    });

});

scraper.run();