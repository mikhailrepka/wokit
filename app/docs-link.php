<?php
echo "<textarea style=\"width:100%;height:100vh;\">";
foreach (json_decode(file_get_contents('/app/ocs.json'),true) as $ttl => $val) {
	if (is_array($val)) {
		$lnk = strtolower($ttl);
		$lnk = "/docs/{$lnk}.html";
		echo "<li><a href=\"{$lnk}\">{$ttl}</a>
	<ul>
";
		foreach ($val as $t=>$v) {
			echo "		<li><a href=\"{$v}\">{$t}</a></li>
";
		}
		echo "
	</ul>
</li>
";
	}
	else {
		echo "<li><a href=\"{$val}\">{$ttl}</a></li>
";
	}
}
echo "</textarea>";