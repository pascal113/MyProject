'use strict';

const XingScraper = require('./app/XingScraper').XingScraper;

let scraper = new XingScraper();

scraper.get('/ping', async (req, res) => {

    await scraper.blabla();

    scraper.success(res, {
        pong: 1
    });

});

scraper.run();