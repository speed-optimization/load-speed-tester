var page = new WebPage();
var t = Date.now();
page.open(phantom.args[0], function (status) {
    if (status !== 'success') {
        console.log(0);
    } else {
        t = Date.now() - t;
        console.log(t);
    }
    phantom.exit();
});