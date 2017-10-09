</div>        
   <footer>

     <div class="container-fluid" style="background-color: #000000;padding: 0;">
        <div class="home-block-divi"></div>   
     </div>	    
     <div class="container-fluid footer-legal-container">
        <div class="container no-padding-container" style="padding: 0">
           <div class="no-padding-container2">
               
             <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 footer-advisor-s" style="">
               <div class="">
                <a href="https://thoughtcapitaltest.co.za/ethos">            
                 <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 footer-logo-space" style="padding-right: 0;">
                    <div class="footer-logo-space-mobile">
                    <img src='<?php echo get_template_directory_uri(); ?>/img/EthosLogo.svg' alt="Ethos" style="width:100%;max-width:100px;float:left;">
                    </div>
                    <div class="col-xs-7 col-sm-8 col-md-8 col-lg-8 no-padding-container mobile-footer-logo" style="padding-right: 0;">
                    <p style="font-weight: 600!important; font-size: 18px!important;color:#ffffff">THE ADVISOR</p>
                    <div class="footer-divider-l"></div>
                    <p class="footer-logo-slogan">ETHOS</p>
                    </div>
                 </div>
               </a>
              </div>
             </div>                
               
              <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8" style="">   
                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 footer-legal-links">
                   <?php wp_nav_menu( array( 'theme_location' => 'legal_menu' ) ); ?>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer-legal-content">
                   <div class="">
                       <p>Ethos Capital Partners Limited is licensed as an authorised financial services provider under the jurisdiction of the Financial Services Board of South Africa in terms of the Financial Advisory and Intermediary Services Act, 2002 (Act No. 37 of 2002). License number 9254. Share code EPE</p>
                    </div>                    
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer-legal-copy">
                   <div class="">
                      <p>Copyright © 2017 Ethos Capital</p>
                   </div>                     
                </div>
              </div>
             <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 footer-advisor-l" style="">
               <div class="">
                <a href="https://thoughtcapitaltest.co.za/ethos">            
                 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 footer-logo-space" style="padding-right: 0;">
                    <div class="footer-logo-space-mobile">
                    <img src='<?php echo get_template_directory_uri(); ?>/img/EthosLogo.svg' alt="Ethos" style="width:100%;max-width:100px;float:left;">
                    </div>
                    <div class="col-xs-7 col-sm-8 col-md-8 col-lg-8 no-padding-container mobile-footer-logo" style="padding-right: 0;">
                    <p style="font-weight: 600!important; font-size: 18px!important;color:#ffffff">THE ADVISOR</p>
                    <div class="footer-divider-l"></div>
                    <p class="footer-logo-slogan">ETHOS</p>
                    </div>
                 </div>
               </a>
              </div>
             </div> 
           </div>
        </div>      
     </div>			
   </footer>   
   <?php wp_footer(); ?>
   <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap.js"></script>
   <script src="<?php echo get_template_directory_uri(); ?>/js/slick.min.js"></script>
   <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.touchSwipe.js" async></script>   
   <script>
      jQuery(document).ready(function(){
         
         /*******************
         SELECT DROPDOWNS
         *******************/
         jQuery('select').each(function(){
            var $this = jQuery(this), numberOfOptions = jQuery(this).children('option').length;

            $this.addClass('select-hidden'); 
            $this.wrap('<div class="select"></div>');
            $this.after('<div class="select-styled"></div>');

            var $styledSelect = $this.next('div.select-styled');
            $styledSelect.text($this.children('option').eq(0).text());

            var $list = jQuery('<ul />', {
              'class': 'select-options'
            }).insertAfter($styledSelect);

            for (var i = 0; i < numberOfOptions; i++) {
              jQuery('<li />', {
                  text: $this.children('option').eq(i).text(),
                  rel: $this.children('option').eq(i).val()
              }).appendTo($list);

              if ($this.children('option').eq(i).attr('selected') == "selected") {
                 $styledSelect.text($this.children('option').eq(i).text());
              } //end if

            }

            var $listItems = $list.children('li');

            $styledSelect.click(function(e) {
              e.stopPropagation();
              jQuery('div.select-styled.active').not(this).each(function(){
                  jQuery(this).removeClass('active').next('ul.select-options').hide();
              });
              jQuery(this).toggleClass('active').next('ul.select-options').toggle();
            });

            $listItems.click(function(e) {
               e.stopPropagation();
               $styledSelect.text(jQuery(this).text()).removeClass('active');
               $this.val(jQuery(this).attr('rel'));
               $list.hide();
               jQuery('select').not($this).val("");
               //jQuery('select').not($this).next("div.select-styled").text("");

               jQuery(this).closest('form').submit();

              //console.log($this.val());
            });

            jQuery(document).click(function() {
              $styledSelect.removeClass('active');
              $list.hide();
            });

         });          


         /*******************
         TIMELINE BLOCKS
         *******************/

         var timelineBlocks = jQuery('.cd-timeline-block'),
            offset = 0.8;

         //hide timeline blocks which are outside the viewport
         hideBlocks(timelineBlocks, offset);

         //on scolling, show/animate timeline blocks when enter the viewport
         jQuery(window).on('scroll', function(){
            (!window.requestAnimationFrame) 
               ? setTimeout(function(){ showBlocks(timelineBlocks, offset); }, 100)
               : window.requestAnimationFrame(function(){ showBlocks(timelineBlocks, offset); });
         });

         function hideBlocks(blocks, offset) {
            blocks.each(function(){
               ( jQuery(this).offset().top > jQuery(window).scrollTop()+jQuery(window).height()*offset ) && jQuery(this).find('.timeline-img, .cd-timeline-content').addClass('is-hidden');
            });
         }

         function showBlocks(blocks, offset) {
            blocks.each(function(){
               ( jQuery(this).offset().top <= jQuery(window).scrollTop()+jQuery(window).height()*offset && jQuery(this).find('.timeline-img').hasClass('is-hidden') ) && jQuery(this).find('.timeline-img, .cd-timeline-content').removeClass('is-hidden').addClass('bounce-in');
            });
         }         

         /*******************
         ACCORDIANS
         *******************/
         setTimeout(function() {
            jQuery("button.accordion:first").addClass("active");
            var panel = jQuery("button.accordion").nextAll('div.panel:first');
            panel.css("max-height",(panel.prop('scrollHeight') + 200));
            panel.css("padding-bottom", 50);
         }, 800);


         jQuery("button.accordion").click(function(e){
            var panel = jQuery(this).nextAll('div.panel:first');
            if (jQuery(this).toggleClass('active').hasClass("active")) {
               panel.css("max-height",(panel.prop('scrollHeight') + 200));
               panel.css("padding-bottom", 50);
            } else {
               panel.css("padding-bottom", 0);
               panel.css("max-height",0);
            }
         });


         /*******************
         SHARE BUTTONS
         *******************/         
         jQuery(".bt-share.js-social").click(function(e){
            if(jQuery('.list-share-circles ul').hasClass("active")){
               jQuery('.list-share-circles ul').removeClass("active");
            }else{
               jQuery('.list-share-circles ul').addClass("active");
            }
            return false;
         });


         /*******************
         FILTER BUTTONS
         *******************/           
         jQuery("#filterbutton, #filterby").click(function(){
            if(jQuery('#filterby').hasClass("in")){
               jQuery('#filterby').removeClass("in");
               jQuery('#filterby').hide();
            }else{
               jQuery('#filterby').addClass("in");
               jQuery('#filterby').show();
            }
            return false;
         });         


         /*******************
         NAVIGATION BUTTONS
         *******************/  
         jQuery(function() {
            jQuery('.nav-toggle').on("click", function() {
               jQuery('.main-navigation').toggleClass('open');
            });
         });    


         jQuery(".menu-item-has-children .caret").click(function(e){
            e.preventDefault();
            //alerty('hello');
            if(jQuery(this).hasClass("toggle")){
               jQuery(this).parent().parent().find("ul.sub").first().removeClass("toggle");
               //if(jQuery(this).hasClass("submenu")){alert('hello');}
               jQuery(this).removeClass("toggle");
            }else{
               jQuery(this).parent().parent().find("ul.sub").first().addClass("toggle");
               jQuery(this).addClass("toggle");
            }
            return false;
         });            


         /*******************
         SEARCH BUTTONS
         *******************/           
         jQuery(function () {
            jQuery('a[href="#search"]').on('click', function(event) {
               event.preventDefault();
               jQuery('#search').addClass('open');
               jQuery('#search > form > input[type="search"]').focus();
            });

            jQuery('#search, #search button.close').on('click keyup', function(event) {
               if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
                  jQuery(this).removeClass('open');
               }
            });

         });


         /*******************
         MENU OERLAY
         *******************/           
         jQuery(function () {
            jQuery('#front-menu a[href="#over-nav"]').on('click', function(event) {

               event.preventDefault();

               jQuery('#over-nav').addClass('open');
               jQuery('.ubermenu li.ubermenu-tab').not('#'+ jQuery(this).attr('id')).removeClass("ubermenu-active");
               jQuery('.ubermenu #'+ jQuery(this).attr('id')).addClass('ubermenu-active');

            });

            jQuery('#over-nav, #over-nav button.close').on('click keyup', function(event) {
               if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
                  jQuery(this).removeClass('open');
               }
            });
         }); 
        
         /*******************
         SLIDERS BUTTONS
         *******************/                
         jQuery('.homeBanner').slick({
            autoplay: true,
            dots: false,
            arrows: false,
            adaptiveHeight: true,
            fade: true,
            autoplaySpeed: 5000,         
            speed: 1200,       
            pauseOnHover: true,  
            rtl: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            swipeToSlide:true,
            customPaging : function(slider, i) {
            var thumb = $(slider.$slides[i]).data();
            return '<a>'+ i +'</a>';
            },
            responsive: [{ 
                breakpoint: 500,
                settings: {
                dots: false,
                arrows: false,
                infinite: false,
                slidesToShow: 1,
                slidesToScroll: 1,
              } 
            }]         
         });
         
         //custom function showing current slide
         var $status = $('.pagingInfo');
         var $slickElement = $('.homeBanner');

         $slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
             //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
             var i = (currentSlide ? currentSlide : 0) + 1;
             $status.html('<div class="slide-number">' + i + '</div>' + '<div class="slide-of">' + '</div>' + '<div class="slide-numbers">' + slick.slideCount + '</div>');
         });
         
