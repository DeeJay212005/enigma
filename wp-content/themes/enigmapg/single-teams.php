<?php

get_header(); 
header_block($post);

$post_id = $post->ID;

?>
<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container">
   <div class="container container-full ">
      <?php the_content(); ?>
   </div>
</div>     

<div class="container-fluid" style="">
	<div class="container container-full page-container-top">
	   <div class="">
	
         <?php	

            $meta_query = array(array('key' => 'team','value' => '"'.$post_id.'"','compare' => 'LIKE'));
            $team_args = array("post_type"=>'team_members', 'meta_query' => $meta_query, "posts_per_page"=>"-1", 'orderby'=>'post_title', 'order'=>'asc');
            $team_members = get_posts($team_args);
            if ($team_members) {
               $cnt = 0;
               foreach ($team_members as $team_member) {
                 
                  $post_id = $team_member->ID;
                  
                  if($post_id == "637") {
                     
                     $cnt++;   
                     $designation= get_field( "designation",$post_id);
                     $excerpt    = $team_member->post_excerpt;
                     if (strlen($excerpt) > 110) {$excerpt = substr($excerpt, 0, 110)." [...]";}  

                     $thumb_id   = get_field("thumbnail_image",$post_id);
                     $image      = wp_get_attachment_image_src($thumb_id, "thumbnail");
                     $feat_img   = $image[0];      

                     $rollover   = get_field("rollover",$post_id);
                     $rollimage  = wp_get_attachment_image_src($rollover, "thumbnail");
                     $roll_img   = $rollimage[0];

                     if (!$feat_img) {$feat_img = get_template_directory_uri()."/img/thumbnail.jpg";}
                     if (!$roll_img) {$roll_img = get_template_directory_uri()."/img/thumbnail.jpg";}

                     $first_displ .= "<a href='".get_permalink($post_id)."' >";
                     $first_displ .= "   <div class='single-team-block col-xs-12 col-sm-6 col-md-6 col-lg-6 team-single-container'>";
                     $first_displ .= "      <div class=''>";
                     $first_displ .= "         <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4' style='padding:0;'>";
                     $first_displ .= "            <img class='bwimage' src='".$feat_img."' style='width:100%'>";
                     $first_displ .= "            <img class='rollover' src='".$roll_img."' style='width:100%' alt='".get_the_title($post_id)."' title='".get_the_title($post_id)."'>";
                     $first_displ .= "         </div>";
                     $first_displ .= "         <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 team-single-container-content'>";
                     $first_displ .= "            <h2>".get_the_title($post_id)."</h2>";
                     $first_displ .= "            <h3>".$designation."</h3>";
                     $first_displ .= "            <div class='team-divider'></div>";
                     $first_displ .= "            <p style='margin:0!important;'>".$excerpt."</p>";
                     $first_displ .= "            <div class='cool-link find-out-links' style='margin-top: 0px;'>VIEW PROFILE</div>";
                     $first_displ .= "         </div>";
                     $first_displ .= "      </div>";
                     $first_displ .= "   </div>";
                     $first_displ .= "</a>";

                     if (($cnt % 2) == 0) {
                        $first_displ .= "<div class='col-xs-12'></div>";
                     } //end if                                   
                  } //end if
               } //end foreach   
               
               
               foreach ($team_members as $team_member) {
                 
                  $post_id = $team_member->ID;
                  
                  if($post_id != "637") {
                     
                     $cnt++;                     
                     
                     $designation= get_field( "designation",$post_id);
                     $excerpt    = $team_member->post_excerpt;
                     if (strlen($excerpt) > 110) {$excerpt = substr($excerpt, 0, 110)." [...]";}  

                     $thumb_id   = get_field("thumbnail_image",$post_id);
                     $image      = wp_get_attachment_image_src($thumb_id, "thumbnail");
                     $feat_img   = $image[0];      

                     $rollover   = get_field("rollover",$post_id);
                     $rollimage  = wp_get_attachment_image_src($rollover, "thumbnail");
                     $roll_img   = $rollimage[0];

                     if (!$feat_img) {$feat_img = get_template_directory_uri()."/img/thumbnail.jpg";}
                     if (!$roll_img) {$roll_img = get_template_directory_uri()."/img/thumbnail.jpg";}
                     

                     $team_displ .= "<a href='".get_permalink($post_id)."' >";
                     $team_displ .= "   <div class='single-team-block col-xs-12 col-sm-6 col-md-6 col-lg-6 team-single-container'>";
                     $team_displ .= "      <div class=''>";
                     $team_displ .= "         <div class='col-xs-3 col-sm-4 col-md-4 col-lg-4 team-single-photo' style='padding:0;'>";
                     $team_displ .= "            <img class='bwimage' src='".$feat_img."' style='width:100%'>";
                     $team_displ .= "            <img class='rollover' src='".$roll_img."' style='width:100%' alt='".get_the_title($post_id)."' title='".get_the_title($post_id)."'>";
                     $team_displ .= "         </div>";
                     $team_displ .= "         <div class='col-xs-12 col-sm-12 col-md-8 col-lg-8 team-single-container-content'>";
                     $team_displ .= "            <h2>".get_the_title($post_id)."</h2>";
                     $team_displ .= "            <h3>".$designation."</h3>";
                     $team_displ .= "            <div class='team-divider'></div>";
                     $team_displ .= "            <p style='margin:0!important;'>".$excerpt."</p>";
                     $team_displ .= "            <div class='cool-link find-out-links' style='margin-top: 0px;'>VIEW PROFILE</div>";
                     $team_displ .= "         </div>";
                     $team_displ .= "      </div>";
                     $team_displ .= "   </div>";
                     $team_displ .= "</a>";
                  
                     if (($cnt % 2) == 0) {
                        $team_displ .= "<div class='col-xs-12'></div>";
                     } //end if                 
                  }
               } //end foreach                  
               
            } //end if  
         
            echo $first_displ.$team_displ;
         ?>	

	   </div>
	</div>
</div>  
<?php
get_footer(); 
?>