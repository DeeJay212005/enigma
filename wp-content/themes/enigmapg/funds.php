<?php
/*
Template Name: Funds
*/
get_header(); 
$post_id = $post->ID;
$page_link_title = get_field( "page_link_title",$post_id);
$page_links = get_field( "page_links",$post_id);
$accordian = get_field( "accordian", $post_id);

//$terms = get_field( "fund_type", $post_id);
if(!$terms) {$terms = get_terms( array('taxonomy' => 'fund_type'));}
//$fund_logo = get_field( "fund_logo", "fund_type_".$term->term_id);

$show_team = get_field( "show_team", $post_id);
$team = get_field( "team", $post_id);

//header_block($post,$fund_logo);
header_block($post);
#-------------------Change request values to php variables---------------#
foreach ($_REQUEST as $key => $value) {
   if (!is_array ($value)) {
      $$key = trim($value);
   }
}//next
#------------------------------------------------------------------------#
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   $fund_type     = preg_replace("/[^A-Za-z0-9 -_]/", "", $fund_type); 
   $invest_status = preg_replace("/[^A-Za-z0-9 -_]/", "", $invest_status); 

   $tax_query = array();
   $invest_meta_query = array();
   if ($fund_type != "")    {
      $tax_query[] = array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $fund_type);
   } else {
      $tax_query['relation'] = 'OR';
      foreach ($terms as $term) {
         $tax_query[] = array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $term->slug);
      } //end foreach
   }
   if ($invest_status != "")  {$meta_query[]= array('key' => 'fund_state','value' => $invest_status,'compare' => '=');}

} else {
   $fund_type = "";
   $invest_status = "";
   $meta_query = array();

   $tax_query['relation'] = 'OR';
   foreach ($terms as $term) {
      $tax_query[] = array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $term->slug);
   }   
   
}//end if
#------------------------------------------------------------------------#  
foreach ($terms as $term) {
   if($fund_type == $term->slug) {
      $fund_drop .= "<option value='".$term->slug."' selected>".strtoupper($term->name)."</option>";
   } else {
      $fund_drop .= "<option value='".$term->slug."'>".strtoupper($term->name)."</option>";
   } //end if
}  
#------------------------------------------------------------------------#  
?>
<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container">
   <div class="container container-full ">
      <?php the_content(); ?>
   </div>
</div>     

<div class="container-fluid">
   <div class="container container-full page-container-top">
      <div class="desk-filter-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container"> 
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container">
            <div class="row">	  	
               <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="padding: 0">
                  <div class="lite-headers" style="padding-top: 20px">FILTER BY:</div>
               </div>
               <div class="col-xs-12 col-sm-4 col-md-4 col-lg-8" style="padding-right: 0;">
                  <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method='post'>
                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding-right: 0">
                        <select id="option" name="fund_type">
                           <option value="">FUND TYPE</option>
                           <?php echo $fund_drop;?>
                        </select>
                     </div> 
                     <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding-right: 0">
                        <select id="option" name="invest_status">
                           <option value="">INVESTMENT STATUS</option>
                           <option value="current" <?php if ($invest_status == "current") {echo "selected";}?>>CURRENT INVESTMENTS</option>
                           <option value="past" <?php if ($invest_status == "past") {echo "selected";}?>>PAST INVESTMENTS</option>
                        </select>
                     </div> 
                  </form>
               </div> 
               <div class="block-divi" style="margin-top: 70px"></div>
            </div>
         </div>
      </div>
   </div>

   <div class="mobile-filter-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container" style="padding: 0;">	  	
         <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 pull-right" style="padding: 0">
            <button class="" data-toggle="modal" data-target="#filterby" style="width: 100%; color: #B3996B; background-color: #000;height: 48px;border: 0 solid #fff;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER </button>
         </div>	   	
         <div class="block-divi" style="margin-top: 70px"></div>
      </div>
   </div>
</div>  

<div class="modal custom fade" id="filterby" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content" style="border-radius: 0;">
         <div class="modal-header" style="border-bottom: 1px solid #B3996B; border-width: 90%;">
           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
           <h4 class="modal-title" id="myModalLabel">FILTER BY:</h4>
         </div>
         <div class="modal-body">
            <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method='post'> 
               <div class="dropdown-top-margin dropdown-mobile" style="width: 100%;margin-bottom: 20px; margin-top: 20px;">
                  <select id="option" name="fund_type">
                     <option value="">FUND TYPE</option>
                     <?php echo $fund_drop;?>
                  </select>
               </div> 
               <div class="dropdown-mobile" style="width: 100%;margin-bottom: 20px">
                  <select id="option" name="invest_status">
                     <option value="">INVESTMENT STATUS</option>
                     <option value="current" <?php if ($invest_status == "current") {echo "selected";}?>>CURRENT INVESTMENTS</option>
                     <option value="past" <?php if ($invest_status == "past") {echo "selected";}?>>PAST INVESTMENTS</option>
                  </select>
               </div> 
               <div class="dropdown-mobile" style="width: 100%;margin-bottom: 20px">   	
                  <button type="submit" value="submit" class="" style="width: 100%; color: #B3996B; background-color: #000;height: 48px;border: 0 solid #fff;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</button>
               </div>            
            </form>
         </div>
      </div>
   </div>
</div>

