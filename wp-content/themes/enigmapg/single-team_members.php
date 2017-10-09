<?php

get_header(); 

$post_id = $post->ID;
#------------------------------------------------------------------------#  
//get first and last post
$first_args = array("post_type" => 'team_members', 'exclude'=>$post_id, "posts_per_page" => "1", 'orderby'=>'post_title', 'order'=>'ASC');
$first_post = get_posts($first_args)[0];

$last_args = array("post_type" => 'team_members', 'exclude'=>$post_id, "posts_per_page" => "1", 'orderby'=>'post_title', 'order'=>'DESC');
$last_post = get_posts($last_args)[0];
#------------------------------------------------------------------------#   
//get prev and next post

global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_title < '".$post->post_title."' AND post_type = 'team_members' AND post_status='publish' ORDER BY post_title DESC LIMIT 1", OBJECT );
$next_post = $results[0];

$results = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_title > '".$post->post_title."' AND post_type = 'team_members' AND post_status='publish' ORDER BY post_title ASC LIMIT 1", OBJECT );
$prev_post = $results[0];

#------------------------------------------------------------------------#   
$next_id = $next_post->ID;            
if (!$next_id) {$next_id = $first_post->ID;} //end if

$prev_id = $prev_post->ID;
if (!$prev_id) {$prev_id = $last_post->ID;} //end if
#------------------------------------------------------------------------# 


$companies = get_field( "companies", $post_id);
$curr_funds = array();
$past_funds = array();
foreach ($companies as $company) {
   
   $comp_id = $company['company'][0]->ID;
   
   $current_fund = get_field( "current_fund", $comp_id);
   $past_fund = get_field( "past_fund",$comp_id);
   
   if($current_fund) {$curr_funds[] = $comp_id;}
   if($past_fund)    {$past_funds[] = $comp_id;}

} //end foreach

$designation = get_field("designation",$post_id);
$joined      = get_field("joined",$post_id);


$responsibilities = "";
$portfolios  = get_field("investment_portfolios",$prod_id);
foreach ($portfolios as $portfolio) {
   $responsibilities .= "<br>".get_the_title($portfolio['portfolio']);
}

$thumb_id = get_post_thumbnail_id($post_id);
$image = wp_get_attachment_image_src($thumb_id, "full");
$rollover    = get_field("rollover",$post_id);
$rollover = wp_get_attachment_image_src($rollover, "full");

$feat_img = $image[0];
$thumb_img = $thumb_image[0];
if (!$feat_img) {$feat_img = get_template_directory_uri()."/img/default_feat.jpg";}

$rollover = $rollover[0];
if (!$rollover) {$rollover = get_template_directory_uri()."/img/default_feat.jpg";}

$header .= "<div class='page-break-mid' style='width:100%;margin:0;'></div>";
$header .= "   <div class='container-fluid' style='background-color: #000000;padding-top: 50px;'>";
$header .= "      <div class='container'>";
$header .= "         <div class=''>";
$header .= "            <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container' style=''>";
$header .= "               <div class='col-xs-12 col-sm-12 col-md-7 col-lg-8 no-padding-container' style='padding:0;'>";
$header .= "                  <div class='' style='height:auto;'>";
$header .= "                     <div class='top-content-block'>";
if (function_exists('yoast_breadcrumb')) {
   $header .= yoast_breadcrumb("<p id='breadcrumbs'>","</p>",false);
} //end if
$header .= "                        <h1>".get_the_title($post_id)."</h1>";
$header .= "                        <h2 class='home-about-block-sub-heading'>".$designation."</h2>";
$header .= "                        <div class='single-team-block-divi'></div>";
$header .= "                        <div class='col-xs-12' style='padding:0'>";
$header .= "                           <div class='row'>";
if($rollover) {      
   $header .= "       <div class='col-xs-2 col-sm-3 col-md-3 col-lg-2 single-dealcard-s-image'>";
   $header .= "          <img class='single-rollover-imgs' src='".$rollover."' style='background-color:#fff;'>";
   $header .= "       </div>";
   $header .= "       <div class='col-xs-12 col-sm-12 col-md-9 col-lg-10'>";
} else {
   $header .= "       <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
}
if($joined) {
$header .= "                        <p style='color:#fff'>Joined Ethos: ".$joined."</p>";
}
if($responsibilities) {
$header .= "                        <p style='color:#fff'>Portfolio Responsibilities: ".$responsibilities."</p>";  
}
$header .= "      </div>";
$header .= "   </div>";

$header .= "                        </div>";
$header .= "                     </div>";
$header .= "                  </div>";
$header .= "               </div>";
$header .= "            <div class='col-sm-6 col-md-4 ethos-right-header-image-singleteam pull-right' style='margin-bottom:-320px;margin-top:0px;padding-right:0;'>";
$header .= "               <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 header-image' style='background-color:transparent !important;padding:0;height:auto;position:relative;'>";

