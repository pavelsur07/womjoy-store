<?php

declare(strict_types=1);
header('Access-Control-Allow-Origin: *');
$files = scandir($D = __DIR__ . '/tpl');
unset($files[0], $files[1]);

$arTPL = [];

foreach ($files as $filesname) {
    $file_tmp = explode('.', $filesname);
    $arTPL[strtolower($file_tmp[0])] = file_get_contents($D . '/' . $filesname);
}

echo str_replace(['\r', '\n', '\t', "\n", "\r", "\t"], '', json_encode($arTPL));
