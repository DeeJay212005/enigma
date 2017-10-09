<?php
/*
Template Name: News
*/
get_header(); 
header_block($post);
$post_id = $post->ID;
$terms = get_field( "fund_type", $post_id);
#-------------------Change request values to php variables---------------#
foreach ($_REQUEST as $key => $value) {
   if (!is_array ($value)) {
      $$key = trim($value);
   }
}//next
#------------------------------------------------------------------------#
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   $news_type     = preg_replace("/[^A-Za-z0-9 -_]/", "", $news_type); 
	$news_catg     = preg_replace("/[^A-Za-z0-9 -_]/", "", $news_catg); 
   $news_year     = preg_replace("/[^0-9 ]/", "", $news_year); 
   
   $news_tax_query = array();
   $news_tax_query['relation'] = 'AND';
   if ($news_type != "")    {$news_tax_query[]= array('taxonomy' => 'news_type','terms' => $news_type,'field' => 'slug');}
   
   if ($news_catg != "")    {
      $news_tax_query[]= array('taxonomy' => 'fund_type','terms' => $news_catg,'field' => 'slug');
   } else {
      
      $tax_query['relation'] = 'OR';
      foreach ($terms as $term) {
         $tax_query[] = array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $term->slug);
      } //end foreach
      $news_tax_query[] = $tax_query;
   }
   
} else {
	$news_type = "";
	$news_catg = "";
	$news_year = "";
   $news_tax_query = array();

   $news_tax_query['relation'] = 'OR';
   foreach ($terms as $term) {
      $news_tax_query[] = array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $term->slug);
   }   
   
}//end if
#------------------------------------------------------------------------# 
/*
if (($news_type)||($news_catg)||($news_year)) {
	
   $err = 0;
   $msg = "";
                  
   
   $news_type     = preg_replace("/[^A-Za-z0-9 -_]/", "", $news_type); 
	$news_catg     = preg_replace("/[^A-Za-z0-9 -_]/", "", $news_catg); 
   $news_year     = preg_replace("/[^0-9 ]/", "", $news_year); 
   
   $news_tax_query = array();
   $news_tax_query['relation'] = 'AND';
   
   if ($news_type != "")    {$news_tax_query[]= array('taxonomy' => 'news_type','terms' => $news_type,'field' => 'slug');}
   if ($news_catg != "")    {$news_tax_query[]= array('taxonomy' => 'fund_type','terms' => $news_catg,'field' => 'slug');}

   
} else {
	$news_type = "";
	$news_catg = "";
	$news_year = "";
   $news_tax_query = array();
}//end if
*/
#------------------------------------------------------------------------#  
//GET INVESTMENTS, AND FUNDS FOR DROPDOWNS
$year_drop     = get_year_drop($news_year);

$types = get_terms(array('taxonomy' => 'news_type', 'hide_empty' => false));
foreach($types as $type) {
   if($news_type == $type->slug) {
      $type_drop .= "<option value='".$type->slug."' selected>".strtoupper($type->name)."</option>";
   } else {
      $type_drop .= "<option value='".$type->slug."'>".strtoupper($type->name)."</option>";
   } //end if
} //end foreach


foreach($terms as $term) {
   if($news_catg == $term->slug) {
      $catg_drop .= "<option value='".$term->slug."' selected>".strtoupper($term->name)."</option>";
   } else {
      $catg_drop .= "<option value='".$term->slug."'>".strtoupper($term->name)."</option>";
   } //end if
} //end foreach

#------------------------------------------------------------------------# 



$news_args = array("post_type" => 'news', 'tax_query' => $news_tax_query, "posts_per_page" => "-1",'date_query'=>array('year'=>$news_year), 'orderby'=>'post_date', 'order'=>'Desc' );

