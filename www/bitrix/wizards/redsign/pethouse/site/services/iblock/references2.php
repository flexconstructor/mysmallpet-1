<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!CModule::IncludeModule("highloadblock"))
	return;

if (!WIZARD_INSTALL_DEMO_DATA)
	return;

$COLOR_ID = $_SESSION["ESHOP_HBLOCK_COLOR_ID"];
unset($_SESSION["ESHOP_HBLOCK_COLOR_ID"]);

$BRAND_ID = $_SESSION["ESHOP_HBLOCK_BRAND_ID"];
unset($_SESSION["ESHOP_HBLOCK_BRAND_ID"]);

//adding rows
WizardServices::IncludeServiceLang("references.php", LANGUAGE_ID);

use Bitrix\Highloadblock as HL;
global $USER_FIELD_MANAGER;

if (intval($COLOR_ID) > 0)
{
	$hldata = HL\HighloadBlockTable::getById($COLOR_ID)->fetch();
	if (is_array($hldata))
	{
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

		$entity_data_class = $hlentity->getDataClass();
		$arColors = array(
			"COLOR1" => "references_files/color1.jpg",
			"COLOR2" => "references_files/color2.jpg",
			"COLOR3" => "references_files/color3.jpg",
			"COLOR4" => "references_files/color4.jpg",
			"COLOR5" => "references_files/color5.jpg",
			"COLOR6" => "references_files/color6.jpg",
			"COLOR7" => "references_files/color7.jpg",
			"COLOR8" => "references_files/color8.jpg",
			"COLOR9" => "references_files/color9.jpg",
			"COLOR10" => "references_files/color10.jpg",
			"COLOR11" => "references_files/color11.jpg",
			"COLOR12" => "references_files/color12.jpg",
			"COLOR13" => "references_files/color13.jpg",
			"COLOR14" => "references_files/color14.jpg",
			"COLOR15" => "references_files/color15.jpg",
			"COLOR16" => "references_files/color16.jpg",
		);
		$sort = 0;
		foreach($arColors as $colorName=>$colorFile)
		{
			$sort+= 100;
			$arData = array(
				'UF_NAME' => GetMessage("WZD_REF_COLOR_".$colorName),
				'UF_FILE' =>
					array (
						'name' => ToLower($colorName).".jpg",
						'type' => 'image/jpeg',
						'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$colorFile
					),
				'UF_SORT' => $sort,
				'UF_DEF' => ($sort > 100) ? "0" : "1",
				'UF_XML_ID' => ToLower($colorName)
			);
			$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$COLOR_ID, $arData);
			$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$COLOR_ID, null, $arData);

			$result = $entity_data_class::add($arData);
		}
	}
}

if (intval($BRAND_ID) > 0)
{
	$hldata = HL\HighloadBlockTable::getById($BRAND_ID)->fetch();
	if (is_array($hldata))
	{
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

		$entity_data_class = $hlentity->getDataClass();
		$arBrands = array(
			"BRAND1" => "",
			"BRAND2" => "",
			"BRAND3" => "",
			"BRAND4" => "",
			"BRAND5" => "",
			"BRAND6" => "",
			"BRAND7" => "",
			"BRAND8" => "",
			"BRAND9" => "",
			"BRAND10" => "",
			"BRAND11" => "",
			"BRAND12" => "",
			"BRAND13" => "",
			"BRAND14" => "",
			"BRAND15" => "",
		);
		$sort = 0;
		foreach($arBrands as $brandName=>$brandFile)
		{
			$sort+= 100;
			$arData = array(
				'UF_NAME' => GetMessage("WZD_REF_BRAND_".$brandName),
				'UF_FILE' =>
					array (
						/*
						'name' => ToLower($brandName).".png",
						'type' => 'image/png',
						'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$brandFile
						*/
					),
				'UF_SORT' => $sort,
				//'UF_DESCRIPTION' => GetMessage("WZD_REF_BRAND_DESCR_".$brandName),
				//'UF_FULL_DESCRIPTION' => GetMessage("WZD_REF_BRAND_FULL_DESCR_".$brandName),
				'UF_XML_ID' => ToLower($brandName)
			);
			$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$BRAND_ID, $arData);
			$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$BRAND_ID, null, $arData);

			$result = $entity_data_class::add($arData);
		}
	}
}
?>