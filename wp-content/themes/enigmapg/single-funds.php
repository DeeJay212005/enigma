<?php

get_header(); 

$post_id = $post->ID;
$term = get_the_terms( $post_id, 'fund_type' )[0];

#------------------------------------------------------------------------#  
//get first and last post
$first_args = array("post_type" => 'funds', 'fund_type'=>$term->slug, 'exclude'=>$post_id, "posts_per_page" => "1", 'orderby'=>'post_date', 'order'=>'ASC');
$first_post = get_posts($first_args)[0];

$last_args = array("post_type" => 'funds', 'fund_type'=>$term->slug, 'exclude'=>$post_id, "posts_per_page" => "1", 'orderby'=>'post_date', 'order'=>'DESC');
$last_post = get_posts($last_args)[0];
#------------------------------------------------------------------------#   
//get prev and next post
$prev_args = array("post_type"=>'funds', 'fund_type'=>$term->slug, 'exclude'=>$post_id, "posts_per_page"=>"1", 'date_query'=>array('before'=>$post->post_date), 'orderby'=>'post_date', 'order'=>'DESC');
$prev_post = get_posts($prev_args)[0];

$next_args = array("post_type"=>'funds', 'fund_type'=>$term->slug, 'exclude'=>$post_id, "posts_per_page"=>"1", 'date_query'=>array('after'=>$post->post_date), 'orderby'=>'post_date', 'order'=>'ASC');
$next_post = get_posts($next_args)[0];
#------------------------------------------------------------------------#   
$next_id = $next_post->ID;            
if (!$next_id) {$next_id = $first_post->ID;} //end if

$prev_id = $prev_post->ID;
if (!$prev_id) {$prev_id = $last_post->ID;} //end if
#------------------------------------------------------------------------#    


$subtitle      = get_field("page_subtitle",$post_id);
$vintage_year  = get_field("vintage_year",$post_id);
$capital_raised= get_field("capital_raised",$post_id);
$fund_status   = get_field("fund_status",$post_id);
$link_text     = get_field("header_link_text",$post_id);
$page_link     = get_field("header_link",$post_id);
$fund_logo     = get_field("page_logo",$prod_id);
$compositions  = get_field("compositions",$post_id);

$accordian     = get_field( "accordian", $post_id);
$show_team     = get_field( "show_team", $post_id);
$team          = get_field( "team", $post_id);

$thumb_id = get_post_thumbnail_id($post_id);
$image = wp_get_attachment_image_src($thumb_id, "full");
$feat_img = $image[0];
if (!$feat_img) {$feat_img = "https://thoughtcapitaltest.co.za/ethos/wp-content/uploads/2017/08/Funds.jpg";}
//if (!$feat_img) {$feat_img = get_template_directory_uri()."/img/default_feat.jpg";}


//$fund_logo = get_field( "fund_logo", "fund_type_".$term->term_id);
$image = wp_get_attachment_image_src($fund_logo, "full");
$logo_img = $image[0];  