$articles = get_posts($news_args);
if($articles) {  
   $cnt = 0;
   foreach ($articles as $news) {
      $cnt ++;
      
      $news_id    = $news->ID;   
      $news_source   = get_field("source",$news_id);
      if ($news_source) {$news_source .= ", ";}
      $src_date   = get_field("source_date",$news_id);
      $excerpt    = $news->post_excerpt;

      $thumb_id   = get_field("listing_image",$news_id);
      $image      = wp_get_attachment_image_src($thumb_id, "full");
      $feat_img   = $image[0];      
      
      $news_displ .= "<a href='".get_permalink($news_id)."'>";
      $news_displ .= "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container comms-wrap-container' style='padding:0;margin:10px 0;background:#000000;'>";
      if ($feat_img) {
         $news_displ .= "   <div class='col-xs-12 col-sm-12 col-md-6 col-lg-4' style='padding:0;'>";
         $news_displ .= "      <img src='".$feat_img."'  style='width: 100%;' alt='".get_the_title($news_id)."' title='".get_the_title($news_id)."'>";
         $news_displ .= "   </div>";
         $news_displ .= "   <div class='col-xs-12 col-sm-12 col-md-6 col-lg-8' style='padding:40px;'>";
      } else {
         $news_displ .= "   <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='padding:40px;'>";         
      }
      $news_displ .= "      <div class='comms-block-header'>".get_the_title($news_id)."</div>";
      $news_displ .= "      <p class='comms-block-sub-header'>".$news_source.date("d M Y",strtotime($src_date))."</p>";
      $news_displ .= "      <div class='home-block-divi'></div>";      
      $news_displ .= "      <div class='comms-block-content'>".nl2br($excerpt)."</div>";
      $news_displ .= "      <div class='cool-link find-out-links' >READ MORE</div>";
      $news_displ .= "   </div>";
      $news_displ .= "</div>";
      $news_displ .= "</a>";
         
      
    
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
                  <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9" style="padding-right: 0">
                     <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method='post'>         
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-right: 0">
                           <select id="option" name="news_type">
                              <option value="">NEWS TYPE</option>
                              <?php echo $type_drop; ?>
                           </select>
                        </div> 
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-right: 0">
                           <select id="option" name="news_catg">
                              <option value="">CATEGORY</option>
                              <?php echo $catg_drop; ?>
                           </select>
                        </div> 
                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2" style="padding-right: 0">
                           <select id="option" name="news_year">
                              <option value="">YEAR</option>
                              <?php echo $year_drop; ?>
                           </select>
                        </div> 		 
                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2" style="padding-right: 0">
                           <button type="submit" value="submit" class="" style="width: 100%; color: #B3996B; background-color: #000;height: 48px;border: 0 solid #fff;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</button>
                        </div> 
                     </form>		                    
                  </div> 	                    
                  <div class="block-divi-comms"></div>
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
               <select id="option" name="news_type">
                  <option value="">NEWS TYPE</option>
                  <?php echo $type_drop; ?>
               </select>
		      </div> 
            <div class="dropdown-mobile" style="width: 100%;margin-bottom: 20px">
               <select id="option" name="news_catg">
                  <option value="">CATEGORY</option>
                  <?php echo $catg_drop; ?>
               </select>
            </div> 
            <div class="dropdown-mobile" style="width: 100%;margin-bottom: 20px">   	
               <select id="option" name="news_year">
                  <option value="">YEAR</option>
                  <?php echo $year_drop; ?>
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
      <?php echo $news_displ; ?>
	</div>
</div>  
<script>
   
jQuery(document).ready(function(){  
   
   var this_sel = jQuery('select[name=news_type]').parent();
   var styledSelect = this_sel.find('div.select-styled');
   var SelectOptions = this_sel.find('ul.select-options');
   var numberOfOptions = SelectOptions.children('li').length;

   for (var i = 0; i < numberOfOptions; i++) {
      if (SelectOptions.children().eq(i).attr('rel') == "<?php echo $news_type;?>") {
         styledSelect.text(SelectOptions.children().eq(i).text());
      } //end if
   } //end foreach

   
   var this_sel = jQuery('select[name=news_catg]').parent();
   var styledSelect = this_sel.find('div.select-styled');
   var SelectOptions = this_sel.find('ul.select-options');
   var numberOfOptions = SelectOptions.children('li').length;

   for (var i = 0; i < numberOfOptions; i++) {
      if (SelectOptions.children().eq(i).attr('rel') == "<?php echo $news_catg;?>") {
         styledSelect.text(SelectOptions.children().eq(i).text());
      } //end if
   } //end foreach   
   
   
   var this_sel = jQuery('select[name=news_year]').parent();
   var styledSelect = this_sel.find('div.select-styled');
   var SelectOptions = this_sel.find('ul.select-options');
   var numberOfOptions = SelectOptions.children('li').length;

   for (var i = 0; i < numberOfOptions; i++) {
      if (SelectOptions.children().eq(i).attr('rel') == "<?php echo $news_year;?>") {
         styledSelect.text(SelectOptions.children().eq(i).text());
      } //end if
   } //end foreach

});  
   
</script>


<?php
get_footer(); 
?>