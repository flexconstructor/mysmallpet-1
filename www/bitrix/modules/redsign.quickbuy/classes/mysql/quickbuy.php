<?
/************************************
*
* mysql mail class
* last update 04.04.2017
*
************************************/

IncludeModuleLangFile(__FILE__);

class CRSQUICKBUYElements
{
	protected static $tableName = 'b_redsign_quckbuy';

	public $ids, $iblockId;
	public $lastDiscItem = array();

	public function getArrIds() {

		$lastDiscItem = $this->lastDiscItem;

		if (is_array($this->ids)) {
			foreach ($this->ids as $id) {
				$lastDiscItem[] = array(
					'CLASS_ID' => 'CondIBElement',
					'DATA' => array(
						'logic' => 'Equal',
						'value' => array($id),
					),
				); 
			}
		} else {
			$lastDiscItem[] = array(
				'CLASS_ID' => 'CondIBElement',
				'DATA' => array(
					'logic' => 'Equal',
					'value' => array($this->ids),
				),
			); 
		}

		$this->lastDiscItem = $lastDiscItem;

	}

	public function createDiscountCondition() {

		$arFieldsCond = array(
			'CLASS_ID' => 'CondGroup',
			'DATA' => array(
				'All' => 'AND',
				'True' => 'True',
			),
			'CHILDREN' => array(
				'0' => array(
					'CLASS_ID' => 'CondBsktProductGroup',
					'DATA' => array(
						'Found' => 'Found',
						'All' => 'OR',
					),
					'CHILDREN' => $this->lastDiscItem,
				),
			),
		);
		
		return $arFieldsCond;
	}

	public function createDiscountActions($discount, $type_discount) {

		$arFieldsAct = array(
			'CLASS_ID' => 'CondGroup',
			'DATA' => array(
				'All' => 'AND',
			),
			'CHILDREN' => array(
				'0' => array(
					'CLASS_ID' => 'ActSaleBsktGrp',
					'DATA' => array(
						'Type' => 'Discount',
                        'Value' => $discount,
                        'Unit' => $type_discount,
                        'Max' => 0,
                        'All' => 'OR',
                        'True' => 'True',
					),
					'CHILDREN' => $this->lastDiscItem,
				),
			),
		);

		return $arFieldsAct;
	}

	function checkOffers($id) {

		$arrOffersID = array();

		$res = CIBlockElement::GetByID($id);
		if($ar_res = $res->GetNext())
			$this->iblockId = $ar_res["IBLOCK_ID"];
		else
			return false;


		$arCatalog = CCatalog::GetByIDExt($this->iblockId);
		if(IntVal($arCatalog["OFFERS_IBLOCK_ID"])>0 && IntVal($arCatalog["OFFERS_PROPERTY_ID"])>0)
		{
			$this->iblockId = IntVal($arCatalog["OFFERS_IBLOCK_ID"]);
			$arrOffersID = array();
			$arFilter = Array(
				"IBLOCK_ID" => $this->iblockId,
				"PROPERTY_".$arCatalog["OFFERS_PROPERTY_ID"]."_VALUE" => $id, 
			);
			$res = CIBlockElement::GetList(Array("ID"=>"ASC"), $arFilter, Array("ID"));
			while($arElemFields = $res->GetNext())
			{
				$arrOffersID[] = $arElemFields["ID"];
			}
		}

		if ($arCatalog['CATALOG'] != "Y")
			return false;

		return $arrOffersID;

	}
	
