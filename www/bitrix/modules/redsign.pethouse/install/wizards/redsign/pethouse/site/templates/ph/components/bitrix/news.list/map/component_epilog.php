<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

use \Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();

if ($arResult['MAP_TYPE'] == 'yandex') {
    $asset->addString('<script src="//api-maps.yandex.ru/2.0-stable/?load=package.standard&lang='.LANGUAGE_ID.'-'.strtolower(LANGUAGE_ID).'" type="text/javascript"></script>', true);
} elseif ($arResult['MAP_TYPE'] == 'google') {
    $asset->addString('<script async defer src="//maps.googleapis.com/maps/api/js?key='.$arParams['GOOGLE_API_KEY'].'"></script>', true);
}
