<?php

// URL вашего CSS-файла с иконками
// $cssFileUrl = 'https://mikhailrepka.github.io/wokit/lib/key.css';
$cssFileUrl = $_SERVER['DOCUMENT_ROOT']."/lib/key.css";

// Использование cURL для получения содержимого CSS-файла
/*$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $cssFileUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$cssContent = curl_exec($ch);
curl_close($ch);*/

if (!ini_get('allow_url_fopen')) {
    die('The allow_url_fopen directive is disabled. The script cannot proceed.');
}
$cssContent = file_get_contents($cssFileUrl);

// Проверка успешности получения данных
if (false === $cssContent) {
    die('Failed to retrieve CSS content.');
}

// Регулярное выражение для поиска классов иконок
// $pattern = '/\.__wo-icon-([a-z0-9-]+)\s*{.*?}/';
$pattern = '/\.__wo-icon-([a-z0-9-]+):before/';
error_log('$pattern: '.$pattern);

// Поиск всех соответствий в CSS-файле
preg_match_all($pattern, $cssContent, $matches);
error_log('$matches: '.json_encode($matches));

// Начало таблицы
// echo '<table border="1" style="border-collapse: collapse;">';
// echo '<tr><th>Icon</th><th>Class Name</th><th>Description</th></tr>';

echo "<link href=\"https://mikhailrepka.github.io/wokit/lib/key.css\" rel=\"stylesheet\" charset=\"utf-8\">";
echo "<link href=\"https://mikhailrepka.github.io/wokit/lib/wo.css\" rel=\"stylesheet\" charset=\"utf-8\">";
echo "
<style>
.__icon {
	text-align: center;
	padding: var(--wo-gutter) 0;
	border: 1px solid var(--wo-bg-dim);
	margin-bottom: var(--wo-gutter);

	border-radius: var(--wo-border-radius-input);
	-moz-border-radius: var(--wo-border-radius-input);
	-webkit-border-radius: var(--wo-border-radius-input);

	transition: all .3s ease-out;
	-o-transition: all .3s ease-out;
	-ms-transition: all .3s ease-out;
	-moz-transition: all .3s ease-out;
	-webkit-transition: all .3s ease-out;
}
.__icon:hover {
	border-color: var(--wo-gray-light);
}
.__icon i {
	display: block;
	font-size: calc(var(--wo-font-size) * 2);
	margin-bottom: var(--wo-gutter);
	color: var(--wo-gray);

	transition: all .3s ease-out;
	-o-transition: all .3s ease-out;
	-ms-transition: all .3s ease-out;
	-moz-transition: all .3s ease-out;
	-webkit-transition: all .3s ease-out;
}
.__icon:hover i {
	color: var(--wo-black);
}
.__icon code {
	display: inline-block;
	font-size: calc(var(--wo-font-size) * .75);
	max-width: calc(100% - (var(--wo-gutter) * .5));
	overflow: auto;
	margin-bottom: var(--wo-gutter);
}
.__icon input {
	display: block;
	margin: 0 auto;
}
</style>
";

echo "<div class=\"__wo-row\">";

// Для каждого класса иконки
foreach ($matches[1] as $iconClassSuffix) {
    $iconClass = "__wo-icon-" . $iconClassSuffix;
    $description = ucwords(str_replace('-', ' ', $iconClassSuffix)); // Замена дефисов на пробелы и капитализация

    // Вывод строки таблицы
	echo "<div class=\"__wo-col-m-3 __wo-col-xs-6 __icon\">";
	echo "<i class=\"$iconClass\"></i>";
	echo "<code>.{$iconClass}</code>";
	echo "<input type=\"text\" disabled value=\".{$iconClass}\">";
	echo "</div>";

/*    echo "<tr>";
    echo "<td><i class=\"$iconClass\"></i></td>"; // Вам нужно будет определить стиль для <i> или использовать библиотеку иконок, которая соответствует этим классам
    echo "<td><code>$iconClass</code></td>";
    echo "<td>$description</td>";
    echo "</tr>"; */
}

// Конец таблицы
echo '</table>';
?>