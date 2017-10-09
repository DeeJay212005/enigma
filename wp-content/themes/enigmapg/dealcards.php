<?php
/*
Template Name: Deal Cards
*/
get_header(); 
header_block($post);
$post_id = $post->ID;
#------------------------------------------------------------------------#  
//GET INVESTMENTS, AND FUNDS FOR DROPDOWNS
$fund_types    = get_field( "fund_type", $post_id);

$invest_drop   = get_invest_drop($fund_types,$deal_invest);
$year_drop     = get_year_drop($deal_year);
$fund_drop     = get_fund_drop($fund_types,$deal_fund);
#-------------------Change request values to php variables---------------#
foreach ($_REQUEST as $key => $value) {
   if (!is_array ($value)) {
      $$key = trim($value);
   }
}//next
#------------------------------------------------------------------------#
$deal_invest   = preg_replace("/[^0-9 ]/", "", $deal_invest); 
$deal_year     = preg_replace("/[^0-9 ]/", "", $deal_year); 
$deal_fund     = preg_replace("/[^0-9 ]/", "", $deal_fund); 

if (($deal_invest)||($deal_fund)) {
	
   $deal_meta_query = array();
   $deal_meta_query['relation'] = 'AND';
   if ($deal_invest != "")    {$deal_meta_query[]= array('key' => 'investment','value' => $deal_invest,'compare' => '=');}
   if ($deal_fund != "")      {
      $deal_meta_query[]= array('key' => 'fund','value' => $deal_fund,'compare' => '=');
   } else {
      
      $default_tax_query['relation'] = 'OR';
      foreach ($fund_types as $fund_type) {
         $default_tax_query[] = array(array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $fund_type->slug));
      } //end foreach

      // get associated funds
      $fund_args = array("post_type" => 'funds',  'tax_query'=>$default_tax_query, "posts_per_page" => "-1", 'orderby'=>'post_title', 'order'=>'asc' );
      $funds = get_posts($fund_args);
      if($funds) {  
         foreach ($funds as $fund) {
            $default_funds[] = $fund->ID;
         } //end foreach
      } //end if

      $deal_meta_query[]= array('key' => 'fund','value' => $default_funds,'compare' => 'IN');
      
   }

   
} else {
	$deal_invest = "";
	$deal_year = "";
	$deal_fund = "";
   $deal_meta_query = array();
   
   $default_funds = array();
   foreach ($fund_types as $fund_type) {
      $default_funds[] = $fund_type->term_id;
   }
   
   $default_tax_query['relation'] = 'OR';
   foreach ($fund_types as $fund_type) {
      $default_tax_query[] = array(array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $fund_type->slug));
   } //end foreach
   
   // get associated funds
   $fund_args = array("post_type" => 'funds',  'tax_query'=>$default_tax_query, "posts_per_page" => "-1", 'orderby'=>'post_title', 'order'=>'asc' );
   $funds = get_posts($fund_args);
   if($funds) {  
      foreach ($funds as $fund) {
         $default_funds[] = $fund->ID;
      } //end foreach
   } //end if

   $deal_meta_query[]= array('key' => 'fund','value' => $default_funds,'compare' => 'IN');
   
}//end if

#------------------------------------------------------------------------#   

