<?php
/*
Template Name: Investment Portfolios
*/
#-------------------Change request values to php variables---------------#
foreach ($_REQUEST as $key => $value) {
   if (!is_array ($value)) {
      $$key = trim($value);
   }
}//next
#------------------------------------------------------------------------#
$thecatg = strval(get_query_var('thecatg'));
$thecatg = preg_replace("/[^A-Za-z0-9-]/", "", $thecatg); 
$thecatg = strtolower($thecatg); 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   if (($comp_search)||($invest_status)) {

      $err = 0;
      $msg = "";

      $comp_search = preg_replace("/[^A-Za-z0-9 -_]/", "", $comp_search); 
      $invest_status = preg_replace("/[^A-Za-z0-9 -_]/", "", $invest_status); 

      $invest_meta_query = array();
      $invest_meta_query['relation'] = 'AND';
      if ($comp_search != "")    {$invest_meta_query[]= array('key' => 'investment_company','value' => $comp_search,'compare' => '=');}
      if ($invest_status != "")  {$fund_meta_query[]= array('key' => 'fund_state','value' => $invest_status,'compare' => '=');}
   }
} else {
   $comp_search = "";
   $news_order = "";
   $invest_meta_query = array();
   if($thecatg) {
      $invest_status = $thecatg;
      $invest_meta_query['relation'] = 'AND';
      $fund_meta_query[]= array('key' => 'fund_state','value' => $thecatg,'compare' => '=');
   }//end if
   
}//end if


#------------------------------------------------------------------------#  

get_header(); 
header_block($post);

$post_id = $post->ID;
$show_team = get_field( "show_team", $post_id);
$team = get_field( "team", $post_id);

#------------------------------------------------------------------------#   
//GET COMPANIES FOR FUND TYPES
$funds = get_field( "fund_type", $post_id);
$company_drop = get_fund_companies($funds,$comp_search);
#------------------------------------------------------------------------#   

// get associated funds
$fund_types = get_field( "fund_type", $post_id);
$tax_query['relation'] = 'OR';
foreach ($fund_types as $fund_type) {
   $tax_query[] = array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $fund_type->slug);
} //end foreach
$fund_args = array("post_type" => 'funds',  'tax_query'=>$tax_query, 'meta_query' => $fund_meta_query, "posts_per_page" => "-1", 'orderby'=>'post_date', 'order'=>'asc' );

$funds = get_posts($fund_args);
if($funds) {  
   $fund_arr = array();
   foreach ($funds as $fund) {
      $fund_arr[] = $fund->ID;
   } //end foreach
} //end if

$invest_meta_query[]= array('key' => 'investment_fund','value' => $fund_arr,'compare' => 'IN');
$invest_args = array("post_type" => 'invest_portfolio', 'meta_query' => $invest_meta_query, "posts_per_page" => "-1", 'orderby'=>'post_title', 'order'=>'asc' );

$investments = get_posts($invest_args);
if($investments) {  
   $cnt = 0;
   foreach ($investments as $invest) {
      $cnt ++;
      
      $invest_id  = $invest->ID;
      $fund       = get_field( "investment_fund", $invest_id);
      $company    = get_field( "investment_company", $invest_id);
      
      $partners   = get_field( "investment_partners", $invest_id);
      $value      = get_field( "enterprise_value", $invest_id);
      $desc       = get_field( "short_description", $company);
      $fund_state = get_field( "fund_state", $fund);
      $year       = get_field( "investment_year", $invest_id);
      $exit_year  = get_field( "exit_year", $invest_id);      
      
      if($exit_year) {$exit = "<br>EXIT YEAR: ".$exit_year;}
      
      $port_displ .= "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4 three-blocks no-padding-container company-blocks'>";
	   $port_displ .= "   <div class='invest-port-block-headers'>".get_the_title($invest_id)."</div>";
	   $port_displ .= "   <p class='home-block-sub-headers'>".$fund_state." INVESTMENT</p>";
      $port_displ .= "   <div class='home-block-divi'></div>";
      $port_displ .= "   <div class='single-investment-container'>";
      $port_displ .= "   <div class='blck-content-sheaderblc' style='margin: 0'>INVESTMENT YEAR:</div>";   
      $port_displ .= "   <div class='blck-content-sheaders'><strong>".$year."</strong></div>";      
      
      if($exit_year) {
         $port_displ .= "   <div class='blck-content-sheaderblc' style='margin: 0'>EXIT YEAR:</div>";   
         $port_displ .= "   <div class='blck-content-sheaders'><strong>".$exit_year."</strong></div>";      
      } //end if
      
	   $port_displ .= "   <div class='blck-content-sheaderblc' style='margin: 0'>BUSINESS DESCRIPTION:</div>";
	   $port_displ .= "   <div class='blck-content-sheaders'><strong>".$desc."</strong></div>";
	   $port_displ .= "   <div class='blck-content-sheaderblc' style='margin: 0'>INVESTMENT PARTNERS:</div>";
	   $port_displ .= "   <div class='blck-content-sheaders'><strong>".$partners."</strong></div>";
	   $port_displ .= "   <div class='blck-content-sheaderblc' style='margin: 0'>FUND:</div>";
	   $port_displ .= "   <div class='blck-content-sheaders'><strong>".get_the_title($fund)."</strong></div>";
	   $port_displ .= "   <div class='blck-content-sheaderblc' style='margin: 0'>ENTERPRISE VALUE:</div>";
	   $port_displ .= "   <div class='blck-content-sheaders'><strong>".$value."</strong></div>";
      $port_displ .= "   </div>";
	   $port_displ .= "   <a href='".get_permalink($invest_id)."'><div class='cool-link find-out-links' >VIEW PORTFOLIO</div></a>";
	   $port_displ .= "</div>";
         
      if (($cnt % 3) == 0) {
         $port_displ .= "<div class='col-sm-12'></div>";
      } //end if 
      
   } //end foreach
} //end if
?>
<!-- MAIN CONTENT -->
<div class="container-fluid three-blocks-container">
   <div class="container container-full ">
      <?php the_content(); ?>
   </div>
