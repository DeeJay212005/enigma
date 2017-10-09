<?php
/*
Template Name: Reports
*/

get_header(); 
header_block($post);

$post_id = $post->ID;

$page_links = get_field( "page_links",$post_id);
$accordian = get_field( "accordian", $post_id);
$compositions  = get_field("compositions",$post_id);
$show_team = get_field( "show_team", $post_id);

$team = get_field( "team", $post_id);


$report_args = array("post_type" => 'reports', 'tax_query'=>array(array('taxonomy' => 'report_type','field' => 'slug', 'terms' => 'interim-results')), "posts_per_page" => "1", 'orderby'=>'post_date', 'order'=>'DESC' );
$reports = get_posts($report_args);
if($reports) {
   foreach ($reports as $report) {
      $report_id = $report->ID;
      
      $report_file = get_field( "report_file", $report_id);
      
      $thumb_id = get_post_thumbnail_id($report_id);
      $image = wp_get_attachment_image_src($thumb_id, "full");
      $report_img = $image[0];          
      
      
      $report_displ .= "<a href='".get_template_directory_uri()."/download.php?file_url=".urlencode($report_file)."' target='_blank'>";      
      $report_displ .= "   <div class='single-team-block col-xs-12 col-sm-6 col-md-6 col-lg-6 report-single-container'>";
      $report_displ .= "      <div class=''>";
      $report_displ .= "         <div class='single-prof-next-img col-xs-4 col-sm-4 col-md-4 col-lg-3' style='padding:0;'>";
      $report_displ .= "            <img class='' src='".$report_img."' style='width:100%'>";
      $report_displ .= "         </div>";
      $report_displ .= "         <div class='col-xs-8 col-sm-8 col-md-8 col-lg-9 report-single-container-content'>";
      $report_displ .= "            <h2>".get_the_title($report_id)."</h2>";
      $report_displ .= "            <div class='team-divider'></div>";
      $report_displ .= "            <div class='cool-link find-out-links' style='margin-top: 0px;padding-top: 7px;'>View Report</div>";
      $report_displ .= "         </div>";
      $report_displ .= "      </div>";
      $report_displ .= "   </div>";
      $report_displ .= "</a>";
      
   } //end foreach
} //end if

$report_args = array("post_type" => 'reports', 'tax_query'=>array(array('taxonomy' => 'report_type','field' => 'slug', 'terms' => 'financial-statement')), "posts_per_page" => "1", 'orderby'=>'post_date', 'order'=>'DESC' );
$reports = get_posts($report_args);
if($reports) {
   foreach ($reports as $report) {
      $report_id = $report->ID;
      
      $report_file = get_field( "report_file", $report_id);
      
      $thumb_id = get_post_thumbnail_id($report_id);
      $image = wp_get_attachment_image_src($thumb_id, "full");
      $report_img = $image[0];          
      
      $report_displ .= "<a href='".get_template_directory_uri()."/download.php?file_url=".urlencode($report_file)."' target='_blank'>";      
      $report_displ .= "   <div class='single-team-block col-xs-12 col-sm-6 col-md-6 col-lg-6 report-single-container'>";
      $report_displ .= "      <div class=''>";
      $report_displ .= "         <div class='single-prof-next-img col-xs-4 col-sm-4 col-md-4 col-lg-3' style='padding:0;'>";
      $report_displ .= "            <img class='' src='".$report_img."' style='width:100%'>";
      $report_displ .= "         </div>";
      $report_displ .= "         <div class='col-xs-8 col-sm-8 col-md-8 col-lg-9 report-single-container-content'>";
      $report_displ .= "            <h2>".get_the_title($report_id)."</h2>";
      $report_displ .= "            <div class='team-divider'></div>";
      $report_displ .= "            <div class='cool-link find-out-links' style='margin-top: 0px;padding-top: 7px;'>View Report</div>";
      $report_displ .= "         </div>";
      $report_displ .= "      </div>";
      $report_displ .= "   </div>";
      $report_displ .= "</a>";
      
   } //end foreach
} //end if

?>
<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container">
   <div class="container container-full the-content-container">
      <div class='row'>
         <?php echo $report_displ; ?>
      </div>
   </div>
</div>     

<!-- MAIN CONTENT -->
<div class="container-fluid">
   <div class="container the-content-container">
      <?php the_content(); ?>
   </div>
</div>     


<?php

if ($accordian) {
   accord_displ($accordian);
} //end if

if($page_links) {
   page_link_div($page_links, 2);
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
?>

<?php
get_footer(); 
?>