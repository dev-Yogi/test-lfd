<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Iframe Communicator</title>
    <script type="text/javascript">
        //<![CDATA[
        function callParentFunction(str) {
            if (str && str.length > 0 &&
                window.parent &&
                window.parent.parent &&
                window.parent.parent.AuthorizeNetIFrame &&
                window.parent.parent.AuthorizeNetIFrame.onReceiveCommunication) {
                // Errors indicate a mismatch in domain between the page containing the iframe and this page.
                window.parent.parent.AuthorizeNetIFrame.onReceiveCommunication(str);
            }
        }

        function receiveMessage(event) {
            if (event && event.data) {
                callParentFunction(event.data);
            }
        }

        if (window.addEventListener) {
            window.addEventListener("message", receiveMessage, false);
        } else if (window.attachEvent) {
            window.attachEvent("onmessage", receiveMessage);
        }

        if (window.location.hash && window.location.hash.length > 1) {
            callParentFunction(window.location.hash.substring(1));
        }
        //]]/>
    </script>
</head>

<body>
</body>

</html>