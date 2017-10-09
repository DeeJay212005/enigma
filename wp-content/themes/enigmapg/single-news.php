<?php

get_header(); 

$post_id = $post->ID;

#------------------------------------------------------------------------#  
//get first and last post
$first_args = array("post_type" => 'news', 'exclude'=>$post_id, "posts_per_page" => "1", 'orderby'=>'post_date', 'order'=>'ASC');
$first_post = get_posts($first_args)[0];

$last_args = array("post_type" => 'news', 'exclude'=>$post_id, "posts_per_page" => "1", 'orderby'=>'post_date', 'order'=>'DESC');
$last_post = get_posts($last_args)[0];
#------------------------------------------------------------------------#   
//get prev and next post
$prev_args = array("post_type"=>'news', 'exclude'=>$post_id, "posts_per_page"=>"1", 'date_query'=>array('before'=>$post->post_date), 'orderby'=>'post_date', 'order'=>'DESC');
$prev_post = get_posts($prev_args)[0];

$next_args = array("post_type"=>'news', 'exclude'=>$post_id, "posts_per_page"=>"1", 'date_query'=>array('after'=>$post->post_date), 'orderby'=>'post_date', 'order'=>'ASC');
$next_post = get_posts($next_args)[0];
#------------------------------------------------------------------------#   
$next_id = $next_post->ID;            
if (!$next_id) {$next_id = $first_post->ID;} //end if

$prev_id = $prev_post->ID;
if (!$prev_id) {$prev_id = $last_post->ID;} //end if
#------------------------------------------------------------------------#   


$page_links = get_field( "page_links",$post_id);
$accordian  = get_field( "accordian", $post_id);
$show_team  = get_field( "show_team", $post_id);

$team = get_field( "team", $post_id);


$subtitle   = get_field("page_subtitle",$prod_id);
$link_text  = get_field("link_text",$prod_id);
$page_link  = get_field("page_link",$prod_id);
$page_logo  = get_field("page_logo",$prod_id);
$excerpt    = get_field("header_excerpt",$prod_id);

$src        = get_field("source",$prod_id);
$src_date   = get_field("source_date",$prod_id);

$header .= "<div class='page-break-mid' style='width:100%;margin:0;'></div>";
$header .= "   <div class='container-fluid' style='background-color: #000000;'>";
$header .= "      <div class='container'>";
$header .= "         <div class='row'>";
$header .= "            <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container' style=''>";
$header .= "               <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container' style=''>";
$header .= "                  <div class='' style='height:auto;'>";
$header .= "                     <div class='top-content-block'>";
if (function_exists('yoast_breadcrumb')) {
   $header .= yoast_breadcrumb("<p id='breadcrumbs'>","</p>",false);
} //end if
$header .= "                        <h1 class='single-news-header'>".get_the_title($post_id)."</h1>";
$header .= "                        <h2 class='home-about-block-sub-heading'>".date("d M Y",strtotime($post->post_date))."</h2>";
$header .= "                        <div class='block-divi'></div>";
$header .= "                        <div class='blck-content-sheader'><strong>".$src."</strong></div>";
$header .= "                        <div class='blck-content-sheaderb'>".$src_date."</div>";

if($page_link) {
$header .= "                  <a href='".$page_link."'>";
$header .= "                     <div class='cool-link find-out-links banner-content-block-alink banner-contact-link-l' style='float:left;'>".$link_text."</div>";
$header .= "                     <div class='cool-link find-out-links banner-content-block-alink banner-contact-link-m' style='float:left;'>View More</div>";
$header .= "                     <div class='head-white-dot' style='float:left;'>";
$header .= "                        <div class='head-black-dot'>";
$header .= "                        <img src='".get_template_directory_uri()."/icons/arrow.svg'>"; 
$header .= "                     </div></div>";          
$header .= "                  </a>";   
} //end if

$header .= "                     </div>";
$header .= "                  </div>";
$header .= "               </div>";
$header .= "         </div>";
$header .= "      </div>";
$header .= "   </div>";
$header .= "</div>";

echo $header;

?>
 
<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container-news">
   <div class="container container-full ">
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container' style="margin-top: 50px;">
         <?php the_content(); ?>
      </div>
   </div>
</div>  

<?php
if ($accordian) {
   accord_displ($accordian);
} //end if


if($page_links) {
   page_link_div($page_links, 2);
}
?>

<div class="container-fluid" style="margin-top:20px 0 20px 0">	
	<div class="container">
      <?php
         //next post ID, previous post ID, Post Type
         echo next_prev_display($next_id, $prev_id, 'Communication');
      ?>  
   </div>      
</div>


<?php
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