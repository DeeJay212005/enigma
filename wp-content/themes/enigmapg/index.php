<?php
get_header(); 

$home = get_page_by_title( 'Home' );
$home_id = $home->ID;  
$home_title = get_field( "home_title", $home_id);
$home_subtitle = get_field( "page_subtitle", $home_id);

$text_slider_header = get_field( "text_slider_header",$text_slider_header);
$text_slider_excerpt = get_field( "text_slider_excerpt", $text_slider_excerpt);

$thumb_id = get_post_thumbnail_id($home_id);
$image = wp_get_attachment_image_src($thumb_id, "full");
$home_img = $image[0];               

$home_top_blocks = get_field( "home_top_blocks", $home_id);

?>
<div class="container col-xs-12 col-sm-12 col-md-12 col-lg-12" class="home-main-container" style="padding: 0;background-color:#34434a">
   <?php
      $slider_args = array("post_type" => 'home_slider', "posts_per_page" => "-1", 'orderby'=>'menu_order', 'order'=>'ASC' );
      $sliders = get_posts($slider_args);
      $cnt = 0;

      if($sliders) {
         $snippets .= " <section class='homeBanner slider'>";
         foreach ($sliders as $slider) {

            $slider_id = $slider->ID;         
            $slider_post = get_post($slider_id);       

            $banner_link = get_field( "banner_link", $slider_id);


            $thumb_id = get_field( "large_slider", $slider_id); 
            $image = wp_get_attachment_image_src($thumb_id, "full");
            $slider_img_lrg = $image[0];				


            $thumb_id = get_field( "medium_slider", $slider_id); 
            $image = wp_get_attachment_image_src($thumb_id, "full");
            $slider_img_med = $image[0];				


            $thumb_id = get_field( "small_slider", $slider_id); 
            $image = wp_get_attachment_image_src($thumb_id, "full");
            $slider_img_sml = $image[0];		              

            //carousel content 
            $snippet_client .= "<div class='col-lg-12' style='padding:0;'>";

            $snippet_client .= "   <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6' style='padding:0;'>";
            $snippet_client .= "   <picture>";
            $snippet_client .= "      <source src='".$slider_img_sml."' media='(max-width: 497px)'>";
            $snippet_client .= "      <source src='".$slider_img_med."' media='(max-width: 992px)'>";
            $snippet_client .= "      <img src='".$slider_img_lrg."' class='' style='width:100%'>";
            $snippet_client .= "   </picture>";
            $snippet_client .= "   </div>";
                      
            $snippet_client .= "   <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 litebg home-slider-container'>";
            $snippet_client .= "      <a class='hs-link' href='".$banner_link."'>";
            $snippet_client .= "         <div class='hs-content'>";
            $snippet_client .= "            <div class='pagingInfo'></div>";
            $snippet_client .= "             <h1 class='hs-heading'>".$slider_post->post_title."</h1>";
            $snippet_client .= "                <div class='hs-sub-heading'>".$slider_post->post_title."</div>";
            $snippet_client .= "                <div class='hs-divider'></div>";
            $snippet_client .= "                <p class='hs-excerpt'>".$slider_post->post_content."</p>";
            $snippet_client .= "            <div class='hs-link-explore'>EXPLORE </div> <div class='hs-link-line'></div> <div class='hs-link-project  hover_effect'> View Project</div>";
            
//            $snippet_client .= "            <div class='hs-link-text'><img src='".get_template_directory_uri()."/img/EVPW.svg' style='width:160px;'></div>";
            
            $snippet_client .= "         </div>";
            $snippet_client .= "      </a>";
            $snippet_client .= "   </div>";

            $snippet_client .= "</div>\n"; 
         } //end foreach
         
         $snippets .= $snippet_client;
         $snippets .= "</section>\n";						

      } //end if                     
      echo $snippets;
	?>   

</div>

<!-- BLANK SPACE -->
<div class="container-fluid" style="background-color: #ffffff;">
   <div class="container">
	   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
         <div class="row">                 
	         <div style="height: 50px"> </div>	         
         </div> 
	   </div>
   </div>
</div>

<!-- MAIN CONTENT -->
<div class="container-fluid" style="background-color: #ffffff;">
   <div class="container">                 
      <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
         <h1><?php echo $home_title?></h1>
         <div class='h1-subheading'><?php echo $home_subtitle?></div>
         <div class='home-header-content-divider'></div>
         <div class='page-content'><?php echo $home->post_content ?></div>
      </div>
   </div>
</div>
  
<!-- BLANK SPACE -->
<div class="container-fluid" style="background-color: #ffffff;">
   <div class="container">
	   <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
         <div class="row">                 
	         <div style="height: 50px"> </div>	         
         </div> 
	   </div>
   </div>
</div>

<!-- TEXT SLIDER -->
<div class="container-fluid">
   <div class="container">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">            
         <?php   
           $text_slider_excerpt    = get_field( "text_slider_excerpt", $home_id);
           $text_slider_header     = get_field( "text_slider_header", $home_id);

           $textslider .= "   <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3' style='padding:0;'>".$text_slider_header."</div>";
           $textslider .= "   <div class='col-xs-12 col-sm-12 col-md-8 col-lg-9' style='padding:0;'>".$text_slider_excerpt."</div>";

           echo $textslider;
         ?>          
      </div>
   </div>
</div>  

<?php get_footer(); ?>