$header .= "                     <img class='single-dealcard-l-image' srcset='".$feat_img."' style='width:100%;'>";
//$header .= "                     <img class='single-dealcard-s-image' srcset='".$rollover."' style='width:100%;'>";


$header .= "               </div>";
$header .= "            </div>";
$header .= "         </div>";
$header .= "      </div>";
$header .= "   </div>";
$header .= "</div>";

echo $header;

?>

<!-- MAIN CONTENT -->
<div class="container-fluid page-content-top-margin-singleteam">
   <div class="container container-full ">
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-8 no-padding-container single-team-content-block' style=""> 
         <?php the_content(); ?>
      </div>
   </div>
</div>      

<?php


echo "<div class='container-fluid page-link-margin' style=''>";
echo "   <div class='container'>";

//DISPLAY CURRENT INVESTMENTS
if (count($curr_funds) > 0) {
   $cnt = 0;
   
   echo "      <button class='accordion' style='font-weight: 600;'>CURRENT INVESTMENTS</button>";
   echo "      <div class='panel'>"; 

   foreach ($curr_funds as $comp_id) {
      $cnt++;
      $current_fund = get_field( "current_fund", $comp_id);

      $company_logo = get_field( "company_logo", $comp_id);
      $image = wp_get_attachment_image_src($company_logo, "full");
      $comp_logo = $image[0];
      
      echo "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 no-padding-container' style='margin: 15px 0;padding: 0;'>";
      echo "   <div class=''>";
      echo "      <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>";
      echo "         <a href='".get_permalink($comp_id)."'>";
      echo "            <img class='rollover' src='".$comp_logo."' style='width:100%' alt='".get_the_title($comp_id)."' title='".get_the_title($comp_id)."'>";
      echo "         </a>";
      echo "      </div>";
      echo "      <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 no-padding-container mobile-footer-logo'>";
      echo "         <p style='font-weight: 600; font-size: 16px; letter-spacing: 1px;color: #000000;margin:0;'>".get_the_title($comp_id)."</p>";
      echo "         <p style='font-size: 12px;'>".get_the_title($current_fund)."</p>";
      echo "         <div class='team-divider'></div>";
      echo "         <a href='".get_permalink($comp_id)."'><div class='cool-link find-out-links' style='margin-top: 0px;padding:0;'>VIEW PORTFOLIO</div></a>";
      echo "      </div>";
      echo "   </div>";
      echo "</div>";

      if (($cnt % 3) == 0) {
         echo "<div class='col-xs-12'></div>";
      } //end if          


   } //end foreach

   echo "      </div>"; 

} //end if


//DISPLAY PAST INVESTMENTS
if (count($past_funds) > 0) {
   $cnt = 0;
   
   echo "      <button class='accordion' style='font-weight: 600;'>PAST INVESTMENTS</button>";
   echo "      <div class='panel'>"; 

   foreach ($past_funds as $comp_id) {
      $cnt++;
      $current_fund = get_field( "current_fund", $comp_id);

      $company_logo = get_field( "company_logo", $comp_id);
      $image = wp_get_attachment_image_src($company_logo, "full");
      $comp_logo = $image[0];
      
      echo "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 no-padding-container' style='margin: 15px 0;padding: 0;'>";
      echo "   <div class=''>";
      echo "      <div class='col-xs-4 col-sm-4 col-md-4 col-lg-4'>";
      echo "         <a href='".get_permalink($comp_id)."'>";
      echo "            <img class='rollover' src='".$comp_logo."' style='width:100%' alt='".get_the_title($comp_id)."' title='".get_the_title($comp_id)."'>";
      echo "         </a>";
      echo "      </div>";
      echo "      <div class='col-xs-8 col-sm-8 col-md-8 col-lg-8 no-padding-container mobile-footer-logo'>";
      echo "         <p style='font-weight: 600; font-size: 16px; letter-spacing: 1px;color: #000000;margin:0;'>".get_the_title($comp_id)."</p>";
      echo "         <p style='font-size: 12px;'>".get_the_title($current_fund)."</p>";
      echo "         <div class='team-divider'></div>";
      echo "         <a href='".get_permalink($comp_id)."'><div class='cool-link find-out-links' style='margin-top: 0px;padding:0;'>VIEW PORTFOLIO</div></a>";
      echo "      </div>";
      echo "   </div>";
      echo "</div>";

      if (($cnt % 3) == 0) {
         echo "<div class='col-xs-12'></div>";
      } //end if          


   } //end foreach

   echo "      </div>"; 

} //end if
 
echo "   </div>";
echo "</div>"; 

?>