	function GetList($aSort=array(), $aFilter=array())
	{
		$bitrix_default_quantity_trace = COption::GetOptionString('catalog', 'default_quantity_trace', 'N');
		global $DB;
		$arFilter = array();
		foreach($aFilter as $key=>$val)
		{
			if(!is_array($val) && strlen($val)<=0)
				continue;
			switch(strtoupper($key))
			{
				case 'ID':
					if(is_array($val))
					{
						$arFilter[] = "(QB.ID='".implode("' or QB.ID='",$val)."')";
					} else {
						$arFilter[] = "QB.ID='".$DB->ForSql($val)."'";
					}
					break;
				case 'ELEMENT_ID':
					if(is_array($val))
					{
						$arFilter[] = "(QB.ELEMENT_ID='".implode("' or QB.ELEMENT_ID='",$val)."')";
					} else {
						$arFilter[] = "QB.ELEMENT_ID='".$DB->ForSql($val)."'";
					}
					break;
				case 'FOR_OFFERS':
					$arFilter[] = "QB.FOR_OFFERS='".($val='Y'?'Y':'N')."'";
					break;
				case 'ACTIVE':
					$arFilter[] = "QB.ACTIVE='".($val=='Y'?'Y':'N')."'";
					break;
				case 'DATE_FROM':
					$arFilter[] = "QB.DATE_FROM < ".$DB->CharToDateFunction( $DB->ForSql($val) )."";
					break;
				case 'DATE_TO':
					$arFilter[] = "QB.DATE_TO > ".$DB->CharToDateFunction( $DB->ForSql($val) )."";
					break;
				case 'DISCOUNT':
					$arFilter[] = "QB.DISCOUNT LIKE='".$DB->ForSql($val)."'";
					break;
				case 'VALUE_TYPE':
					$arFilter[] = "QB.VALUE_TYPE='".$DB->ForSql($val)."'";
					break;
				case 'QUANTITY':
					if($bitrix_default_quantity_trace=='Y')
					{
						$arFilter[] = "QB.QUANTITY>'".$DB->ForSql($val)."'";
					}
					break;
				case 'AUTO_RENEWAL':
					$arFilter[] = "QB.AUTO_RENEWAL='".($val=='Y'?'Y':'N')."'";
					break;
			}
		}

		$arOrder = array();
		foreach($aSort as $key=>$val)
		{
			$ord = (strtoupper($val) <> 'ASC'?'DESC':'ASC');
			switch(strtoupper($key))
			{
				case 'ID':
					$arOrder[] = "QB.ID ".$ord;
					break;
				case 'ELEMENT_ID':
					$arOrder[] = "QB.ELEMENT_ID ".$ord;
					break;
				case 'DATE_FROM':
					$arOrder[] = "QB.DATE_FROM ".$ord;
					break;
				case 'DATE_TO':
					$arOrder[] = "QB.DATE_TO ".$ord;
					break;
				case 'DISCOUNT':
					$arOrder[] = "QB.DISCOUNT ".$ord;
					break;
				case 'QUANTITY':
					$arOrder[] = "QB.QUANTITY ".$ord;
					break;
			}
		}
		if(count($arOrder) == 0)
			$arOrder[] = "QB.ID DESC";
		$sOrder = "\nORDER BY ".implode(", ",$arOrder);

		if(count($arFilter) == 0)
			$sFilter = "";
		else
			$sFilter = "\nWHERE ".implode("\nAND ", $arFilter);

		$strSql = "
			SELECT
				QB.*,
				".$DB->DateToCharFunction("QB.DATE_FROM")." DATE_FROM,
				".$DB->DateToCharFunction("QB.DATE_TO")." DATE_TO
			FROM
				".self::$tableName." QB
			".$sFilter.$sOrder;
		
		return $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
	}
	
	function GetByID($ID)
	{
		global $DB;
		
		return self::GetList(array("ID"=>"SORT"),array("ID"=>$ID));
	}
	
	function GetByElementID($ID)
	{
		global $DB;
		
		return self::GetList(array("ID"=>"SORT"),array("ELEMENT_ID"=>$ID));
	}
	
