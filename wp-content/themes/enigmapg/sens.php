<?php
/*
Template Name: Sens
*/
get_header(); 
header_block($post);
$post_id = $post->ID;
#------------------------------------------------------------------------#  
$sens_args = array("post_type" => 'sens',"posts_per_page" => "-1", 'orderby'=>'post_date', 'order'=>'desc' );
$senses = get_posts($sens_args);
if($senses) {  
   $cnt = 0;
   foreach ($senses as $sens) {
      $cnt ++;
                  
      $sens_id    = $sens->ID;
      $sens_sub   = get_field("page_subtitle",$sens_id);
      $date       = get_field("date", $sens_id);
      
      $deal_displ .="<a href='".get_permalink($sens_id)."'>";
      $deal_displ .="  <div class='dealcard-block col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
      $deal_displ .="   <div class='col-xs-10 col-sm-10 col-md-10 col-lg-10 dealcard-container-content' style='padding:0'>";
      $deal_displ .="      <p style='margin: 0!important;'>SENS <strong> | ".date("d M Y", strtotime($date))."</strong></p>";
      $deal_displ .="      <h2 class=''>".get_the_title($sens_id)."</h2>";
      $deal_displ .="      <h3 class='' style='color:#000000;margin:0!important;'>".$sens_sub."</h3>";      
      $deal_displ .="      <div class='dealcard-block-divi'></div>";      
      $deal_displ .="   </div>";
      $deal_displ .="</div>";
      $deal_displ .="</a>";
         
   } //end foreach
} //end if
#------------------------------------------------------------------------#  
?>
<div class="container-fluid three-blocks-container">
   <div class="container container-full comms-outer-container">
      <?php echo $deal_displ; ?>
	</div>
</div>  

<?php
get_footer(); 
?>