<?php

echo "<textarea style=\"width:100%;height:100vh;\">";
foreach (json_decode(file_get_contents('/app/colors.json'),true) as $ttl => $clr) {
echo ".--color-{$ttl} {
	color: var(--wo-{$ttl});
}
.--bg-{$ttl} {
	background-color: var(--wo-{$ttl});
}
";
}
echo "</textarea>";