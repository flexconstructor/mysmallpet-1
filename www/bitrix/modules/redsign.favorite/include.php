<?

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

global $DBType, $DB, $MESS, $APPLICATION;

Loc::loadMessages(__FILE__);

$arClasses = array(
  'CRSFavorite' => 'classes/'.$DBType.'/favorite.php',
);

Loader::registerAutoLoadClasses(
	'redsign.favorite',
	$arClasses
);

CJSCore::RegisterExt('rs_favorite', array(
	'js' => '/bitrix/js/redsign.favorite/favorite.js'
));


function RSFavoritePropCheck($IBLOCK_ID)
{
	if (!Loader::includeModule('iblock')) {
		return false;
	}
	
	$PropertyID = 0;
	$CODE = 'RSFAVORITE_COUNTER';
	if (intval($IBLOCK_ID) > 0) {
		
		$propRes = CIBlockProperty::GetList(
			array(
				'ID' => 'ASC'
			),
			array(
				'IBLOCK_ID' => $IBLOCK_ID,
				'CODE' => $CODE
			)
		);

		if ($arProp = $propRes->GetNext()) {
			$PropertyID = $arProp['ID'];
		} else {
			$arFields = array(
				'NAME' => GetMessage('RS.FAVORITE.PROP_NAME'),
				'ACTIVE' => 'Y',
				'SORT' => '500',
				'CODE' => $CODE,
				'PROPERTY_TYPE' => 'N',
				'IBLOCK_ID' => $IBLOCK_ID,
				'WITH_DESCRIPTION' => 'N',
			);
			$iblockproperty = new CIBlockProperty;
			$PropertyID = $iblockproperty->Add($arFields);
		}
	}
	return $PropertyID;
}

function RSFavoriteCounterIncDec($ELEMENT_ID)
{
	if (!Loader::includeModule('iblock') || !Loader::includeModule('sale')) {
		return false;
	}
	$res = false;
	$CODE = 'RSFAVORITE_COUNTER';
	if (intval($ELEMENT_ID) > 0) {

		$res = CIBlockElement::GetByID($ELEMENT_ID);
		if($arElement = $res->GetNext())
		{
			$IBLOCK_ID = $arElement['IBLOCK_ID'];
			$dbProps = CIBlockElement::GetProperty(
				$IBLOCK_ID,
				$ELEMENT_ID,
				array(
					'ID' => 'ASC'
				),
				array(
					'CODE' => $CODE
				)
			);

			if ($arProps = $dbProps->Fetch()) {
				$propID = $arProps['ID'];
				$res = true;
				$VALUE = intval($arProps['VALUE']) > 0 ? $arProps['VALUE'] : 0;
			} else {
				$propID = RSFavoritePropCheck($IBLOCK_ID);
				$dbProps = CIBlockElement::GetProperty(
					$IBLOCK_ID,
					$ELEMENT_ID,
					array(
						'ID' => 'ASC'
					),
					array(
						'ID' => $propID
					)
				);
				
				if($arProps = $dbProps->Fetch()) {
					$res = true;
					$VALUE = intval($arProps['VALUE'])>0 ? $arProps['VALUE'] : 0;
				}
			}
			
			if ($res) {
				$FUserID = CSaleBasket::GetBasketUserID();
				$dbRes = CRSFavorite::GetList(
					array(),
					array(
						'FUSER_ID' => $FUserID,
						'ELEMENT_ID' => $ELEMENT_ID
					)
				);

				if ($arFields = $dbRes->Fetch()) {
					$res = 1;
					$VALUE--;
					CRSFavorite::Delete($arFields['ID']);
				} else {
					$res = 2;
					$VALUE++;
					$arFields = array(
						'FUSER_ID' => $FUserID,
						'ELEMENT_ID' => $ELEMENT_ID,
						'PRODUCT_ID' => 0,
					);
					CRSFavorite::Add($arFields);
				}
				$VALUE = (intval($VALUE) > 0 ? $VALUE : 0);
				CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $propID, $VALUE);
			}
		}
	}
	return $res;
}

function RSFavoriteAddDel($ELEMENT_ID)
{
	if (!Loader::includeModule('iblock')) {
		return false;
	}
	
	$res = false;
	if (intval($ELEMENT_ID) > 0) {

		$res = CIBlockElement::GetByID($ELEMENT_ID);
		if ($arElement = $res->GetNext()) {

			$iUserId = false;
			if (Loader::includeModule('sale')) {
				//ShowError(GetMessage("RS_SALE_NOT_INSTALLED"));
				$iUserId = CSaleBasket::GetBasketUserID();
			} else {

				global $USER;
				if ($USER->IsAuthorized()) {
					$iUserId = $USER->getId();
				}

			}

			if ($iUserId) {

				$dbRes = CRSFavorite::GetList(
					array(),
					array(
						'FUSER_ID' => $iUserId,
						'ELEMENT_ID' => $ELEMENT_ID
					)
				);
				
				if ($arFields = $dbRes->Fetch()) {
					$res = 1;
					CRSFavorite::Delete($arFields['ID']);
				} else {
					$res = 2;
					$arFields = array(
						'FUSER_ID' => $iUserId,
						'ELEMENT_ID' => $ELEMENT_ID,
						'PRODUCT_ID' => 0,
					);
					CRSFavorite::Add($arFields);
				}

			} else {
				if (is_array($_SESSION[CRSFavorite::SESSION_CODE]) && ($i = array_search($ELEMENT_ID, $_SESSION[CRSFavorite::SESSION_CODE])) !== false) {
					$res = 1;
					unset($_SESSION[CRSFavorite::SESSION_CODE][$i]);
				} else {
					$res = 2;
					$_SESSION[CRSFavorite::SESSION_CODE][] = $ELEMENT_ID;
				}
			}
		}
	}
	return $res;
}