<?php
define("DIR",$_SERVER['DOCUMENT_ROOT']);
$tpl = file_get_contents(DIR."/app/tpl.html");

$_links = [];
foreach (json_decode(file_get_contents(DIR.'/app/docs.json'),true) as $ttl => $val) {
	$link = "";
	if (is_array($val)) {
		$var = explode("||",$ttl);
		$title = $var[0];
		$link = $var[1];
		// $link = $ttl;
		// $link = strtolower($link);
		// $link = str_replace(' ','-',$link);
		// $_links[$ttl] = "/{$link}.html";
		$_links[$title] = $link;
		foreach ($val as $t=>$v) {
			$_links[$t] = $v;
		}
	}
	else {
		$_links[$ttl] = $val;
	}
}

echo json_encode($_links)."<br>";

foreach ($_links as $k=>$v) {
	if (!file_exists(DIR.$v)) {
		$fd = fopen(DIR.$v,'w+');
		fputs($fd,'');
		fclose($fd);
	}
}

function genMenu($link) {
	$menu = "";
	foreach (json_decode(file_get_contents(DIR."/app/docs.json")) as $ttl => $val) {
		if (!is_array($val)) {
			$menu.= "<li><a href=\"{$val}\"";
			if ($val == $link) $menu.= " class=\"--active\"";
			$menu.= ">{$ttl}</a></li>";
		}
		else {
			$var = explode("||",$ttl);
			// $lnk = "/".strtolower($ttl).".html";
			$lmk = $var[1];
			$menu.= "<li><a href=\"{$lnk}\"";
			if ($lnk == $link) $menu.= " class=\"--active\"";
			// $menu.= ">{$ttl}</a><ul>";
			$menu.= ">{$var[0]}</a><ul>";
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
			$var = explode("||",$ttl);
			// $lnk = "/".strtolower($ttl).".html";
			$lnk = $var[1];
			$html.= "<li";
			if ($lnk == $link) $html.= " class=\"--active\"";
			$html.= "><a href=\"{$lnk}\">{$var[0]}</a><ul>";
			// $html.= "><a href=\"{$lnk}\">{$ttl}</a><ul>";
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
		echo "{$link}<br>";
		echo DIR.$link."<br>";
		createPage($t,$link,1);
}