	function Delete($ID)
	{
		global $DB;
		
		if(!\Bitrix\Main\Loader::includeModule('catalog') ||
			!\Bitrix\Main\Loader::includeModule('sale')) {
		    die();
		}
		
		$ID = intval($ID);
		
		$resource = self::GetByID($ID);
		if($data = $resource->Fetch())
		{
			/////////////////////////////// OFFERS and SIMPLE
			$arrDiscountArrayID = unserialize( $data["DISCOUNT_ID_ARRAY"] );
			foreach($arrDiscountArrayID as $DISCOUNT_ID)
			{
				CSaleDiscount::Delete($DISCOUNT_ID);
			}
			$DB->StartTransaction();
			
			$res = $DB->Query("DELETE FROM ".self::$tableName." WHERE ID=".$ID, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			
			if($res)
				$DB->Commit();
			else
				$DB->Rollback();
		}
		return $res;
	}
	
	function Add($arFields)
	{
		global $DB;

		if (!\Bitrix\Main\Loader::includeModule('iblock') ||
			!\Bitrix\Main\Loader::includeModule('catalog') ||
			!\Bitrix\Main\Loader::includeModule('sale')) {
		    die();
		}

		if(isset($arFields['ID']))
			unset($arFields['ID']);
		
		$arFields['ACTIVE'] = $arFields['ACTIVE'] == 'Y' ? 'Y' : 'N';

		$arrOffersID = $this->checkOffers($arFields["ELEMENT_ID"]);

		if (!is_array($arrOffersID))
			return false;

		$groups = array();
		$resGroupList = CGroup::GetList($by = 'c_sort', $order = 'asc');
		while ($group = $resGroupList->GetNext()){ 
			$groups[] = $group["ID"]; 
		}
		
		$arrLIDs = array();
		$rsSites = CIBlock::GetSite($this->iblockId);
		while($arSite = $rsSites->Fetch())
		{
			$arrLIDs[] = $arSite['LID'];
		}

		$arrDiscountArrayID = array();
		if(is_array($arrOffersID) && count($arrOffersID)>0) {

			$arFields['FOR_OFFERS'] = 'Y';
			$this->ids = $arrOffersID;

		} else {

			$arFields['FOR_OFFERS'] = 'N';
			$this->ids = $arFields['ELEMENT_ID'];

		}
			
		foreach($arrLIDs as $LID)
		{
			$this->getArrIds();

			$arFieldsCond = $this->createDiscountCondition();

			$arFieldsAct = $this->createDiscountActions($arFields['DISCOUNT'], $arFields['VALUE_TYPE']);

			$arFieldsDiscount = array(
				'LID' => $LID,
				'NAME' => GetMessage('RSQB.DISCOUNT_NAME').' | Product ID = '.$arFields['ELEMENT_ID'],
				'ACTIVE_FROM' => $arFields['DATE_FROM'],
				'ACTIVE_TO' => $arFields['DATE_TO'],
				'ACTIVE' => 'Y',
				'SORT' => 100,
				'PRIORITY' => 10,
				'LAST_DISCOUNT' => 'N',
				'LAST_LEVEL_DISCOUNT' => 'N',
				'XML_ID' => '',
				'CONDITIONS' => $arFieldsCond,
				'ACTIONS' => $arFieldsAct,
				'USER_GROUPS' => $groups,
			);
			$DISCOUNT_ID = CSaleDiscount::Add($arFieldsDiscount);
			if($DISCOUNT_ID>0)
			{
				$arrDiscountArrayID[] = $DISCOUNT_ID;
			}
		}

		if(is_array($arrDiscountArrayID) && count($arrDiscountArrayID)>0)
		{
			$arFields['DISCOUNT_ID_ARRAY'] = serialize($arrDiscountArrayID);
			$ID = $DB->Add(self::$tableName, $arFields);
		}
		
		return $ID;
	}
	
	function Update($ID, $arFields, $updAct, $updDisc = false)
	{
		global $DB;
		
		if(!\Bitrix\Main\Loader::includeModule('iblock') ||
			!\Bitrix\Main\Loader::includeModule('catalog') ||
			!\Bitrix\Main\Loader::includeModule('sale')) {
		    die();
		}
		
		$ID = intval($ID);
		
		if(isset($arFields["ID"]))
			unset($arFields["ID"]);		

		$resource = self::GetByID($ID);
		if($data = $resource->Fetch())
		{

			if ($updAct) {

				$arrOffersID = $this->checkOffers($arFields["ELEMENT_ID"]);

				if(is_array($arrOffersID) && count($arrOffersID)>0) {

					$arFields['FOR_OFFERS'] = 'Y';
					$this->ids = $arrOffersID;

				} else {

					$arFields['FOR_OFFERS'] = 'N';
					$this->ids = $arFields['ELEMENT_ID'];
					
				}

				$this->getArrIds();

				$arFieldsCond = $this->createDiscountCondition();

				$arFieldsAct = $this->createDiscountActions($arFields['DISCOUNT'], $arFields['VALUE_TYPE']);

				$arFieldsDisc['CONDITIONS'] = $arFieldsCond; 
				$arFieldsDisc['ACTIONS'] = $arFieldsAct; 

			}
			
			$strUpdate = $DB->PrepareUpdate(self::$tableName, $arFields);
			if($strUpdate!="")
			{
				/////////////////////////////// OFFERS and SIMPLE
				$DB->Query("UPDATE ".self::$tableName." SET ".$strUpdate." WHERE ID=".$ID, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				$arrDiscountArrayID = unserialize( $data["DISCOUNT_ID_ARRAY"] );
				foreach($arrDiscountArrayID as $DISCOUNT_ID)
				{
					$arFieldsDisc['ACTIVE_FROM'] = $arFields['DATE_FROM'];
					$arFieldsDisc['ACTIVE_TO'] = $arFields['DATE_TO'];

					if ($updDisc)
						CSaleDiscount::Update($DISCOUNT_ID, $arFieldsDisc);

				}
			}
		}
		return true;
	}
	
	function CheckAutoRenewal()
	{
		if(!defined("ADMIN_SECTION") || ADMIN_SECTION !== true)
		{
			global $DB;
			
			$time = ConvertTimeStamp(time(),"FULL");
			$sFilter = "\nWHERE QB.AUTO_RENEWAL='Y' AND QB.DATE_TO < ".$DB->CharToDateFunction($time)."";
			$sOrder = "\nORDER BY QB.ID DESC";
			$strSql = "
				SELECT
					QB.*,
					".$DB->DateToCharFunction("QB.DATE_FROM")." DATE_FROM,
					".$DB->DateToCharFunction("QB.DATE_TO")." DATE_TO
				FROM
					".self::$tableName." QB
				".$sFilter.$sOrder;
			
			$res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			while($arData = $res->Fetch())
			{
				$TS_DATE_FROM = new \Bitrix\Main\Type\DateTime($arData["DATE_FROM"]);
				$TS_DATE_TO = new \Bitrix\Main\Type\DateTime($arData["DATE_TO"]);
				$NEW_DATE_TO_ = $TS_DATE_TO->getTimestamp() + ( $TS_DATE_TO->getTimestamp() - $TS_DATE_FROM->getTimestamp());
				$NEW_DATE_TO = \Bitrix\Main\Type\DateTime::createFromTimestamp($NEW_DATE_TO_);

				$arFields = array(
					"ELEMENT_ID" => $arData["ELEMENT_ID"],
					"DATE_FROM" => $arData["DATE_TO"],
					"DATE_TO" => $NEW_DATE_TO->toString(),
				);
				self::Update($arData["ID"],$arFields, false, true);
			}
		}
	}
}
