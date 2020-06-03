// JavaScript Document

 $(document).ready(function() {

              $('.owl-carousel2').owlCarousel({

                loop: true,
                margin: 10,
        				items: 4,
        				autoplay: true,
        				slideSpeed : 300,
                responsive: {

                  0: {
                    items: 1,
                    nav: true
                  },
                  600: {
                    items: 2,
                    nav: false

                  },
                  1000: {
                    items: 4,
                    nav: true,
                    loop: true,
                    margin: 20
                  }
                }
              });

			  

			  $('.owl-carousel1').owlCarousel({
                loop: true,
                margin: 10,
				        autoplay: 1000,
				        slideSpeed : 300,                
                itemsCustom : [
                      [0, 1],
                      [445, 2],
                      [600, 4],
                      [1000, 6]
                    ],
                pagination : true,
                paginationNumbers: false


              });

        $('#owl-testimo').owlCarousel({

                loop: true,
                margin: 10,
                autoplay: true,                
                itemsCustom : [
                      [0, 1],
                      [450, 1],
                      [600, 1],
                      [700, 1],
                      [1124, 2],
                      [1200, 2],
                      [1400, 2],
                      [1600, 2]
                    ],
                    slideSpeed : 300
              });

			  

			  

			  $('.search').click(function(){

				$('.search_overlay').fadeIn('slow');   

			  });

			    $('.search_close').click(function(){

				$('.search_overlay').fadeOut('slow');   

			  });

			  



 var owl1 = $("#prod");
  owl1.owlCarousel({

      items : 3, //10 items above 1000px browser width

      itemsDesktop : [1100,3], //5 items between 1000px and 901px

      itemsDesktopSmall : [900,3], // betweem 900px and 601px

      itemsTablet: [800,2], //2 items between 600 and 0

      itemsMobile : [599,1],// itemsMobile disabled - inherit from itemsTablet option

  });

 

  // Custom Navigation Events

  $("#next1").click(function(){

    owl1.trigger('owl.next');

  })

  $("#prev1").click(function(){

    owl1.trigger('owl.prev');

  }); 
  });

  



