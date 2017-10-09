<?php
/*
Template Name: Sitemap
*/
get_header(); 

$post_id = $post->ID;
$subtitle   = get_field("page_subtitle",$prod_id);
$page_links = get_field( "page_links",$post_id);
$accordian = get_field( "accordian", $post_id);
$show_team = get_field( "show_team", $post_id);

$team = get_field( "team", $post_id);

   $header .= "<div class='page-break-mid' style='width:100%;margin:0;'></div>";
   $header .= "<div class='single-page-header-content-padding container-fluid' style=''>";
   $header .= "   <div class='container container-full no-padding-container'>";
   $header .= "      <div class='home-about-ethos-container header-block-no-padding'>";
   $header .= "         <div class='single-page-no-feat col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container header-block-no-padding'>";
   $header .= "            <div class='slider-content-block' style='max-height: 180px;'>";
   $header .= "               <div class='top-content-block'>";
   if (function_exists('yoast_breadcrumb')) {
      $header .= yoast_breadcrumb("<p id='breadcrumbs'>","</p>",false);
   } //end if
   $header .= "                  <h1>".get_the_title($post_id)."</h1>";
   $header .= "                  <h2 class='home-about-block-sub-heading'>".$subtitle."</h2>";
   $header .= "                  <div class='block-divi'></div>";
   $header .= "               </div>";
   $header .= "            </div>";
   $header .= "         </div>";
   $header .= "      </div>";
   $header .= "   </div>";
   $header .= "</div>";  

echo $header;
?>


<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container" style="padding-top: 50px !important;">
   <div class="container container-full ">
      <?php 
         the_content(); 
      
         wp_nav_menu( array(
            'menu'            => 'sitemap_menu',
            'theme_location'  => 'sitemap_menu',
            'container'       => 'div',
            'container_id'    => 'sitemap-menu',
            'menu_class'      => 'nav navbar-nav',
            'walker'          => new wp_sitemap_navwalker())
         );
      ?>	      
      
   </div>
</div>     

<?php

if ($accordian) {
   accord_displ($accordian);
} //end if

if($page_links) {
   page_link_div($page_links);
}

if($show_team[0] == "Y") {
?>
<div class='container-fluid'>
   <div class='container'> 
      <div class='row'>     
         
         <?php
            echo team_display($team);
         ?>  

      </div>
   </div>
</div>	
<?php
} //end if team

get_footer(); 
?>