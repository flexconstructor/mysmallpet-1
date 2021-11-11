<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();


$arResult['AGREEMENT_ORIGINATOR_ID'] = isset($arResult['AGREEMENT_ORIGINATOR_ID'])
	? $arResult['AGREEMENT_ORIGINATOR_ID']
	: 'main/reg';

$arResult['AGREEMENT_ORIGIN_ID'] = isset($arResult['AGREEMENT_ORIGIN_ID'])
	? $arResult['AGREEMENT_ORIGIN_ID']
	: 'register';

$arResult['AGREEMENT_INPUT_NAME'] = isset($arResult['AGREEMENT_INPUT_NAME'])
	? $arResult['AGREEMENT_INPUT_NAME']
	: 'USER_AGREEMENT';
