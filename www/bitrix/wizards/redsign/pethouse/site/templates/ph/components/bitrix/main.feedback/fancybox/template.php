<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

if ($request->get('backurl') && strlen($request->get('backurl')) > 0) {
	$arResult['BACKURL'] = htmlspecialchars($request->get('backurl'));
}

?>

<div class="popup styleforsmallpopup">
<?php if (strlen($arResult['OK_MESSAGE']) > 0): ?>
<script>
if (window.jQuery) {
    appSLine.closePopup({
    <?php if (strlen($request->get('backurl')) > 0): ?>
        backurl: <?=\Bitrix\Main\Web\Json::encode($arResult['BACKURL'])?>,
    <?php endif; ?>
    });
}
</script>
<?php endif; ?>
	<div class="popup_dashed"><?
		?><div id="feedback_popup" class="popup_body"><?
			$frame = $this->createFrame('feedback_popup', false)->begin('');
			if(!empty($arResult['ERROR_MESSAGE'])){
				foreach($arResult['ERROR_MESSAGE'] as $v)
					ShowError($v);
			}
			if(strlen($arResult['OK_MESSAGE']) > 0){
				ShowMessage( array('MESSAGE'=>$arResult['OK_MESSAGE'],'TYPE'=>'OK') );
			}
			?><form action="<?=POST_FORM_ACTION_URI?>" method="POST"><?
				echo bitrix_sessid_post();
				?><input type="hidden" name="PARAMS_HASH" value="<?=$arResult['PARAMS_HASH']?>"><?
				
					?><input class="textinput40" type="text" name="user_name" value="<?=$arResult['AUTHOR_NAME']?>" placeholder="<?=GetMessage('MFT_NAME')?>" /><br/><?
					?><input class="textinput40" type="text" name="user_email" value="<?=$arResult['AUTHOR_EMAIL']?>" placeholder="<?=GetMessage('MFT_EMAIL')?>" /><br/><?
					?><textarea class="textinput40" name="MESSAGE" placeholder="<?=GetMessage('MFT_MESSAGE')?>"><?=$arResult['MESSAGE']?></textarea><br/><?
					if($arParams['USE_CAPTCHA'] == 'Y'){
						?><input class="textinput40 textinput_captcha" type="text" name="captcha_word" size="30" maxlength="50" value=""><?
						?><input type="hidden" name="captcha_sid" value="<?=$arResult['capCode']?>"><?
						?><img class="captcha_image" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['capCode']?>" width="180" height="39" alt="CAPTCHA" autocomplete="off"><?
					}
				
				?><input class="btn btn-primary" type="submit" name="submit" value="<?=GetMessage('MFT_SUBMIT')?>"><?
			?></form><?
			$frame->end();
		?></div><?
	?></div><?
?></div>