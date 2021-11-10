<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

$request = Application::getInstance()->getContext()->getRequest();
?>

<?if($arResult["ALLOW_ANONYMOUS"]=="Y" && $_REQUEST["authorize"]<>"YES" && $_REQUEST["register"]<>"YES"):?>
	<div class="col-xs-12">
<?/*
	<div class="panel">
		<div class="panel__head">
			<h2 class="panel__name"><?echo GetMessage('subscr_title_auth2')?></h2>
		</div>
		<div class="panel__body">
*/?>
			<div class="row">
			<div class="col-xs-12">
				<p><?echo GetMessage("adm_auth1")?> <a href="<?echo $arResult["FORM_ACTION"]?>?authorize=YES&amp;sf_EMAIL=<?echo $arResult["REQUEST"]["EMAIL"]?><?echo $arResult["REQUEST"]["RUBRICS_PARAM"]?>"><?echo GetMessage("adm_auth2")?></a>.</p>
				<?if($arResult["ALLOW_REGISTER"]=="Y"):?>
					<p><?echo GetMessage("adm_reg1")?> <a href="<?echo $arResult["FORM_ACTION"]?>?register=YES&amp;sf_EMAIL=<?echo $arResult["REQUEST"]["EMAIL"]?><?echo $arResult["REQUEST"]["RUBRICS_PARAM"]?>"><?echo GetMessage("adm_reg2")?></a>.</p>
				<?endif;?>
			</div>
			<div class="col-xs-12"><p><?echo GetMessage("adm_reg_text")?></p></div>
			</div>
<?/*
		</div>
	</div>
*/?>
	</div>
<?elseif($arResult["ALLOW_ANONYMOUS"]=="N" || $_REQUEST["authorize"]=="YES" || $_REQUEST["register"]=="YES"):?>
	<form class="col-xs-12 col-sm-6" action="<?=$arResult["FORM_ACTION"]?>" method="post">
		<div class="panel">
			<div class="panel__head">
				<h2 class="panel__name"><?echo GetMessage('adm_auth_exist')?></h2>
			</div>
			<div class="panel__body">
	
	<?echo bitrix_sessid_post();?>
    <p>
        <?if($arResult["ALLOW_ANONYMOUS"]=="Y"):?>
            <?echo GetMessage("subscr_auth_note")?>
        <?else:?>
            <?echo GetMessage("adm_must_auth")?>
        <?endif;?>
    </p>
    <div class="form-group">
		<input class="form-control" type="text" name="LOGIN" value="<?echo $arResult["REQUEST"]["LOGIN"]?>" size="20" placeholder="<?echo GetMessage("adm_auth_login")?>*">
    </div>
    <div class="form-group">
		<input class="form-control" type="password" name="PASSWORD" size="20" value="<?echo $arResult["REQUEST"]["PASSWORD"]?>" placeholder="<?echo GetMessage("adm_auth_pass")?>*">
    </div>
			</div>
		</div>
	<div class="form-group">
		<input class="btn btn-default" type="submit" name="Save" value="<?echo GetMessage("adm_auth_butt")?>">
	</div>

	<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
		<input type="hidden" name="RUB_ID[]" value="<?=$itemValue["ID"]?>">
	<?endforeach;?>
	<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
	<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
	<?if($_REQUEST["register"] == "YES"):?>
		<input type="hidden" name="register" value="YES" />
	<?endif;?>
	<?if($_REQUEST["authorize"]=="YES"):?>
		<input type="hidden" name="authorize" value="YES" />
	<?endif;?>
	</form>

	<?if($arResult["ALLOW_REGISTER"]=="Y"):
		?>
		<form class="col-xs-12 col-sm-6" action="<?=$arResult["FORM_ACTION"]?>" method="post">
		<div class="panel">
			<div class="panel__head">
				<h2 class="panel__name"><?echo GetMessage('adm_reg_new')?></h2>
			</div>
			<div class="panel__body">
		<?echo bitrix_sessid_post();?>
        <p>
            <?if($arResult["ALLOW_ANONYMOUS"]=="Y"):?>
                <?echo GetMessage("subscr_auth_note")?>
            <?else:?>
                <?echo GetMessage("adm_must_auth")?>
            <?endif;?>
        </p>
        <div class="form-group">
            <input class="form-control" type="text" name="NEW_LOGIN" value="<?echo $arResult["REQUEST"]["NEW_LOGIN"]?>" size="20" placeholder="<?echo GetMessage("adm_reg_login")?>*"></p>
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="NEW_PASSWORD" size="20" value="<?echo $arResult["REQUEST"]["NEW_PASSWORD"]?>" placeholder="<?echo GetMessage("adm_reg_pass")?>*">
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="CONFIRM_PASSWORD" size="20" value="<?echo $arResult["REQUEST"]["CONFIRM_PASSWORD"]?>" placeholder="<?echo GetMessage("adm_reg_pass_conf")?>*">
        </div>
        <div class="form-group">
            <input class="form-control" type="text" name="EMAIL" value="<?=$arResult["SUBSCRIPTION"]["EMAIL"]!=""?$arResult["SUBSCRIPTION"]["EMAIL"]:$arResult["REQUEST"]["EMAIL"];?>" size="30" maxlength="255" placeholder="<?echo GetMessage("subscr_email")?>*">
        </div>
		<?
        /* CAPTCHA */
        if (COption::GetOptionString("main", "captcha_registration", "N") == "Y"):
            $capCode = $GLOBALS["APPLICATION"]->CaptchaGetCode();
        ?>
            <div class="form-group clearfix">
				<input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($capCode) ?>" />
				<img class="captcha-img pull-right" src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($capCode) ?>" alt="CAPTCHA" title="<?=GetMessage("subscr_CAPTCHA_REGF_TITLE")?>">
                <div class="l-overflow">
                    <input class="form-control" type="text" name="captcha_word" size="30" maxlength="50" value="" autocomplete="off" placeholder="<?=GetMessage("subscr_CAPTCHA_REGF_PROMT")?>">
                </div>
            </div>
        <?endif;?>

		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<input type="hidden" name="RUB_ID[]" value="<?=$itemValue["ID"]?>">
		<?endforeach;?>
		<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
		<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
		<?if($_REQUEST["register"] == "YES"):?>
			<input type="hidden" name="register" value="YES" />
		<?endif;?>
		<?if($_REQUEST["authorize"]=="YES"):?>
			<input type="hidden" name="authorize" value="YES" />
		<?endif;?>
		
			</div>
		</div>

			<?php
			$sBtnSubmitText = GetMessage("adm_reg_butt");
			?>
			<?php if ($arParams['USER_CONSENT'] == 'Y'):?>
				<div class="form-group">
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.userconsent.request",
						"form",
						array(
							"ID" => $arParams["USER_CONSENT_ID"],
							"IS_CHECKED" => $arParams["USER_CONSENT_IS_CHECKED"],
							"IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
							"AUTO_SAVE" => "Y",
							// "ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
							// "ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
							"INPUT_NAME" => "FORM",
							// 'SUBMIT_EVENT_NAME' => '',
							'REPLACE' => array(
								'button_caption' => $sBtnSubmitText,
								// 'fields' => array()
							)
						)
					);?>
				</div>
			<?php endif; ?>
			
			<div class="form-group">
				<input class="btn btn-default" type="submit" name="Save" value="<?echo GetMessage("adm_reg_butt")?>">
			</div>
			
		</form>

	<?endif;?>

<?endif; //$arResult["ALLOW_ANONYMOUS"]=="Y" && $authorize<>"YES"?>
