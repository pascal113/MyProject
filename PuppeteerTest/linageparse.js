const fs = require('fs');

let rawdata = fs.readFileSync('./data.json');

let devices = JSON.parse(rawdata);

for(let i = 0; i<devices.length; i++) {
    devices[i].height = 1000;
    if(devices[i].dimensions !== undefined) {
        devices[i].height = parseFloat(devices[i].dimensions.split(' ')[0]);
        console.log(devices[i]);
    }
}

devices.sort((a, b) => {
    return a.height - b.height;
});

fs.writeFileSync('./data_sorted.json', JSON.stringify(devices, null, 1) , 'utf-8');