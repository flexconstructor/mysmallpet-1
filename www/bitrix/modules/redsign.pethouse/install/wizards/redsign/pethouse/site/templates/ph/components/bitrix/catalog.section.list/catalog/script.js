$(document).ready(function(){
	$('.section_list .lvl1').on('mouseenter',function(){
		$(this).find('.lvl1_submenu').removeClass('none2')
	}).on('mouseleave',function(){
		$(this).find('.lvl1_submenu').addClass('none2')
	});
});