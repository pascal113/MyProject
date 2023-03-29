
exports.sleep = (ms) => {
    return new Promise(resolve=>{
        setTimeout(resolve,ms)
    });
};

exports.merge_options = (objects) => {
    let ret = {};
    objects.forEach((obj) => {
        for(let attr in obj) {
            ret[attr] = obj[attr];
        }
    });

    return ret;
};