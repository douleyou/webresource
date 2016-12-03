#!/usr/bin/php -q
<?php
set_time_limit(0);
ini_set('display_errors', true);
error_reporting(E_ALL ^ E_NOTICE);
$start_time = microtime(true);
// register_shutdown_function('shutdown_func');

$base_dir = dirname(__FILE__);
$lang_dir = realpath($base_dir);

if(isset($_REQUEST['--help']) || isset($_REQUEST['-h'])){
	show_help();
}
$base_compare_lang = 'en_US';
if(isset($_REQUEST['--base'])){
	$base_compare_lang = $_REQUEST['--base'];
}

$target_lang = null;
if(isset($_REQUEST['--target'])){
	$target_lang =$_REQUEST['--target']; 	
}

$add_not_exists_key = false;
if(isset($_REQUEST['--add-not-exists'])){
	$add_not_exists_key = true;
}
if($add_not_exists_key){
	echo "the key not exists in compare file will be added";
}
$delete_extra_key = false;
if(isset($_REQUEST['--delete-extra-key'])){
	$delete_extra_key = true;
}

if($delete_extra_key){
	echo "the key not exists in base file will be delete";
}
if(isset($_REQUEST['--lang-dir'])){
	$lang_dir = $_REQUEST['--lang-dir']; 	
}
$skip_extra_keys = true;

if(!is_dir($lang_dir)){
	die("language dir not exists: $lang_dir");
}

// print_msg( "check lang file in $lang_dir");
$list = get_lang_list($lang_dir);
if(empty($list)){
	die("no lang file found");
}
$parse_errors = array();
//print_r($list);

if(!isset($list[$base_compare_lang])){
	die("compare base language $base_compare_lang not exist");
}else{
// 	print_msg( "use base language: $base_compare_lang");
}
$base_lang = read_lang_file($list[$base_compare_lang]);
unset($list[$base_compare_lang]);
if(!empty($target_lang)){
	if(isset($list[$target_lang])){
		$list = array($target_lang => $list[$target_lang]);
	}else{
		die("target compare lang $target_lang not found");
	}
}
$errno = 0;
$error = '';
$missing_keys = array();
foreach ($list as $lang_t => $file_xml){
	$re = check_lang_file($base_lang, $file_xml, $lang_t, $errno, $error);
	if ($re === true) {
		continue;
	}
	if (is_array($re) && !empty($re['not_exists'])) {
		$missing_keys[$lang_t] = $re['not_exists'];
	}
}

if (!empty($parse_errors)) {
	foreach ($parse_errors as $lang_f => $err_msgs) {
		$lang_name = basename($lang_f);
		print_msg("parse xml file $lang_name with errors:");
		print_msg("\t" . implode("\t",$err_msgs));
	}
}
if (!empty($missing_keys)) {
	foreach ($missing_keys as $lang => $keys) {
		print_msg("following keys not exists in lang $lang:");
		foreach ($keys as $lang_id => $lang_value) {
			print_msg("\tid=" . $lang_id . " val=" . $lang_value);
		}
	}
}

if (!empty($parse_errors) || !empty($missing_keys)) {
	exit(1);
}


function show_help(){
	$file = basename(__FILE__);
	echo <<<USAGE
$file [options] 
    Options:
        --base              the base compare language, default is en_US
        --target            the target compare language, if not set, compare 
                            base with other language in the --lang-dir
        --lang-dir          the folder where language file could be found
        --help,-h           show this message
        --add-not-exists    add not exist keys to compare according to the base language file
        --delete-extra-key  delete the keys not exist in base language from compared file
USAGE;
	exit();
}

function shutdown_func(){
	global $start_time;
	if($start_time > 0){
		printf("used time: %.1f s\n",microtime(true)-$start_time);
	}
}

function check_lang_file($base,$lang_file,$lang,&$errno,&$error){
	$compare_lang = read_lang_file($lang_file);
	if($compare_lang === false){
		$errno = 1001;
		$error = "parse xml file $lang_file error";
		return false;
	}
	if(empty($compare_lang)){
// 		 print_msg("no lang key found in file $lang_file");
		$errno = 1002;
		$error = "parse xml file $lang_file get empty, please check format";
		return false;
	}
	$not_exists_keys = array_diff_key($base, $compare_lang);
	$extra_keys = array_diff_key($compare_lang, $base);
// 	print_msg( "The result for compare $lang :");
	if(empty($not_exists_keys) && empty($extra_keys)){
// 		 print_msg( "OK");
		$errno = 0;
		$error = '';
		return true;
	}
// 	if(!empty($not_exists_keys)){
// 		 print_msg("  The following keys not exist in lang $lang");
// 		foreach ($not_exists_keys as $id => $val){
// 			print_msg('id='. $id . ' val=' . $val);
// 		}
// 	}
// 	if(!empty($extra_keys)){
// 		 print_msg("  The following keys not exist in base lang, but exists in $lang");
// 		foreach ($extra_keys as $id => $val){
// 			 print_msg('id=' . $id . ' val=' . $val);
// 		}
// 	}
	
// 	 print_msg('Total not exist keys:'. count($not_exists_keys) . "\n".
// 		'Total extra keys:'. count($extra_keys));
	return array('not_exists' => $not_exists_keys,'extra_keys' => $extra_keys);
}

function print_msg($msg){
	echo $msg , PHP_EOL;
}

function read_lang_file($file){
	global $parse_errors;
	libxml_use_internal_errors(true);
	$xml = simplexml_load_file($file);
	if(!$xml){
// 		 print_msg("load xml file $file fail");
		foreach(libxml_get_errors() as $error) {
// 	        print_msg($error->message);
			$parse_errors[$file][] = $error->message;
	    }
	    return false;
	}
	
	$result = array();
	$unset_keys = array();
	foreach ($xml->module->key as $key){
		$id = strtolower((string)$key['id']);
		$val = (string)$key['value'];
		if(isset($result[$id]) && substr($id, -2) == '[]'){
			$new_id = substr($id, 0, -2);
			if(is_array($result[$new_id])){
				$result[$new_id][] = $val;
			}else{
				$result[$new_id] = array($result[$new_id],$val);
			}
			$unset_keys[$id] = $id;
		}else{
			$result[$id] = $val;
		}
	}
	if(!empty($unset_keys)){
		foreach ($unset_keys as $k){
			if(isset($result[$k])){
				unset($result[$k]);
			}
		}
	}
	return $result;
}

function get_lang_list($dir){
	$files = glob($dir . '/*.xml');
	$result = array();
	foreach ($files as $file){
		if($file == '.' || $file == '..'){
			continue;
		}
		$lang = substr(basename($file,'.xml'),5);
		$result[$lang] = $file;
	}
	
	return $result;
}
