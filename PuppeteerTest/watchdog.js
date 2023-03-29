const config = require('./config');
const express = require('express');
const cors = require('cors');
const snapshot = require('process-list').snapshot;
var spawn = require('child_process').spawn;
var ps = require('ps-node');
const helper = require('./app/helper');

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

let getNextFreePort = async () => {
    let tasks = await runlist();

    let ports = [];
    tasks.forEach((task) => {
        ports.push(task.port);
    });

    let freeport = 3002;
    while (ports.indexOf(freeport) !== -1) {
        freeport++;
    }

    return freeport;

};

let runlist = async () => {
    const tasks = await snapshot('pid', 'name', 'cmdline', 'starttime', 'cpu', 'pmem');
    let out = [];
    tasks.forEach((task) => {
        if(task.name === 'node' && task.cmdline.indexOf('node proxyserver_') !== -1) {

            let account = task.cmdline.split('--account=')[1].split(' ')[0];
            let port = task.cmdline.split('--port=')[1].split(' ')[0];
            let browser = task.cmdline.split('proxyserver_')[1].split('.js')[0];

            out.push({
                browser: browser,
                pid: task.pid,
                account: account,
                port: parseInt(port),
                cmd: task.cmdline,
                start: task.starttime,
                cpu: task.cpu,
                ram: formatBytes(task.pmem)
            });
        }
    });

    return out;
};

let startBrowser = async (browser) => {

};


let app = express();
app.use(cors());
app.use(express.urlencoded({extended: false}));

app.get('/runlist', async (req, res) => {

    // ps aux | grep "[n]ode server_"

    let tasks = await runlist();

    res.json({
        tasks: tasks
    });

});

app.get('/kill/:port', async (req, res) => {

    // ps aux | grep "[n]ode server_"

    let tasks = await runlist();

    await tasks.forEach(async (t) => {
        if(t.port+'' == req.params.port+'') {
            console.log('kill pid ' + t.pid);
            ps.kill(t.pid+'');
            await helper.sleep(1000);
            ps.kill(t.pid+'');
            await helper.sleep(500);
            ps.kill(t.pid+'');
            await helper.sleep(500);
            ps.kill(t.pid+'');
        }
    });

    res.json({
        tasks: tasks
    });

});

app.post('/start/:platform', async (req, res) => {

    let browserruns = false;
    let tasks = await runlist();
    let browser_task = null;
    let browser = req.params.platform;
    let account = req.body.login;
    let password = req.body.passwd;
    let task = {};

    await tasks.forEach((t) => {
        if(t.browser === browser && t.account == account) {
            browserruns = true;
        }
    });

    if(!browserruns) {
        let freeport = await getNextFreePort();

        let parameters = ['proxyserver_' + browser + '.js','--account=' + account , '--password="' + password + '"', '--port=' + freeport];

        if(config.headless === true) {
            parameters.push('--headless');
        }

        spawn('node', parameters, {
            detached: true
        });
    }

    tasks = await runlist();

    await tasks.forEach((t) => {
        if(t.browser === browser && t.account == account) {
            task = t;
        }
    });

    res.json(task);
});

app.listen(3001,() => {
    console.log('Server started on port 3001');
});

