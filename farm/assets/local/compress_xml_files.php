<?php

$ext = '.xml';

$suffix = trim($_REQUEST['suffix']);
if(empty($suffix)){
	$suffix = date('ymd_His',time()+28800);
}
if($suffix[0] != '_'){
	$suffix = '_' . $suffix;
}
$dir = './';
$output = $dir . date('YmdHis');
if(!file_exists($output)){
	mkdir($output,0777,true);
}
$count = 0;
foreach(glob($dir . '*' . $ext) as $xml){
	++$count;
	$new_name = $output . '/' . basename($xml,$ext) . $suffix . $ext;
	$contents = file_get_contents($xml);
	file_put_contents($new_name,gzcompress($contents,9));
}
echo 'process ',$count,' files';