$deal_args = array("post_type" => 'dealcards', 'meta_query' => $deal_meta_query, "posts_per_page" => "-1",'date_query'=>array('year'=>$deal_year), 'orderby'=>'post_date', 'order'=>'desc' );
$dealcards = get_posts($deal_args);
if($dealcards) {  
   $cnt = 0;
   foreach ($dealcards as $dealcard) {
      $cnt ++;
      
      $deal_id  = $dealcard->ID;
      $deal_sub = get_field("page_subtitle",$deal_id);
      
      $invest_id  = get_field("investment", $deal_id);
      $company    = get_field("investment_company", $invest_id);
      $fund_id    = get_field("fund", $deal_id);

      
      $thumb_id   = get_field("thumbnail_image",$deal_id);
      $image      = wp_get_attachment_image_src($thumb_id, "thumbnail");
      $feat_img   = $image[0];      

      $rollover   = get_field("rollover",$deal_id);
      $rollimage  = wp_get_attachment_image_src($rollover, "thumbnail");
      $roll_img   = $rollimage[0];

      if (!$feat_img) {$feat_img = get_template_directory_uri()."/img/thumbnail.jpg";}
      if (!$roll_img) {$roll_img = get_template_directory_uri()."/img/thumbnail.jpg";}
      if (strlen($deal_sub) > 70) {$deal_sub = substr($deal_sub, 0, 70)." [...]";}
      
      $deal_displ .= "<a href='".get_permalink($deal_id)."'>";
      $deal_displ .= "<div class='dealcard-block col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
      $deal_displ .= "   <div class='col-xs-2 col-sm-2 col-md-2 col-lg-2' style='padding:0;'>";
      $deal_displ .= "         <img class='bwimage' src='".$feat_img."' style='width:100%'>";
      $deal_displ .= "         <img class='rollover' src='".$roll_img."' style='width:100%' alt='".get_the_title($post_id)."' title='".get_the_title($post_id)."'>";
      $deal_displ .= "   </div>";
      $deal_displ .= "   <div class='col-xs-10 col-sm-10 col-md-10 col-lg-10 dealcard-container-content' style='padding:0 0 0 15px;'>";
	   $deal_displ .= "      <h2 class=''>".get_the_title($deal_id)."</h2>";
      $deal_displ .= "      <h3 class='' style='color:#000000;margin:0!important;'>".$deal_sub."</h3>";      
      $deal_displ .= "      <div class='dealcard-block-divi'></div>";      
	   $deal_displ .= "      <p style='margin: 0!important;'>YEAR: <strong>".date("Y", strtotime($dealcard->post_date))."</strong></p>";
      if($company) {
	   $deal_displ .= "      <p style='margin: 0!important;'>COMPANY: <strong>".get_the_title($company)."</strong></p>";
      }
      if($fund_id) {
	   $deal_displ .= "      <p style='margin: 0!important;'>FUND: <strong>".get_the_title($fund_id)."</strong></p>";
      }
	   $deal_displ .= "      <div class='cool-link find-out-links' >VIEW DEAL CARD</div>";
	   $deal_displ .= "   </div>";
	   $deal_displ .= "</div>";
	   $deal_displ .= "</a>";
         
   } //end foreach
} //end if
?>
<!-- MAIN CONTENT -->
<div class="container-fluid">
   <div class="container container-full page-container-top" style="padding: 80px 15px 0 15px">
      <div class="desk-filter-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container"> 
         <div class="row">	  
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container">	  	
               <div class="row">	  	
                  <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="padding: 0">
                     <div class="lite-headers" style="padding-top: 20px">FILTER BY:</div>
                  </div>
                  <div class="col-xs-12 col-sm-3 col-md-3 col-lg-9" style="padding-right: 0">
                     <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method='post'>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="padding-right: 0">
                           <select id="option" name="deal_invest">
                              <option value="">INVESTMENTS</option>
                              <?php echo $invest_drop; ?>
                           </select>
                        </div> 
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2" style="padding-right: 0">
                           <select id="option" name="deal_year">
                              <option value="">YEARS</option>
                              <?php echo $year_drop; ?>
                           </select>
                        </div> 
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="padding-right: 0">
                           <select id="option" name="deal_fund">
                              <option value="">FUNDS</option>
                              <?php echo $fund_drop; ?>
                           </select>
                        </div> 
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2" style="padding-right: 0">
                           <button type="submit" value="submit" class="" style="width: 100%; color: #B3996B; background-color: #000;height: 48px;border: 0 solid #fff;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</button>
                        </div> 	
                     </form>	   
                  </div>      		      
                  <div class="block-divi" style="margin-top: 70px; margin-bottom:50px;"></div>
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
               <select id="option" name="deal_invest">
                  <option value="">INVESTMENTS</option>
                  <?php echo $invest_drop; ?>
               </select>
		      </div> 
            <div class="dropdown-mobile" style="width: 100%;margin-bottom: 20px">
               <select id="option" name="deal_year">
                  <option value="">YEARS</option>
                  <?php echo $year_drop; ?>
               </select>
            </div> 
            <div class="dropdown-mobile" style="width: 100%;margin-bottom: 20px">   	
               <select id="option" name="deal_fund">
                  <option value="">FUNDS</option>
                  <?php echo $fund_drop; ?>
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



<div class="container-fluid">
   <div class="container container-full comms-outer-container">
      <?php echo $deal_displ; ?>
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
   
jQuery(document).ready(function(){  
   
   var this_sel = jQuery('select[name=deal_invest]').parent();
   var styledSelect = this_sel.find('div.select-styled');
   var SelectOptions = this_sel.find('ul.select-options');
   var numberOfOptions = SelectOptions.children('li').length;

   for (var i = 0; i < numberOfOptions; i++) {
      if (SelectOptions.children().eq(i).attr('rel') == "<?php echo $deal_invest;?>") {
         styledSelect.text(SelectOptions.children().eq(i).text());
      } //end if
   } //end foreach

   
   var this_sel = jQuery('select[name=deal_year]').parent();
   var styledSelect = this_sel.find('div.select-styled');
   var SelectOptions = this_sel.find('ul.select-options');
   var numberOfOptions = SelectOptions.children('li').length;

   for (var i = 0; i < numberOfOptions; i++) {
      if (SelectOptions.children().eq(i).attr('rel') == "<?php echo $deal_year;?>") {
         styledSelect.text(SelectOptions.children().eq(i).text());
      } //end if
   } //end foreach   
   
   
   var this_sel = jQuery('select[name=deal_fund]').parent();
   var styledSelect = this_sel.find('div.select-styled');
   var SelectOptions = this_sel.find('ul.select-options');
   var numberOfOptions = SelectOptions.children('li').length;

   for (var i = 0; i < numberOfOptions; i++) {
      if (SelectOptions.children().eq(i).attr('rel') == "<?php echo $deal_fund;?>") {
         styledSelect.text(SelectOptions.children().eq(i).text());
      } //end if
   } //end foreach

});  
   
</script>


<?php
get_footer(); 
?>