</div>   

<div class="container-fluid">
   <div class="row">
      <div class="container container-full page-container-top" style="padding: 80px 15px 50px 15px">
         <div class="desk-filter-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container"> 
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container">
                  <div class="row">	  	
                     <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="padding: 0">
                        <div class="lite-headers" style="padding-top: 20px">FILTER BY:</div>
                     </div>
                     <div class="col-xs-12 col-sm-4 col-md-4 col-lg-8" style="padding-right: 0;">
                        <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method='post'>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-5" style="padding-right: 0">
                           <select id="option" name="comp_search">
                              <option value="">COMPANY</option>
                              <?php echo $company_drop;?>
                           </select>
                        </div> 
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-5" style="padding-right: 0">
                           <select id="option" name="invest_status">
                              <option value="">INVESTMENT STATUS</option>
                              <option value="current" <?php if ($invest_status == "current") {echo "selected";}?>>CURRENT INVESTMENTS</option>
                              <option value="past" <?php if ($invest_status == "past") {echo "selected";}?>>PAST INVESTMENTS</option>
                           </select>
                        </div> 
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" style="padding-right: 0">
                           <button type="submit" value="submit" class="" style="width: 100%; color: #B3996B; background-color: #000;height: 48px;border: 0 solid #fff;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</button>
                        </div> 
                        </form>
                     </div> 
                     <div class="block-divi" style="margin-top: 70px"></div>
                  </div>
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
                  <select id="option" name="comp_search">
                     <option value="">COMPANY</option>
                     <?php echo $company_drop;?>
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


<div class="container-fluid">
   <div class="container container-full comms-outer-container">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container" style="padding: 0">
         <div class="row">
			<?php echo $port_displ; ?>
			</div>
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


<div class="container-fluid">
   <div class="container container-full" style="padding: 80px 15px 0 15px">
	 <div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container">
			<h2>THE ETHOS TEAM</h2>
		</div>
	  </div>      
	</div>
</div>

<div class="container-fluid">
   <div class="container container-full" style="padding: 80px 15px 0 15px">
	 <div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container">
			<?php if($page_links) {
				   if($post->post_parent == 34) {
					  page_link_div($page_links, 3);
				   } else {
					  page_link_div($page_links, 2);
				   }
				} 
			 ?>
		</div>
	  </div>      
	</div>
</div>
				
<?php
} //end if team
?>
<script>
   
jQuery(document).ready(function(){  
   
   var this_sel = jQuery('select[name=comp_search]').parent();
   var styledSelect = this_sel.find('div.select-styled');
   var SelectOptions = this_sel.find('ul.select-options');
   var numberOfOptions = SelectOptions.children('li').length;

   for (var i = 0; i < numberOfOptions; i++) {
      if (SelectOptions.children().eq(i).attr('rel') == "<?php echo $comp_search;?>") {
         styledSelect.text(SelectOptions.children().eq(i).text());
      } //end if
   } //end foreach

   
   
   var this_sel = jQuery('select[name=invest_status]').parent();
   var styledSelect = this_sel.find('div.select-styled');
   var SelectOptions = this_sel.find('ul.select-options');
   var numberOfOptions = SelectOptions.children('li').length;

   for (var i = 0; i < numberOfOptions; i++) {
      if (SelectOptions.children().eq(i).attr('rel') == "<?php echo $invest_status;?>") {
         styledSelect.text(SelectOptions.children().eq(i).text());
      } //end if
   } //end foreach

});  
   
</script>
<?php
get_footer(); 
?>