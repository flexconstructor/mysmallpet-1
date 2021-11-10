<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Контакты");
?><!--<div style="line-height:18px;">
	<div>
		 Адрес: Москва, ул. Пушкина 19 <br>
		 Телефон: 8 (000) 000 00 00 8 (000) 000 00 00 <br>
		 E-mail: <a href="mailto:mail@mysmallpet.ru">mail@mysmallpet.ru</a> <br>
		 График работы: Время работы: Пн-Вс 9:00 - 20:00 <br>
 <br>
 <br>
		<p>
			<b>Схема проезда:</b>
		</p>
		 <?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	".default",
	Array(
		"CONTROLS" => array(0=>"ZOOM",1=>"SMALLZOOM",2=>"MINIMAP",3=>"TYPECONTROL",4=>"SCALELINE",),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:3:{s:10:\"yandex_lat\";s:7:\"55.7383\";s:10:\"yandex_lon\";s:7:\"37.5946\";s:12:\"yandex_scale\";i:10;}",
		"MAP_HEIGHT" => "500",
		"MAP_ID" => "contacts",
		"MAP_WIDTH" => "750",
		"OPTIONS" => array(0=>"ENABLE_DBLCLICK_ZOOM",1=>"ENABLE_DRAGGING",)
	)
);?>
	</div>
</div>
<br>--><?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>