const puppeteer = require('puppeteer');
const fs = require('fs-extra');
let page = null;

let scrape_device = async (device) => {
    await page.goto(device.link);
    await page.waitForSelector('.deviceinfo.table tbody tr');

    let out = await page.evaluate(async () => {
        let dev = {};
        let table_rows = document.querySelectorAll('.deviceinfo.table tbody tr');
        table_rows.forEach((row) => {
            let prop = row.querySelector('th');
            if(prop) {
                prop = prop.innerText.trim();
                if(prop === 'Battery') {
                    dev.battery = row.querySelector('td').innerText;
                }
                else if(prop === 'Dimensions') {
                    dev.dimensions = row.querySelector('td').innerText;
                }
                else if(prop === 'RAM') {
                    dev.ram = row.querySelector('td').innerText;
                }
                else if(prop === 'Storage') {
                    dev.storage = row.querySelector('td').innerText;
                }
                else if(prop === 'Released') {
                    dev.released = row.querySelector('td').innerText;
                }

            }
        });

        return dev;

    });

    out.link = device.link;
    out.name = device.name;

    return out;
};

let run = async() => {
    let browser = await puppeteer.launch({
        headless: false,
        defaultViewport: {width: 1024, height: 700}
    });
    page = await browser.newPage();
    page.setDefaultNavigationTimeout(20000);
    await page.goto('https://wiki.lineageos.org/devices/');
    await page.waitForSelector('.form-check-label');
    await page.click('.form-check-label');
    await page.waitFor(500);
    let devices = await page.evaluate(() => {
        let devices = [];
        let rows = document.querySelectorAll('.table.device tbody tr');

        rows.forEach((row) => {
            if(row.className.indexOf('discontinued') === -1) {
                let device = {};
                let link = row.querySelector('a');
                device.link = 'https://wiki.lineageos.org' + link.getAttribute('href');
                device.name = link.innerText;
                devices.push(device);
            }
        });

        return devices;
    });

    for(let i = 0;i<devices.length; i++) {
        devices[i] = await scrape_device(devices[i]);
        console.log(devices[i].link);
    }

    fs.writeFileSync('./data.json', JSON.stringify(devices) , 'utf-8');
    process.exit();
};



run();