<div class='container-fluid'>
   <div class='container'> 
      <?php

         $fund_args = array("post_type" => 'funds', 'meta_query' => $meta_query, 'tax_query'=>$tax_query, "posts_per_page" => "-1", 'orderby'=>'post_date', 'order'=>'desc' );

         $funds = get_posts($fund_args);         
         if ($funds) {
            $cnt = 0;
            foreach ($funds as $fund) {   
               $cnt++;

               $fund_id = $fund->ID;

               $vintage_year  = get_field( "vintage_year",$fund_id);
               $capital_raised= get_field( "capital_raised",$fund_id);
               $fund_status   = get_field( "fund_status",$fund_id);
               $ethos_link   = get_field( "ethos_link",$fund_id);

               echo "<a href='".$ethos_link."' target='_blank'>";
               echo "   <div class='col-xs-12 col-sm-6 col-md-4 col-lg-4 no-padding-container company-blocks'>";
               echo "      <div class='funds-block-headers'>".get_the_title($fund_id)."</div>";
               echo "      <div class='funds-block-divi-larg'></div>";
               echo "      <div class='funds-block-divi-mobi'></div>";
               echo "      <div class='blck-content-sheaderblck' style='margin:0;'>VINTAGE YEAR:</div>";
               echo "      <div class='blck-content-sheaders'><b>".$vintage_year."</b></div>";
               echo "      <div class='blck-content-sheaderblck' style='margin:0;'>CAPITAL RAISED:</div>";
               echo "      <div class='blck-content-sheaders'><b>".$capital_raised."</b></div>";
               echo "      <div class='blck-content-sheaderblck' style='margin:0;'>FUND STATUS:</div>";
               echo "      <div class='blck-content-sheaders'><b>".$fund_status."</b></div>";
               echo "      <div class='cool-link find-out-links' >VIEW ".get_the_title($fund_id)."</div>";
               echo "   </div>"; 
               echo "</a>"; 


               if (($cnt % 3) == 0) {
                  echo "<div class='col-xs-12'></div>";
               } //end if                      

            } //foreach
         } //end if
      ?>

   </div>
</div>


<?php
if($page_links) {
   
   $item_cnt = count($page_links);
   
   if($item_cnt >= 5)  {
      $items_per_row = "4";
   } else {             
      if($item_cnt == 1){$items_per_row = 12;$one_style = " style='min-height:0;'";}
      if($item_cnt == 2){$items_per_row = 6;}
      if($item_cnt == 3){$items_per_row = 4;}
      if($item_cnt == 4){$items_per_row = 6;}
   } //end if*/
   
   echo "<div class='container-fluid page-link-margin company-blocks'>";
   echo "   <div class='container' style=''>";
   echo "      <div class='col-xs-12'>";
   echo "         <div class='home-block-headers' style='color:#BABABA;'>".$page_link_title."</div>";
   echo "      </div>";
   
   $cnt = 0;
   foreach ($page_links as $page_link) {
      
      $cnt++;
      $post_id       = $page_link['page_link'];
      $page_title    = $page_link['page_title'];
     // $page_sub      = $page_link['page_sub_title'];
      $page_excerpt  = $page_link['page_excerpt'];
      $page_link_text= $page_link['page_link_text'];

      $page_excerpt = preg_replace(" (\[.*?\])",'',$page_excerpt);
      $page_excerpt = strip_shortcodes($page_excerpt);
      $page_excerpt = strip_tags($page_excerpt);
      $page_excerpt = trim(preg_replace( '/\s+/', ' ', $page_excerpt));
      
      

      $info = get_post($post_id); 

      if ($info->post_type == 'attachment') {
         $attach =  wp_get_attachment_url($post_id);
      } //end if

      if (!$page_title)    {$page_title   = get_the_title($post_id);}
      if (!$page_sub)      {$page_sub     = get_field("page_subtitle",$post_id);}
      if (!$page_excerpt)  {$page_excerpt = $info->post_excerpt;}
      if (strlen($page_excerpt) > 130) {$page_excerpt = substr($page_excerpt, 0, 130)." [...]";}
      if (!$page_link_text)  {$page_link_text = "FIND OUT MORE";}
      
      
      echo "   <div class='col-xs-12 col-sm-".$items_per_row." col-md-".$items_per_row." col-lg-".$items_per_row." no-padding-container'>";
      echo "      <div class='home-block-headers' ".$one_style.">".$page_title."</div>";
      //echo "      <p class='home-block-sub-headers'".$one_style.">".$page_sub."</p>";
      echo "      <div class='home-block-divi'></div>";      
      echo "      <p ".$one_style.">".$page_excerpt."</p>";
      echo "      <a href='".get_permalink($post_id)."'><div class='cool-link find-out-links' >".$page_link_text."</div></a>";
      echo "   </div>"; 
      
      if($item_cnt == 4)     {
         if (($cnt % 2) == 0) {
            echo "<div class='col-xs-12'></div>";
         } //end if      
      } else {
         
         $clear_row = intval(12/$items_per_row);
         if (($cnt % $clear_row) == 0) {
            echo "<div class='col-xs-12'></div>";
         } //end if      
         
      } //end if
      
   } //end foreach
   
   echo "   </div>";
   echo "</div>";    
   
} //end if
?>

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

<?php
get_footer(); 
?>