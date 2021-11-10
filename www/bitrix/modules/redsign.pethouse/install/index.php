<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class redsign_pethouse extends CModule
{
	var $MODULE_ID = "redsign.pethouse";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function redsign_pethouse()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("REDSIGN.PETHOUSE.INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("REDSIGN.PETHOUSE.INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = GetMessage("REDSIGN.PETHOUSE.SPER_PARTNER");
		$this->PARTNER_URI = GetMessage("REDSIGN.PETHOUSE.PARTNER_URI");
	}


	function InstallDB($install_wizard = true)
	{
		global $DB, $DBType, $APPLICATION;

		RegisterModule("redsign.pethouse");
		COption::SetOptionString("redsign.pethouse", "wizard_version", "1");
		RegisterModuleDependences("main", "OnBeforeProlog", "redsign.pethouse", "rsPetHouse", "ShowPanel");

        if(CModule::IncludeModule('redsign.pethouse'))
		{
			rsPetHouse::InstallModuleOptions();
		}
        
		return true;
	}

	function UnInstallDB($arParams = Array())
	{
		global $DB, $DBType, $APPLICATION;

		UnRegisterModule("redsign.pethouse");
		UnRegisterModuleDependences("main", "OnBeforeProlog", "redsign.pethouse", "rsPetHouse", "ShowPanel");

		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles()
	{
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.pethouse/install/modules", $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.pethouse/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		//CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.pethouse/install/wizards/bitrix/eshop.mobile", $_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/bitrix/eshop.mobile", true, true);
		//CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.pethouse/install/images",  $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/redsign.pethouse", true, true);
	
		return true;
	}

	function InstallPublic()
	{
	}

	function UnInstallFiles()
	{
		//DeleteDirFilesEx("/bitrix/images/redsign.pethouse/");//images
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION, $step;

		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallPublic();
		return true;
	}

	function DoUninstall()
	{
		global $APPLICATION, $step;

		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();
		return true;
	}
}
?>