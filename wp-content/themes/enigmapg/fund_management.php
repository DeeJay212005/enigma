<?php
/*
Template Name: Fund Management
*/

get_header(); 

$post_id = $post->ID;
$page_links = get_field( "page_links",$post_id);
$accordian = get_field( "accordian", $post_id);

$terms = get_field( "fund_type", $post_id);
//$fund_logo = get_field( "fund_logo", "fund_type_".$term->term_id);

$show_team = get_field( "show_team", $post_id);
$team = get_field( "team", $post_id);

//header_block($post,$fund_logo);
header_block($post);


$tax_query['relation'] = 'OR';
foreach ($terms as $term) {
   $tax_query[] = array(array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $term->slug));
}

?>
<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container">
   <div class="container container-full ">
      <?php the_content(); ?>
   </div>
</div>     

<div class='container-fluid' style=''>
   <div class='container'> 
      <div class='row'>     
  
         <?php
         
            
            $meta_query = array(array('key' => 'fund_state','value' => 'current'));
            $fund_args = array("post_type" => 'funds', 'meta_query' => $meta_query, 'tax_query'=>$tax_query, "posts_per_page" => "-1", 'orderby'=>'post_date', 'order'=>'desc' );
      
            $funds = get_posts($fund_args);         
            if ($funds) {
               $cnt = 0;

               echo "   <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container'>";
	   	      echo "      <p class='lite-headers'>CURRENT FUNDS</p>";
	   	      echo "      <div class='block-divi'></div>";
		         echo "   </div>";
               
               
               foreach ($funds as $fund) {   
                  $cnt++;
                  
                  $fund_id = $fund->ID;
                  
                  $vintage_year  = get_field( "vintage_year",$fund_id);
                  $capital_raised= get_field( "capital_raised",$fund_id);
                  $fund_status   = get_field( "fund_status",$fund_id);
                  
                  echo "   <div class='col-xs-12 col-sm-6 col-md-4 col-lg-4 no-padding-container company-blocks'>";
                  echo "      <div class='funds-block-headers'>".get_the_title($fund_id)."</div>";
                  echo "      <div class='blck-content-sheaderblck' style='margin:0!important;'>VINTAGE YEAR:</div>";
                  echo "      <div class='blck-content-sheaders'><b>".$vintage_year."</b></div>";
                  echo "      <div class='blck-content-sheaderblck' style='margin:0!important;'>CAPITAL RAISED:</div>";
                  echo "      <div class='blck-content-sheaders'><b>".$capital_raised."</b></div>";
                  echo "      <div class='blck-content-sheaderblck' style='margin:0!important;'>FUND STATUS:</div>";
                  echo "      <div class='blck-content-sheaders'><b>".$fund_status."</b></div>";
                  echo "      <div class='home-block-divi'></div>";
                  echo "      <p style='margin: 0'>".$fund->post_excerpt."</p>";                  
                  echo "      <a href='".get_permalink($fund_id)."'><div class='cool-link find-out-links' >VIEW ".get_the_title($fund_id)."</div></a>";
                  echo "   </div>"; 


                  if (($cnt % 3) == 0) {
                     echo "<div class='col-xs-12'></div>";
                  } //end if                      
                  
               } //foreach
            } //end if
         ?>
      </div>
   </div>
</div>
<div class='container-fluid'>
   <div class='container'> 
      <div class='row'>     

         <?php

            $meta_query = array(array('key' => 'fund_state','value' => 'past'));
            $fund_args = array("post_type" => 'funds', 'meta_query' => $meta_query, 'tax_query'=>$tax_query, "posts_per_page" => "-1", 'orderby'=>'post_date', 'order'=>'desc' );
            $funds = get_posts($fund_args);         
            if ($funds) {
               
               echo "   <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container'>";
	   	      echo "      <p class='lite-headers'>PAST FUNDS</p>";
	   	      echo "      <div class='block-divi'></div>";
		         echo "   </div>";               
               
               
               $cnt = 0;
               foreach ($funds as $fund) {   
                  $cnt++;
                  
                  $fund_id = $fund->ID;
                  $vintage_year  = get_field( "vintage_year",$fund_id);
                  $capital_raised= get_field( "capital_raised",$fund_id);
                  $fund_status   = get_field( "fund_status",$fund_id);
                  
                  echo "   <div class='col-xs-12 col-sm-6 col-md-4 col-lg-4 no-padding-container company-blocks'>";
                  echo "      <div class='funds-block-headers'>".get_the_title($fund_id)."</div>";
                  echo "      <p style='margin:0;'>VINTAGE YEAR:</p>";
                  echo "      <p><b>".$vintage_year."</b></p>";
                  echo "      <p style='margin:0;'>CAPITAL RAISED:</p>";
                  echo "      <p><b>".$capital_raised."</b></p>";
                  echo "      <p style='margin:0;'>FUND STATUS:</p>";
                  echo "      <p><b>".$fund_status."</b></p>";
                  echo "      <div class='home-block-divi'></div>";
                  echo "      <p style='margin: 0'>".$fund->post_excerpt."</p>";                  
                  echo "      <a href='".get_permalink($fund_id)."'><div class='cool-link find-out-links' >VIEW ".get_the_title($fund_id)."</div></a>";
                  echo "   </div>"; 

                  if (($cnt % 3) == 0) {
                     echo "<div class='col-xs-12'></div>";
                  } //end if                      
                  
               } //foreach
            } //end if
         ?>
      </div>
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