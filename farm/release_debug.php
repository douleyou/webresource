<?php
$appId = $_REQUEST['appId'];// app id
$secu = 0;
if(isset($_REQUEST['secu'])){
	$secu = $_REQUEST['secu'];// secu id
}

$uid = $_REQUEST['uid'];// user id

// game_config.php带上版本号081301
require_once './game_config_debug.php';


$language = $_REQUEST['language'];// app id

///////////////////////////////////////////////////////
$supportLangDict = array('de_DE', 'en_US', 'es_ES', 'it_IT', 'pt_BR', 'ru_RU', 'th_TH', 'tr_TR', 'zh_TW');

$currLang = 'en_US';

if(in_array($language, $supportLangDict)){
	$currLang = $language;
}


$baseversion = '2014-12-03';

$game_env_version=$baseversion . '-2';
$game_text_version=$baseversion . '3';
$game_setting_version=$baseversion . '2';
$game_database_version=$baseversion . '2';
$game_fonts_version=$baseversion . '2';
$game_swf_version=$baseversion . '2';
$game_activities_version=$baseversion . '2';
$map_version=$baseversion . '2';
$icon_version=$baseversion . '2';
$sound_version=$baseversion . '2';

echo '<game>
<game_url>'.$game_url.'?aa=20141121</game_url>
<service_base>'.$service_base.'</service_base>
<game_config_url>'.$game_config_url.'?aa=xuuuuu</game_config_url>
<hashed_url></hashed_url>
<quest_config_url>'.$quest_config_url.'</quest_config_url>
<localization_url>'.$localization_url.'?aa=x</localization_url>
<asset_url>'.$asset_url.'</asset_url>
<compressxml>'.$compressxml.'</compressxml>
<game_class>GameMain</game_class>
<sns>'.$sns.'</sns>
<section>8</section>
<wwwGateway>https://starfarm.douleyou.com/farmfb/www/amf/gateway.php</wwwGateway>
<language>'.$currLang.'</language>
<locale>'.$currLang.'</locale>
<FILE_PATH>https://' .$cdn_server_domain . '/farm</FILE_PATH>
<game_env_version>'.$game_env_version.'</game_env_version>
<game_text_version>'.$game_text_version.'</game_text_version>
<game_setting_version>'.$game_setting_version.'</game_setting_version>
<game_database_version>'.$game_database_version.'</game_database_version>
<game_fonts_version>'.$game_fonts_version.'</game_fonts_version>
<game_swf_version>'.$game_swf_version.'</game_swf_version>
<game_activities_version>'.$game_activities_version.'</game_activities_version>
<map_version>'.$map_version.'</map_version>
<icon_version>'.$icon_version.'</icon_version>
<sound_version>'.$sound_version.'</sound_version>
</game>';


// Config.wwwGateway = "http://starfarm.douleyou.com/farmfb/www/amf/gateway.php";
// Config.language = "zh_TW";
// Config.locale = "zh_TW";
// //Config.FILE_PATH = "http://cdn.douleyou.com/farm";
?>