var GLASS_magnify;
var $GLASS_glass;

var GLASS_uip = {
	fadeDelay		: 200,
	native_width	: 0,
	native_height	: 0,
	mouse_x			: 0,
	mouse_y			: 0,
	lupa			: ''
};

// ���������� ��������� �������
var GLASS_mouseMove = function(e)
{


	// �������� ������� �� ���� �������� ����� � ������
	var GLASS_magnify_offset = GLASS_uip.cur_img.offset();
	GLASS_uip.mouse_x = e.pageX - GLASS_magnify_offset.left;
	GLASS_uip.mouse_y = e.pageY - GLASS_magnify_offset.top;
  
	if(GLASS_uip.mouse_x < GLASS_uip.cur_img.width() && GLASS_uip.mouse_y < GLASS_uip.cur_img.height() && GLASS_uip.mouse_x > 0 && GLASS_uip.mouse_y > 0)
	{ // ���� ������� ������� ��������� ������
		GLASS_magnify(e);
	} else { // ����� ��������
		GLASS_uip.lupa.hide();
	}
	return;
};

var GLASS_magnify = function(e)
{
	var rx = Math.round(GLASS_uip.mouse_x/GLASS_uip.cur_img.width()*GLASS_uip.native_width -GLASS_uip.lupa.width()/2)*-1;
	var ry = Math.round(GLASS_uip.mouse_y/GLASS_uip.cur_img.height()*GLASS_uip.native_height - GLASS_uip.lupa.height()/2)*-1;
	var bg_pos = rx + 'px ' + ry + 'px';
	var glasspop_top  = e.pageY - GLASS_uip.cur_img.offset().top - GLASS_uip.lupa.height()/2 + GLASS_uip.cur_img.offset().top - $GLASS_glass.offset().top;
	var glasspop_left = e.pageX - GLASS_uip.cur_img.offset().left - GLASS_uip.lupa.width()/2 + GLASS_uip.cur_img.offset().left - $GLASS_glass.offset().left;

	// ������ ����������� ���������� �������� css ��������� ����
	GLASS_uip.lupa.css({
		left: glasspop_left,
		top: glasspop_top,
		backgroundPosition: bg_pos
	});
	return;
};

$(document).ready(function(){

  $('.bx-no-touch .js-glass')

    // hide/show glass when mouseleave
    .on('mouseleave', function(){
      if (GLASS_uip.lupa.length) {
        GLASS_uip.lupa.fadeOut(GLASS_uip.fadeDelay);
      }
    })

    // hide glass on click
    .on('click', '.js-glass__lupa', function(){
      GLASS_uip.cur_img.trigger('click');
    })
    .on('mousemove', '.js-glass__lupa', GLASS_mouseMove)

    // show glass on click
    .on('mouseenter', '.js-glass__canvas', function(e) {
      $GLASS_glass = $(this).closest('.js-glass');
      GLASS_uip.lupa = $GLASS_glass.find('.js-glass__lupa').addClass('active');
    })
    
    .on('mousemove', '.js-glass__canvas', function(){

      if (GLASS_uip.lupa.length == 0) {
        return;
      }
      // �������� ������� ��� �����������
      GLASS_uip.cur_img = $(this).find('img'); // ������� �����������
      GLASS_uip.lupa.fadeIn(GLASS_uip.fadeDelay); // ������� ��������� ����

      var src = $(this).attr('href'); // ���������� ���� �� ��������

      if (src) { // ���� ���������� src, ������������� ��� ��� ����
        GLASS_uip.lupa.css({
          'background-image': 'url(' + src + ')',
          'background-repeat': 'no-repeat'
        });
      }

      if (!GLASS_uip.cur_img.data('GLASS_native_width') || GLASS_uip.cur_img.data('src') != src) {
        var image_object = new Image();
        image_object.onload = function(){
          // ���������� �������� ������� ��������
          GLASS_uip.native_width = image_object.width;
          GLASS_uip.native_height = image_object.height;

          // ���������� ��� ������
          GLASS_uip.cur_img.data('GLASS_native_width', GLASS_uip.native_width);
          GLASS_uip.cur_img.data('GLASS_native_height', GLASS_uip.native_height);
          GLASS_uip.cur_img.data('src', src);

          // �������� ������� mouseMove � ���������� ����� ����
          GLASS_mouseMove.apply(this, arguments);
        };
        image_object.src = src;
        return;

      } else {
        // �������� �������� ������� �����������
        GLASS_uip.native_width = GLASS_uip.cur_img.data('GLASS_native_width');
        GLASS_uip.native_height = GLASS_uip.cur_img.data('GLASS_native_height');
      }

      // �������� ������� mouseMove � ���������� ����� ����
      GLASS_mouseMove.apply(this, arguments);
    });

});