//         jQuery('.homeBanner').slick({
//            dots: true,
//            arrows:true,
//            autoplay: true,
//            adaptiveHeight: true,
//            fade: true,
//            autoplaySpeed: 5000,         
//            speed: 1200,       
//            pauseOnHover: true,  
//            rtl: false,
//            infinite: true,
//            slidesToShow: 1,
//            slidesToScroll: 1,
//            swipeToSlide:true,
//            nextArrow: jQuery('.homeBanner .home-white-dot'),
//            prevArrow: '',
//            responsive: [
//             {
//               breakpoint: 590,
//               settings: {
//                 arrows: false,
//               }
//             },
//             {
//               breakpoint: 480,
//               settings: {
//                 arrows: false,
//               }
//             }
//            ]         
//         });        
         
         jQuery('.homeNews').slick({
            dots: false,
            arrows:true,
            autoplay: true,
            adaptiveHeight: true,
            fade: true,
            autoplaySpeed: 5000,         
            speed: 500,       
            pauseOnHover: true,  
            rtl: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            swipeToSlide:true,
            nextArrow: jQuery('.white-dot-bottom'),
            prevArrow: '',
            responsive: [
             {
               breakpoint: 590,
               settings: {
                 arrows: false,
               }
             },
             {
               breakpoint: 480,
               settings: {
                 arrows: false,
               }
             }
            ]         
         }); 
         
         jQuery('.twitterFooter').slick({
            dots: false,
            arrows:true,
            autoplay: true,
            adaptiveHeight: true,
            fade: true,
            autoplaySpeed: 5000,         
            speed: 500,       
            pauseOnHover: true,  
            rtl: false,
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            swipeToSlide:true,
            nextArrow: jQuery('.twitter-arrow-l'),
            prevArrow: jQuery('.twitter-arrow-r'),
            responsive: [
             {
               breakpoint: 590,
               settings: {
                 arrows: false,
               }
             },
             {
               breakpoint: 480,
               settings: {
                 arrows: false,
               }
             }
            ]         
         });          
      });
      
	/*** responsive table ***/
   var tables = document.querySelectorAll("table");
   for (index = 0; index < tables.length; ++index) {

      var headertext = [],
      headers 		= tables[index].querySelectorAll("th"),
      tablebody 	= tables[index].querySelector("tbody");

      for(var i = 0; i < headers.length; i++) {
         var current = headers[i];
         headertext.push(current.textContent.replace(/\r?\n|\r/,""));
      } //end for

      //console.log(tablebody);
      if(tablebody) {
         for (var i = 0, row; row = tablebody.rows[i]; i++) {
            for (var j = 0, col; col = row.cells[j]; j++) {
               col.setAttribute("data-th", headertext[j]);
            } //end for 
         } //end for		
      } //end for	

   } //end for	
   </script>
   
<!--   <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/sanctuary_footer.js"  crossorigin="anonymous"></script>-->
   </body>
</html>
