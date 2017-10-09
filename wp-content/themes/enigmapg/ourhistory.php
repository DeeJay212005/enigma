<?php
/*
Template Name: Our History
*/

get_header(); 
header_block($post);

$post_id = $post->ID;
$page_links = get_field( "page_links",$post_id);

#------------------------------------------------------------------------#
$history_args = array("post_type"=>'history', "posts_per_page"=>"-1", 'orderby'=>'post_date', 'order'=>'desc');
$histories = get_posts($history_args);   
if ($histories) {
   $cnt = 0;
   $hist_displ = "";
   $year_displ = "";
   
   foreach ($histories as $history) {
      $cnt++;

      $hist_id = $history->ID;

      $history_excerpt  = get_field("history_excerpt",$hist_id);
      $page_logo        = get_field("page_logo",$hist_id);
      $page_subtitle    = get_field("page_subtitle",$hist_id);
      $page_link        = get_field("page_link",$hist_id);
      //$hist_type        = get_field("hist_type",$hist_id);
      $history_date     = $history->post_date;

      $image = wp_get_attachment_image_src($page_logo, "full");
      $feat_img = $image[0];       
      
      if(($cnt % 2) == 0) {$hist_type = "right";} else {$hist_type = "";}

      $hist_displ .= "<div class='cd-timeline-block ".$hist_type."'>";
      $hist_displ .= "   <div class='timeline-year' rel='".date("Y",strtotime($history_date))."'></div>";
      $hist_displ .= "   <div class='timeline-img'>";
      $hist_displ .= "      <img src='".get_template_directory_uri()."/icons/timelinebullet.svg'>";
      $hist_displ .= "      <span class='cd-date bounce-in historyblock-date' style='".$start."'>".date("Y",strtotime($history_date))."</span>";
      $hist_displ .= "   </div>";
      $hist_displ .= "   <div class='cd-timeline-content'>";
      $hist_displ .= "      <p class='hist-type'>".strtoupper($hist_type)."</p>";
      $hist_displ .= "      <h2> ".get_the_title($hist_id)."</h2>";

      if ($page_subtitle)  {$hist_displ .= "      <p>".$page_subtitle."</p>";}
      if ($history_excerpt){$hist_displ .= "      ".$history_excerpt."";}
      if ($page_link)      {$hist_displ .= "      <a href='".$page_link."' class='cool-link find-out-links' style='".$rightlink." max-width: 114px;color:#b3996b;display:block;'>Read more</a>";
      }
      if ($feat_img)       {$hist_displ .= "      <img class='pull-left' src='".$feat_img."' style='max-width: 100px;float:left;margin-top:15px'>";}

      $hist_displ .= "   </div>";
      $hist_displ .= "</div>";


   } //end foreach
} //end if
#------------------------------------------------------------------------#
?>
<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container" style="padding-top:60px!important;background-color:#FFFFFF;">
   <div class="container container-full ">
      <?php the_content(); ?>
   </div>
</div>  
<div class="container-fluid">
   <div class="container container-full page-container-top" style="padding: 60px 15px;position:relative;">
      <div id="back-year"> 2017 </div>
      <div class="hist-head col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container" style="padding: 0">
         <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="text-align: right;right: 20px;font-weight: 600">OUR TIMELINE</div>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container all-history-container">
         <section id="cd-timeline" class="cd-container">
            <?php echo $hist_displ; ?>
         </section>
	  </div>
   </div>
</div>
<div class="container-fluid" style="margin-bottom:-50px;padding: 60px 0 0 0;background-color:#FFFFFF;">
<?php
if($page_links) {
   page_link_div($page_links);
}
?>
</div>
<script>

// code for the isElementInViewport function
function elementInViewport(el) {
   
  var top = el.offsetTop + (window.innerHeight/3);
  var left = el.offsetLeft;
  var width = el.offsetWidth;
  var height = el.offsetHeight;

  while(el.offsetParent) {
    el = el.offsetParent;
    top += el.offsetTop;
    left += el.offsetLeft;
  }

  return (
    top >= window.pageYOffset &&
    left >= window.pageXOffset &&
    (top + height) <= (window.pageYOffset + window.innerHeight) &&
    (left + width) <= (window.pageXOffset + window.innerWidth)
  );
} 

   
function callbackFunc() {   
   jQuery('.timeline-year').each(function(){
      var $this = jQuery(this);
      
      if (elementInViewport(this)) {
         jQuery('#back-year').text($this.attr('rel'));
      }
   });
}   

jQuery(function(){
  jQuery(window).scroll(function(){
     callbackFunc()
  });
});
        

</script>


<?php get_footer(); ?>