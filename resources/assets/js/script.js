$(document).ready(function(){
$('.slider').flexslider({
    directionNav: false,
    controlNav: true
});
$('.main_menu').click(function(){
$('.main_menu ul').slideToggle();
});


 
  
  
   /* --------------------phone cover detail page slider ----------------------------- */
    $('.cover_slider').bxSlider({
        /*auto: false,
		infiniteLoop: false,
        speed: 500,
        pager: true,
        pagerCustom: '.bx-thumb-pager'*/
        
        auto:false,
		speed: 500,
		infiniteLoop: false,
		pager:true,
		pagerCustom: '.bx-thumb-pager',
		useCSS: false


    });


    $('.detail_thumb_slide').bxSlider({
        /*auto: false,
        speed: 500,
        pager: false,
        slideWidth: 110,
        minSlides: 1,
        maxSlides: 4,
        moveSlides: 1,
        slideMargin: 20,
        nextSelector: '#next_slide',
        prevSelector: '#prev_slide'*/
        
        
        auto:false,
		speed: 500,
		infiniteLoop: false,
		pager:false,
		slideWidth: 110,
		minSlides: 1,
		maxSlides: 4,
		moveSlides: 1,
		slideMargin: 20,
		nextSelector: '#next_slide',
		prevSelector: '#prev_slide'

    });

	
					$('.cover_slide3').bxSlider({
					auto: false,
					pager: false,
					infiniteLoop: false,
					nextSelector: '#next6',
					prevSelector: '#prev6',
					slideWidth: 205,
					minSlides: 2,
					maxSlides: 5,
					moveSlides: 5,
					slideMargin: 0
					});
					
$(".cover_thumb li").hover(function () {
$(".smallicon", this).stop(true,false).animate({top:"0px",opacity:"1"});}, function () {
$(".smallicon", this).stop(true,false).animate({top:"0px",opacity:"0"});

});

 
 
  

});