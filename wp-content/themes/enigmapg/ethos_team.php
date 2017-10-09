<?php
/*
Template Name: Ethos Team
*/

get_header(); 
header_block($post);

$post_id = $post->ID;
$term = get_field( "team",$post_id);

?>
  
<!-- MAIN CONTENT -->
<div class="container-fluid" style="margin-top: 50px">
	<div class="container container-full page-container-top">
	
      <?php	
         
         $team_member_array = get_team_by_fund($term->slug);
         $team_args = array("post_type"=>'team_members', 'ethos_team'=>$term->slug, "posts_per_page"=>"-1", 'orderby'=>'post_title', 'order'=>'asc');
         $team_members = get_posts($team_args);
         if ($team_members) {
            $cnt = 0;
            foreach ($team_members as $team_member) {
               $cnt++;
               $post_id = $team_member->ID;

               $designation= get_field( "designation",$post_id);
               $excerpt    = $team_member->post_excerpt;

               $rollover   = get_field("rollover",$post_id);
               $rollimage  = wp_get_attachment_image_src($rollover, "thumbnail");
               $roll_img   = $rollimage[0];

               if (!$roll_img) {$roll_img = get_template_directory_uri()."/img/thumbnail.jpg";}

               echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 no-padding-container' style='margin-bottom: 30px'>";
               echo "   <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4 footer-logo-space-mobile'>";
               echo "      <a href='".get_permalink($post_id)."'>";
               echo "         <img class='rollover' src='".$roll_img."' style='width:100%' alt='".get_the_title($post_id)."' title='".get_the_title($post_id)."'>";
               echo "      </a>";
               echo "   </div>";
               echo "   <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 no-padding-container mobile-footer-logo'>";
               echo "      <p style='font-weight: 600; font-size: 16px; letter-spacing: 1px;color: #000000;margin:0;'>".get_the_title($post_id)."</p>";
               echo "      <p style='font-size: 12px;'>".$designation."</p>";
               echo "      <div class='team-divider'></div>";
               echo "      <p>".$excerpt."</p>";
               echo "      <a href='".get_permalink($post_id)."'><div class='cool-link find-out-links' style='margin-top: 8px;'>VIEW PROFILE</div></a>";
               echo "   </div>";
               echo "</div>";

               if (($cnt % 2) == 0) {
                  echo "<div class='col-xs-12'></div>";
               } //end if                 
               
            } //end foreach   
         } //end if   
      ?>	

	</div>
</div>  

<?php get_footer(); ?>