<div class="single-team-member-n-p container-fluid" style="margin-top:20px 0 20px 0">	
	<div class="container">
      <?php

      
         $np_dspl .="<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='padding:0;'>";
         $np_dspl .="   <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6' style='padding:0;'>";

         if ($next_id) {

            $designation= get_field( "designation",$next_id);

            $thumb_id   = get_field("thumbnail_image",$next_id);
            $image      = wp_get_attachment_image_src($thumb_id, "thumbnail");
            $feat_img   = $image[0];      

            $rollover   = get_field("rollover",$next_id);
            $rollimage  = wp_get_attachment_image_src($rollover, "thumbnail");
            $roll_img   = $rollimage[0];

            if (!$feat_img) {$feat_img = get_template_directory_uri()."/img/thumbnail.jpg";}
            if (!$roll_img) {$roll_img = get_template_directory_uri()."/img/thumbnail.jpg";}


            $np_dspl .= "<a href='".get_permalink($next_id)."' >";
            $np_dspl .= "   <div class='single-team-block col-xs-12 col-sm-12 col-md-12 col-lg-12 team-single-container'>";
            $np_dspl .= "      <div class=''>";
            $np_dspl .= "         <div class='single-prof-next-img col-xs-4 col-sm-4 col-md-4 col-lg-3' style='padding:0;'>";
            $np_dspl .= "            <img class='bwimage' src='".$feat_img."' style='width:100%'>";
            $np_dspl .= "            <img class='rollover' src='".$roll_img."' style='width:100%' alt='".get_the_title($next_id)."' title='".get_the_title($next_id)."'>";
            $np_dspl .= "         </div>";
            $np_dspl .= "         <div class='col-xs-8 col-sm-8 col-md-8 col-lg-9 team-single-container-content'>";
            $np_dspl .= "            <h2>".get_the_title($next_id)."</h2>";
            $np_dspl .= "            <h3>".$designation."</h3>";
            $np_dspl .= "            <div class='team-divider'></div>";
            $np_dspl .= "            <div class='cool-link find-out-links' style='margin-top: 0px;padding-top: 7px;'>VIEW PROFILE</div>";
            $np_dspl .= "         </div>";
            $np_dspl .= "      </div>";
            $np_dspl .= "   </div>";
            $np_dspl .= "</a>";      

         }

         $np_dspl .="   </div>";
         $np_dspl .="   <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6 ' style='padding:0;'>";
//         $np_dspl .="      <div style='float:right;'>";

         if ($prev_id) {

            $designation= get_field( "designation",$prev_id);

            $thumb_id   = get_field("thumbnail_image",$prev_id);
            $image      = wp_get_attachment_image_src($thumb_id, "thumbnail");
            $feat_img   = $image[0];      

            $rollover   = get_field("rollover",$prev_id);
            $rollimage  = wp_get_attachment_image_src($rollover, "thumbnail");
            $roll_img   = $rollimage[0];

            if (!$feat_img) {$feat_img = get_template_directory_uri()."/img/thumbnail.jpg";}
            if (!$roll_img) {$roll_img = get_template_directory_uri()."/img/thumbnail.jpg";}      

            $np_dspl .= "<a href='".get_permalink($prev_id)."' >";
            $np_dspl .= "   <div class='single-team-block col-xs-12 col-sm-12 col-md-12 col-lg-12 team-single-container' style='float:right;'>";
            $np_dspl .= "      <div class=''>";
            $np_dspl .= "         <div class='single-prof-prev-img col-xs-4 col-sm-4 col-md-4 col-lg-3' style='padding:0;'>";
            $np_dspl .= "            <img class='bwimage' src='".$feat_img."' style='width:100%'>";
            $np_dspl .= "            <img class='rollover' src='".$roll_img."' style='width:100%' alt='".get_the_title($prev_id)."' title='".get_the_title($prev_id)."'>";
            $np_dspl .= "         </div>";
            $np_dspl .= "         <div class='col-xs-8 col-sm-8 col-md-8 col-lg-9 team-single-container-content'>";
            $np_dspl .= "            <h2>".get_the_title($prev_id)."</h2>";
            $np_dspl .= "            <h3>".$designation."</h3>";
            $np_dspl .= "            <div class='team-divider'></div>";
            $np_dspl .= "            <div class='cool-link find-out-links' style='margin-top: 0px;padding-top: 7px;'>VIEW PROFILE</div>";
            $np_dspl .= "         </div>";
            $np_dspl .= "      </div>";
            $np_dspl .= "   </div>";
            $np_dspl .= "</a>";    
         }
         //$np_dspl .="      </div>";
         $np_dspl .="   </div>";
         $np_dspl .="</div>";

         echo $np_dspl;      

      ?>  
      
      
      
      
   </div>      
</div>

<?php
get_footer(); 
?>