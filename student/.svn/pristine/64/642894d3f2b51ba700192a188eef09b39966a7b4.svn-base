$(function () {
    $("#add_payment").show();
    $("#send_token").submit();
    $(window).scrollTop($('#add_payment').offset().top - 50);
});
(function () {
    if (!window.AuthorizeNetIFrame) window.AuthorizeNetIFrame = {};
    AuthorizeNetIFrame.onReceiveCommunication = function (querystr) {
        var params = parseQueryString(querystr);
        switch (params["action"]) {
            case "successfulSave":
                console.log('successfulSave');
                break;
            case "cancel":
                console.log('cancel');
                break;
            case "resizeWindow":
                console.log('resizeWindow');
                var w = parseInt(params["width"]);
                var h = parseInt(params["height"]);
                var ifrm = document.getElementById("add_payment");
                ifrm.style.width = w.toString() + "px";
                ifrm.style.height = h.toString() + "px";
                break;
            case "transactResponse":
                console.log('transactResponse');
                var transResponse = JSON.parse(params['response']);
                console.log(transResponse);
                console.log(transResponse.transactionData);
                console.log(transResponse.transId);
                window.location.href = base_url + '/payment/complete/' + transResponse.transId;
        }
    };

    function parseQueryString(str) {
        var vars = [];
        var arr = str.split('&');
        var pair;
        for (var i = 0; i < arr.length; i++) {
            pair = arr[i].split('=');
            vars.push(pair[0]);
            vars[pair[0]] = unescape(pair[1]);
        }
        return vars;
    }
}());