<?php
/**
 * Created by PhpStorm.
 * User: Yuheng
 * Date: 2014/10/31 0031
 * Time: 下午 20:17
 */
$flash_url = "TestMain.swf";
$uid = isset($_GET['uid']) ? $_GET['uid'] : false;
if($uid === false){
    die();
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>StarFarm Web Tester</title>
    <script type="text/javascript" src="https://cdn.douleyou.com/farm/swfobject.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.douleyou.com/farm/history/history.css" />
    <script type="text/javascript" src="https://cdn.douleyou.com/farm/history/history.js"></script>
</head>
<body style="text-align: center">
<div id="flashContent"></div>
<script type="text/javascript">
    // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection.
    var swfVersionStr = "11.1.0";
    // To use express install, set to playerProductInstall.swf, otherwise the empty string.
    var xiSwfUrlStr = "playerProductInstall.swf";
    var flashvars = {};
    var params = {};
    params.quality = "high";
    params.bgcolor = "#ffffff";
    params.allowscriptaccess = "sameDomain";
    params.allowfullscreen = "true";
    params.uid = "<?php echo $uid ?>";
    params.userid = "<?php echo $uid ?>";
    var attributes = {};
    attributes.id = "TestMain";
    attributes.name = "TestMain";
    attributes.align = "middle";
    flashvars = params;
    swfobject.embedSWF(
        "<?php echo $flash_url ?>", "flashContent",
        "1280", "720",
        swfVersionStr, xiSwfUrlStr,
        flashvars, params, attributes);
    // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
    swfobject.createCSS("#flashContent", "display:block;text-align:left;");
</script>
</body>
</html>