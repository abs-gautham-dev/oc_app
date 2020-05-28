/**js initialization**/
$(document).ready(function(){
  $('.slider8').bxSlider({
    mode: 'vertical',
    slideWidth: 300,
    minSlides: 6,
    slideMargin: 0,
    infiniteLoop: false,
    pager: false
  });



	$('#fabric').click(function() {
				$('.item-detail').hide("slide");
				$('.inner-palet').removeClass("active");
                $(this).addClass("active");
                $('.item-detail.fabric').show("slide");
        });
	$('#style').click(function() {

				$('.item-detail').hide("slide");
				$('.inner-palet').removeClass("active");
                $(this).addClass("active");
                $('.item-detail.style').show("slide");
        });
	$('#person').click(function() {
				$('.item-detail').hide("slide");
				$('.inner-palet').removeClass("active");
                $(this).addClass("active");
                $('.item-detail.person').show("slide");
        });
	$('.close-controll').click(function() {
				$('.item-detail').hide("slide");
        });

});