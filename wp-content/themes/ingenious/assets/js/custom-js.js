(function($){
    
    
    $(document).ready(function(){
        
        
        
        
        $('.new_checkout_button').click(function(){
            
            
            // const validateEmail = (email) => {
            //   return email.match(
            //     /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            //   );
            // };


           
        //   let _email = $('input[type=email]').val();
           
        //   email(_email);
           
           //$('.wc-block-components-checkout-place-order-button').click();
            
            
        });
        
        
        function __check_product_url() {  
    $('.shop-cat-page .product img').each(function(){
        
        let __first_four  = $(this).attr('src');
        __first_four = __first_four.substr(0, 4);
        console.log(__first_four);

        //console.log(aaa);
        if(__first_four == 'data') {

            var __get_data_lazy_srcset = $(this).attr('data-lazy-src');
            $(this).fadeIn().attr('src' , __get_data_lazy_srcset);
        }
        

    });

}


 
        
        

  
           
      
      /* show second image on hover */
      
      const _addClass = () => 
            $('.woocommerce-loop-product__link').each(function(){
               if($(this).find('img').length > 1) {
                   $(this).find('img:nth-of-type(1)').addClass('offOnHover');
               }
                
            });
     
        _addClass();
      
   $( document ).ajaxComplete(function() {
        
         __check_product_url();
          _addClass();
});   
      
      
        
        // $('.pop-cat-nav li').click(function(){
        //     var cat_text = (this.innerText);
        //     var cat_text = cat_text.toLowerCase();
        //     //alert(cat_text);
            
        //     $.ajax({
        //         type: 'POST',
        //         url: admin_url(admin-ajax.php),
        //         data:{thisLinkHover_n : cat_text , action : 'get_ajax_posts_new'},
        //         success: function(data){
        //             console.log(data);
        //             //$('.popular_pr').html(result);
        //         }
                
                
                
                
        //     });
            
            
        // });
        
        
        
        
        $(window).on('load' , function(){
           $('.slider_home').css({'opacity':1});
            
            

        });
        
        
        if($(window).width() < 992){
           $('.sub-menu li:nth-of-type(1)').each(function(){
               $(this).before('<div class="inner-toggle"></div>');
               
               });
           
           
           
           $('.sub-menu').each(function(){
                var thiss = this;
                //console.log(this);
                var abb = $(this).find('.inner-toggle');
                $(abb).click(function(){
                   $(thiss).find('li').toggle(500 , function(){
                          $(this).css({'opacity':'1'});
                          });
                   $(thiss).find('li').css({'opacity':'1'});
                });
                
               console.log(abb + 'wahaj');
           });
           
           
               
           
            // $('.menu-item-has-children').each(function(){
            //   $(this + ':after').click(function(){
            //       alert('adasdadas');
            //   });
                
            // });
            
        }
        
        
        /*
        $(document).mousemove(function(){
                if($(window).width() > 992 ) {
                    
                    $('.menu li aa').on('mouseover' , function(){
                        
                    	$(this).next('.sub-menu').slideDown();
                    	$(this).next('.sub-menu').css({'opacity':'1' , 'z-index':'1'});
                    	
                    	
                    
            	
            	$('.menu li aa').mousemove(function(){
            	    	 if($(this).hover().length !== 1 &&  $(this).find('.sub-menu').hover().length !== 1 ){
                	$('.sub-menu').slideUp();
            	}
            	    
            	});
            	
            	
        	        });
        
       
        
        	    
        
        	}

        });

*/

$('#site-navigation > li').each(function(){
            
        });
        
        
        
        
        
        $(".quantity").click(function(e){
   var pWidth = $(this).innerWidth(); //use .outerWidth() if you want borders
   var pOffset = $(this).offset(); 
   var x = e.pageX - pOffset.left;
   var getVal = $('input[name=quantity]').val();
   //alert(e.pageX);
  // if(pWidth/4 > x ) {
    if((e.pageX <= 723) && (e.pageX >= 711) ) {
        console.log(getVal + 'FOR DECREAMENT');
      $('input[name=quantity]').val(--getVal);
    }
    if((e.pageX <= 776) && (e.pageX >= 763 )) {
        console.log(getVal + 'FOR INCREAMENT');
        
        $('input[name=quantity]').val(++getVal);
    }
});
        
        
       
        
        if($(window).width() < 768 ) {
            
            $('#footer h4').each(function() {
                    $(this).click(function(){
                    if($(this).next('*').css('display') == 'none') {
                    $(this).siblings().slideDown( "slow" );
                    } else {
                        $(this).siblings().slideUp( "slow" );
                    }
                    });
            });
            
            }
        
        
        
        
        
        
        
        
       $(window).on('resize' , function(){
           
            var child_length = $('.slider_home').children().length;
            var win_width = $(window).width();
            
            $('.slider-children , .slider_home').css({'width': win_width + 'px' , 'min-width' : win_width + 'px'});
                  }); 
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   $(".slider-children").each(function(i){
               $(this).attr('data-toggle' ,  i );
               
               //console.log($(this).length);
               if( i === 0 ) {
             
                   $('.car-button ul').append('<li class="button-toggle active-button" data-toggle="' + i +'"></li>');
               }
               else {
                   $('.car-button ul').append('<li class="button-toggle" data-toggle="' + i +'"></li>');
               }
               
               
               });
               
               
               
               $('.button-toggle').each(function(i){
                  $(this).click(function(){
                      if($(this).hasClass('active-button') !== true){
                          $('.button-toggle').removeClass('active-button');
                          $(this).removeClass('active-button');
                          $(this).addClass('active-button');
                      $('.slider-children').fadeOut(1000 , function(){
                          $(this).css({'display':'none'});
                          
                      });
                      
                      
                      //var aaa = $('.slider-children[data-toggle=' + i + ']');
                      //console.log($(aaa));
                      $('.slider-children[data-toggle=' + i + ']').fadeIn(2000 , function(){
                          $(this).css({'display':'flex'});
                          
                      });
                      
                      }
                      
                   
               });
               
            //setInterval(
            //function(){ 
           //$('.slider').css({'margin-left': '-' + win_width + 'px'});
           // }
          //  , 10000
          //  );
           
       });
       
       var iw = 1;
       
     
          
    
       
                    function autoslide(){
                                   setTimeout(function(){
                                       
                                     
                             
                                       $('.slider-children').css({'transition':'opacity 1s , width 1s , min-width 1s' , 'opacity':0 , 'width':0 , 'min-width':0});
                                                  
                                           
                                              
                                          //console.log($('.slider-children[data-toggle=' + iw  +']'));
                                              
                                       $('.slider-children[data-toggle=' + iw  +']').css({'transition':'opacity 2s, width 1s , min-width 1s' , 'opacity':1 , 'width': $(window).width(), 'min-width':$(window).width() + 'px'});
                                             
                                              
                                              
                                       iw++;
                                                
                                               
                                                    autoslide();
                                                      //console.log(iw);
                                                      if(iw === 3) {
                                                          iw = 0;
                                                        //autoslide();
                                                      }  
                                      
                                 
                                  }, 9000);
              
          }
         
          autoslide();
     
     /* END SLDIER*/
     
     /* START TOGGLE MENU */
     
     $('.toggle-menu').click(function(){
        if($('#primary-menu').hasClass('toggle-display')){
            $('#primary-menu').removeClass('toggle-display');
             $(this).removeClass('toggle-icon-animate');
             $('html , body').removeClass('overflowHidden');
       
        } else {
         
        $('#primary-menu').addClass('toggle-display'); 
        $(this).addClass('toggle-icon-animate');
        $('html , body').addClass('overflowHidden');
        
        }
        
     });
     
     
     
     
            
        });
    
    
     
            
            
   $(document).ready(function() {
    $(window).trigger('resize');
});


})( jQuery );