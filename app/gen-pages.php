<?php
define("DIR",$_SERVER['DOCUMENT_ROOT']);
$tpl = file_get_contents(DIR."/app/tpl.html");

$_links = [];
foreach (json_decode(file_get_contents(DIR.'/app/docs.json'),true) as $ttl => $val) {
	$link = "";
	if (is_array($val)) {
		$link = $ttl;
		$link = strtolower($link);
		$link = str_replace(' ','-',$link);
		$_links[$ttl] = "/{$link}.html";
		foreach ($val as $t=>$v) {
			$_links[$t] = $v;
		}
	}
	else {
		$_links[$ttl] = $val;
	}
}

// echo json_encode($_links);
// var_dump($links);
foreach ($_links as $k=>$v) {
	if (!file_exists(DIR.$v)) {
		$fd = fopen(DIR.$v,'w+');
		fputs($fd,'');
		fclose($fd);
	}
}

// echo "<br><br>";

// $hld = opendir(DIR.'/docs');
// while ($file = readdir($hld)) {
// 	if ( ($file != '..') && ($file != '.') ) {
// 		foreach ($_links as $t=>$link) {
// 			$lnk = str_replace('/docs/','',$link);
// 			if ($lnk == $file) echo "! {$file}<br>";
// 		}
// 	}
// }
// closedir($hld);

function genMenu($link) {
	$menu = "";
	foreach (json_decode(file_get_contents(DIR."/app/docs.json")) as $ttl => $val) {
		if (!is_array($val)) {
			$menu.= "<li><a href=\"{$val}\"";
			if ($val == $link) $menu.= " class=\"--active\"";
			$menu.= ">{$ttl}</a></li>";
		}
		else {
			$lnk = "/".strtolower($ttl).".html";
			$menu.= "<li><a href=\"{$lnk}\"";
			if ($lnk == $link) $menu.= " class=\"--active\"";
			$menu.= ">{$ttl}</a><ul>";
			foreach ($val as $k=>$v) {
				$menu.= "<li><a href=\"{$v}\"";
				if ($v == $link) $menu.= " class=\"--active\"";
				$menu.= ">{$k}</a></li>";
			}
			$menu.= "</ul></li>";
		}
	}
	return $menu;
}

function getMenu($link) {
	$html = "";
	foreach (json_decode(file_get_contents(DIR.'/app/docs.json'),true) as $ttl => $val) {
		if (is_array($val)) {
			$lnk = "/".strtolower($ttl).".html";
			$html.= "<li";
			if ($lnk == $link) $html.= " class=\"--active\"";
			$html.= "><a href=\"{$lnk}\">{$ttl}</a><ul>";
			foreach ($val as $k=>$v) {
				$html.= "<li";
				if ($v == $link) $html.= " class=\"--active\"";
				$html.= "><a href=\"{$v}\">{$k}</a></li>";
			}
			$html.= "</ul></li>";
		}
		else {
			$html.= "<li";
			if ($val == $link) $html.= " class=\"--active\"";
			$html.= "><a href=\"{$val}\">{$ttl}</a></li>";
		}
	}
	return $html;
}

function genPage($title,$link) {
	$tpl = file_get_contents($_SERVER['DOCUMENT_ROOT']."/app/tpl.html");
	$html = $tpl;
	$_d = [];

	$_d['title'] = $title;
	$config = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/app/config.json"),true);
	$_d['site-title'] = $config['title'];
	$_d['url'] = $config['url'].$link;
	$bc = "";
	$lnk = "";
	foreach (explode("/",$link) as $val) {
		if (!empty($val)) {
			$lnk.= "/{$val}";
			$ttl = str_replace("-"," ",$val);
			$ttl = str_replace(".html","",$ttl);
			$ttl = ucfirst($ttl);
			$bc.= "<li><a href=\"{$lnk}.html\">{$ttl}</a></li>";
		}
	}
	$_d['bc'] = $bc;
	$_d['menu'] = getMenu($link);
	$_d['content'] = file_get_contents($_SERVER['DOCUMENT_ROOT']."/app/content{$link}");
	$_d['year'] = date('Y');

	foreach ($_d as $k=>$v) {
		$html = str_replace("%{$k}%",$v,$html);
	}

	return $html;
}

function createPage($title,$link,$foreced=0) {
	$continue = 0;
	$content = "";
	error_log('Create Page '.$link);
	if ($forced = 1) $continue = 1;
	else {
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].$link)) $continue = 1;
	}
	
	if ($continue == 1) {
		$fd = fopen($_SERVER['DOCUMENT_ROOT'].$link,"w+");
		$content = genPage($title,$link);
		fputs($fd,$content);
		fclose($fd);
	}
}

foreach ($_links as $t=>$link) {
	// echo "link: {$link}<br>";
	// if (!file_exists(DIR.$link)) {
		echo "{$link}<br>";
		echo DIR.$link."<br>";
		// $fd = fopen(DIR."/tmp".$link,"w+");
		// $content = genPage($t,$link);
		// fputs($fd,$content);
		// fclose($fd);
		createPage($t,$link,1);
	// }
	// $lnk = str_replace('/docs/','',$link);
	// if ($lnk == $file) echo "! {$file}<br>";

}