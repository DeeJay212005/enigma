<!DOCTYPE html>
<html lang="en" class="no-js" style='margin-top:0!important;'>
<head>
  
   <!-- Meta Tags -->
   <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta http-equiv="Cache-control" content="public, max-age=169200"> 
   <meta name="format-detection" content="telephone=no">
   
   <link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet">
   <link href="<?php echo get_template_directory_uri(); ?>/fonts/fa/css/font-awesome.css" rel="stylesheet">
   <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon.png" /> 
   
   <!-- Title -->
   <?php 
    
      if(is_home() && is_front_page()) {
         $share_title = "Enigma Property Group";
         $share_link = site_url()."/";
         echo "<title>".get_bloginfo("name")."</title>";
      } else {
         $share_title = get_the_title();
         $share_link = get_the_permalink();         
         echo "<title>".get_bloginfo("name")." - ".get_the_title()."</title>";
      } 
   
      wp_head();    
   ?>
   <link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet"> 
    
   <link href="<?php echo get_template_directory_uri(); ?>/css/slick.css" rel="stylesheet" />
	<link href="<?php echo get_template_directory_uri(); ?>/css/slick-theme.css" rel="stylesheet"/>  		
	<link href="<?php echo get_template_directory_uri(); ?>/css/timeline/reset.css" rel="stylesheet"> 
	<link href="<?php echo get_template_directory_uri(); ?>/css/timeline/style.css" rel="stylesheet">  
  
   <!-- Javascript -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
   <script src="<?php echo get_template_directory_uri(); ?>/js/jquery-ui-1.10.4.custom.js"></script>   
   <script src="<?php echo get_template_directory_uri(); ?>/js/modernizr-custom.js"></script>   
   <script src="<?php echo get_template_directory_uri(); ?>/js/timeline/main.js"></script>
   <script src="<?php echo get_template_directory_uri(); ?>/js/timeline/modernizr.js"></script>
<!--   <script src="<?php echo get_template_directory_uri(); ?>/js/highcharts.js"></script>-->
   
</head>
<body <?php body_class(); ?>>  
   <!-- hidden search -->
   <div id="search" style="z-index: 999">
      <button type="button" class="close">×</button>
      <form class="header-search-form" method="get" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
         <input type="search" name="s"  placeholder="Search Enigma" onclick="this.placeholder = ''" autocomplete="off"/>
      </form>  
   </div>   
   <header id="mNavbar" class="navbar-fixed-top main-header">     
      <div class="container-fluid" style="">
         <div class="container">                       
            <nav class="navbar navbar-default" role="navigation">
               <div class="logo_div">
                  <a href="<?php echo site_url(); ?>">
                     <img class="EPGlogoL" src='<?php echo get_template_directory_uri(); ?>/img/EngimaLogoLandscape.svg' alt="Enigma Property Group" style="display: none;">
                     <img class="EPGlogoP" src='<?php echo get_template_directory_uri(); ?>/img/EngimaLogoPortrait.svg' alt="Enigma Property Group">
                  </a>
               </div>                 

               <div style='position: absolute;left: auto;right:30px;z-index: 9999;'>
                  <div class="nav-top-icons">
                     <div style="height:25px;float: left; display: block;padding:0 0 0 15px;">
                        <a href="#search" style="color: #fff;">
                           <img src='<?php echo get_template_directory_uri(); ?>/img/search.svg' style='width:auto;height:26px;'>
                        </a>
                     </div>
                  </div>
                  <div class="navbar-header nav-list">
                     <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span><i class="fa fa-bars"></i>
                     </button>
                  </div>
               </div>    

            <!--<div class="nav-block nav">-->
            <div class="nav-block navigation-space">
               <?php
                  global $menu_cnt;
                  $menu_cnt = 0;
                  wp_nav_menu( array(
                     'menu'            => 'header_menu',
                     'theme_location'  => 'header_menu',
                     'container'       => 'div',
                     'container_class' => 'collapse navbar-collapse',
                     'container_id'    => 'bs-example-navbar-collapse-1',
                     'menu_class'      => 'nav navbar-nav',
                     'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
                     'walker'          => new wp_bootstrap_navwalker())
                  );
               ?>
            </div>                            		
         </nav>
      </div>       						
   </div>
</header>
   <script>
      $(document).ready(function() {
        var $navbar = $("#mNavbar");

        AdjustHeader(); // Incase the user loads the page from halfway down (or something);
        $(window).scroll(function() {
            AdjustHeader();
        });

        function AdjustHeader(){
          if ($(window).scrollTop() > 150) {
            if (!$navbar.hasClass("navbar-fixed-top")) {
              $navbar.addClass("navbar-fixed-top");
              $('.EPGlogoP').hide();
              $('.EPGlogoL').show();
              $('.navigation-space').removeClass('navigation-space').addClass('navigation-space-scroll');
              $('.main-header').removeClass('main-header').addClass('main-header-scroll');
            }
          } else {
            $navbar.removeClass("navbar-fixed-top");
              $('.EPGlogoP').show();
              $('.EPGlogoL').hide();
              $('.navigation-space-scroll').removeClass('navigation-space-scroll').addClass('navigation-space');
              $('.main-header-scroll').removeClass('main-header-scroll').addClass('main-header');
          }
        }
      });
      
   </script>
<div class="">
<!--<div class="body-full-height">-->