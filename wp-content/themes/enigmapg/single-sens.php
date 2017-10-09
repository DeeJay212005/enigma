<?php

get_header(); 
header_block($post);

$post_id = $post->ID;
#------------------------------------------------------------------------#  
//get first and last post
$first_args = array("post_type" => 'sens', 'exclude'=>$post_id, "posts_per_page" => "1", 'orderby'=>'post_date', 'order'=>'ASC');
$first_post = get_posts($first_args)[0];

$last_args = array("post_type" => 'sens', 'exclude'=>$post_id, "posts_per_page" => "1", 'orderby'=>'post_date', 'order'=>'DESC');
$last_post = get_posts($last_args)[0];
#------------------------------------------------------------------------#   
//get prev and next post
$prev_args = array("post_type"=>'sens', 'exclude'=>$post_id, "posts_per_page"=>"1", 'date_query'=>array('before'=>$post->post_date), 'orderby'=>'post_date', 'order'=>'DESC');
$prev_post = get_posts($prev_args)[0];

$next_args = array("post_type"=>'sens', 'exclude'=>$post_id, "posts_per_page"=>"1", 'date_query'=>array('after'=>$post->post_date), 'orderby'=>'post_date', 'order'=>'ASC');
$next_post = get_posts($next_args)[0];
#------------------------------------------------------------------------#   
$next_id = $next_post->ID;            
if (!$next_id) {$next_id = $first_post->ID;} //end if

$prev_id = $prev_post->ID;
if (!$prev_id) {$prev_id = $last_post->ID;} //end if
#------------------------------------------------------------------------#   


$accordian  = get_field( "accordian", $post_id);
$place      = get_field("place", $post_id);
$date       = get_field("date", $post_id);
$sponsor    = get_field("sponsor", $post_id);
$disclaimer = get_field("disclaimer", $post_id);
?>
   
<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container">
   <div class="container container-full the-content-container">
      <?php the_content(); ?>
   </div>
</div>     

<?php
if ($accordian) {
   accord_displ($accordian);
} //end if
?>
             
<div class="container-fluid three-blocks-container">
   <div class="container container-full the-content-container">
      <div class='col-xs-12' style='padding:0;'>  
      <?php 
         if ($place) {
            echo "<p><b>".$place."</b></p>";
         }
         if ($date) {
            echo "<p style='margin:0!important;'>DATE:</p>";
            echo "<p><b>".$date."</b></p>";
         }
         if ($sponsor) {
            echo "<p style='margin:0!important;'>SPONSOR:</p>";
            echo "<p><b>".$sponsor."</b></p>";
         }
         if ($disclaimer) {
            echo "<p style='margin:0!important;'>".$disclaimer."</p>";
         }
      
      ?>
      </div>
   </div>
</div>                   


<div class="container-fluid" style="margin-top:20px 0 20px 0">	
	<div class="container">
      <?php
         //next post ID, previous post ID, Post Type
         echo next_prev_display($next_id, $prev_id);
      ?>  
   </div>      
</div>

<?php
get_footer(); 
?>