$header .= "<div class='page-break-mid' style='width:100%;margin:0;'></div>";
$header .= "   <div class='container-fluid' style='background-color: #000000;padding-top: 50px;'>";
$header .= "      <div class='container'>";
$header .= "         <div class='row'>";
$header .= "            <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container' style=''>";
$header .= "               <div class='col-xs-12 col-sm-6 col-md-8 col-lg-8 no-padding-container' style='padding-left: 0;'>";
$header .= "                  <div class='slider-content-block' style='height:auto;'>";
$header .= "                     <div class='top-content-block'>";
if (function_exists('yoast_breadcrumb')) {
   $header .= yoast_breadcrumb("<p id='breadcrumbs'>","</p>",false);
} //end if
$header .= "                        <h1>".get_the_title($post_id)."</h1>";
$header .= "                        <h2 class='home-about-block-sub-heading'>".$subtitle."</h2>";
$header .= "                        <div class='block-divi'></div>";
$header .= "                        <div class='blck-content-sheader' style='margin:0;'>VINTAGE YEAR:</div>";
$header .= "                        <div class='blck-content-sheaderb'><b>".$vintage_year."</b></div>";
$header .= "                        <div class='blck-content-sheader' style='margin:0;'>CAPITAL RAISED:</div>";
$header .= "                        <div class='blck-content-sheaderb'><b>".$capital_raised."</b></div>";
$header .= "                        <div class='blck-content-sheader' style='margin:0;'>FUND STATUS:</div>";
$header .= "                        <div class='blck-content-sheaderb'><b>".$fund_status."</b></div>";
$header .= "                        <a href='".$page_link."'>";
$header .= "                           <div class='cool-link find-out-links banner-content-block-alink banner-contact-link-l' style='float:left;'>".$link_text."</div>";
$header .= "                           <div class='cool-link find-out-links banner-content-block-alink banner-contact-link-m' style='float:left;'>View More</div>";
$header .= "                           <div class='head-white-dot' style='float:left;'>";
$header .= "                              <div class='head-black-dot'>";
$header .= "                              <img src='".get_template_directory_uri()."/icons/arrow.svg'>"; 
$header .= "                           </div></div>";          
$header .= "                        </a>"; 
$header .= "                     </div>";
$header .= "                  </div>";
$header .= "               </div>";
$header .= "            <div class='col-sm-6 col-md-4 ethos-right-header-image pull-right' style='margin-bottom:-80px;margin-top:0px;padding-right:0;'>";
$header .= "               <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 header-image' style='background-color: #1a1a1a !important;padding:0;height:auto;position:relative;margin-top:148px;'>";
$header .= "                  <img src='".$feat_img."' style='width:100%;'>";
if($logo_img) {
   $header .= "               <div style='position:absolute;bottom:0;right:0;padding:10px;width:120px;'>";
   $header .= "                  <img src='".$logo_img."' style='width:100%;'>";
   $header .= "               </div>";
} //end if
$header .= "               </div>";
$header .= "            </div>";
$header .= "         </div>";
$header .= "      </div>";
$header .= "   </div>";
$header .= "</div>";

echo $header;

?>

<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container">
   <div class="container container-full ">
      <?php the_content(); ?>
   </div>
</div>     

<?php

if ($accordian) {
   accord_displ($accordian);
} //end if


