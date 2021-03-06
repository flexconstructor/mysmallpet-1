<?
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\EventManager;
use \Bitrix\Main\Localization\Loc;

global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class redsign_favorite extends CModule
{
	var $MODULE_ID = "redsign.favorite";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function redsign_favorite()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("REDSIGN.FAVORITE.INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("REDSIGN.FAVORITE.INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = GetMessage("REDSIGN.FAVORITE.SPER_PARTNER");
		$this->PARTNER_URI = GetMessage("REDSIGN.FAVORITE.PARTNER_URI");
	}

	function InstallDB($install_wizard = true)
	{
		global $DB, $DBType, $APPLICATION;

		ModuleManager::registerModule($this->MODULE_ID);
		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/redsign.favorite/install/db/".$DBType."/install.sql");

		return true;
	}

	function UnInstallDB($arParams = Array())
	{
		global $DB, $DBType, $APPLICATION;

		ModuleManager::unregisterModule($this->MODULE_ID);

		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/redsign.favorite/install/db/".$DBType."/uninstall.sql");

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
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.favorite/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/redsign.favorite/install/js', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js', true, true);

		return true;
	}

	function UnInstallFiles()
	{
		//DeleteDirFilesEx("/bitrix/images/redsign.favorite/");//images
		DeleteDirFilesEx("/bitrix/components/redsign/favorite.list/");
		DeleteDirFilesEx("/bitrix/js/redsign.favorite/");

		return true;
	}

	function InstallPublic()
	{
		return true;
	}

	function UnInstallPublic()
	{
		return true;
	}

	function InstallOptions()
	{
		return true;
	}

	function UnInstallOptions()
	{
		COption::RemoveOption('redsign.favorite');
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