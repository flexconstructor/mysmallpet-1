<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

if (is_array($arResult['SECTIONS']) && count($arResult['SECTIONS'])) {
	foreach ($arResult['SECTIONS'] as $iSectionKey => $arSection) {

		if (is_array($arSection['PICTURE'])) {
            $arResult['SECTIONS'][$iSectionKey]['PICTURE']['RESIZE'] = CFile::ResizeImageGet(
                $arSection['PICTURE'],
                array('width' => 200, 'height' => 200),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
		}
	}
}