if($compositions) {

   $cnt = 0;
   $comp_displ .= "<div class='container-fluid'>";
   $comp_displ .= "   <div class='container container-full'>";
   $comp_displ .= "      <button class='accordion' style='font-weight: 600;'>INVESTOR COMPOSITIONS</button>";
   $comp_displ .= "		 <div class='panel'>";


   foreach ($compositions as $composition) {
      $cnt++;

      $percent = "";
      $list_names = "";
      $colours = "";
      
      $comp_title = $composition['comp_title'];
      $comps = $composition['composition'];
      
      $comp_cnt = 0;
      
      foreach ($comps as $comp) {
         $comp_cnt ++;

         $percent .= "['".$comp['percent']."',   ".$comp['percent']."],";
         $list_names .= "<li>".$comp['list_name']."<b> (".$comp['percent']."%)</b></li>";
         
         if ($comp_cnt == 1) {$colours .= "'".$comp['colour']."'";} else {$colours .= " ,'".$comp['colour']."'";}
         
      } //end foreach


      $comp_displ .= "		    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='padding: 0'>";      
      $comp_displ .= "		       <div class='col-xs-12 col-sm-4 col-md-3 col-lg-3' style='padding: 0'>";
      $comp_displ .= "		          <div id=chart-".$cnt." style='min-width: 310px; height: auto; max-width: 600px; margin: 0 auto'></div>";
      $comp_displ .= "		       </div>";

      $comp_displ .= "		       <div class='col-xs-12 col-sm-8 col-md-9 col-lg-9'>";
      $comp_displ .= "		          <p style='color: #000000 !important;font-size: 14px;letter-spacing: 2px;font-weight: 600;'>".$comp_title."</p>";
      $comp_displ .= "		          <ul class='bpoint gold-circle' style='padding: 0;'>";
      $comp_displ .= "		          ".$list_names;
      $comp_displ .= "		          </ul>";
      $comp_displ .= "		       </div>";
      $comp_displ .= "		    </div>";
      
      
      
      
      $chart_js .= "   

         Highcharts.chart('chart-".$cnt."', {
            colors: [".$colours." ], 
            chart: {
                 plotBackgroundColor: null,
                 plotBorderWidth: 0,
                 plotShadow: false
             },
             title: {
                 text: '',
                 align: 'center',
                 verticalAlign: 'middle',
                 y: 40
             },
             tooltip: {
                 style: {display: 'none',}
             },
             plotOptions: {
                 pie: {
                     dataLabels: {
                         enabled: true,
                         distance: -35,
                         style: {
                             fontWeight: '600',
                             color: 'white',
                             fontSize: '13px',
                             textShadow: 'false',
                             textOutline: 'false',
                         }
                     },
                     startAngle: 0,
                     endAngle: 180,
                     center: ['10%', '50%'],
                 }
             },
             series: [{
                 type: 'pie',
                 name: '',
                 innerSize: '50%',
                 borderWidth: 0, // < set this option
                 data: [
                     ".$percent."
                     {
                         name: '',
                         y: 0.2,
                         dataLabels: {
                             enabled: false
                         }

                     }
                 ]
             }]
         });
         
         setTimeout(function() {
            var highchart = document.querySelector('#chart-".$cnt." svg:last-of-type');
            highchart.setAttribute('viewBox', '55 132 350 290');
         }, 10);"; 
      
   } //end foreach

   $comp_displ .= "      </div>";
   $comp_displ .= "   </div>";
   $comp_displ .= "</div>";
   
   echo $comp_displ;   
}




$meta_query = array(array('key' => 'investment_fund','value' => $post_id));
$invest_args = array("post_type" => 'invest_portfolio', 'meta_query' => $meta_query, "posts_per_page" => "-1", 'orderby'=>'post_date', 'order'=>'asc' );
$investments = get_posts($invest_args);
if($investments) {  
   $cnt = 0;
   foreach ($investments as $invest) {
      $cnt ++;

      $invest_id  = $invest->ID;   
      $company    = get_field( "investment_company", $invest_id);
      
      $logo       = get_field( "logo", $company);
      $image      = wp_get_attachment_image_src($logo, "full");
      $logo_img   = $image[0];
      
      if($logo_img) {
         $companies .= "<div class='col-xs-2 col-sm-2 col-md-2 col-lg-2' style='background:#FFFFFF;'>";
         $companies .= "  <a href='".get_permalink($invest_id)."'><img src='".$logo_img."' style='width:100%;' alt='".get_the_title($company)."' title='".get_the_title($company)."' ></a>";
         $companies .= "</div>";
      } //end if 
      
      if (($cnt % 6) == 0) {
         $companies .= "<div class='col-sm-12'></div>";
      } //end if       

   } //end foreach
?>
<div class='container-fluid'>
   <div class='container container-full'>
      <button class='accordion' style='font-weight: 600;'>CURRENT INVESTMENTS</button>
      
      <div class='panel'>
         <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='padding: 0'>
            <?php echo $companies; ?>
         </div>
      </div>
   </div>
</div>     
<?php 
} //end if
?>

<div class="container-fluid" style="margin-top:20px 0 20px 0">	
	<div class="container">
      <?php
         //next post ID, previous post ID, Post Type
         echo next_prev_display($next_id, $prev_id, 'Fund');
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
?>

<script>
   <?php echo  $chart_js; ?>   
</script>



<?php
get_footer(); 
?>