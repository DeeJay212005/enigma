<?php
/*
Template Name: Communications Main
*/
get_header(); 
header_block($post);
$post_id = $post->ID;
$term = get_field("news_type",$post_id)[0];
#-------------------Change request values to php variables---------------#
foreach ($_REQUEST as $key => $value) {
   if (!is_array ($value)) {
      $$key = trim($value);
   }
}//next
#------------------------------------------------------------------------#
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   $news_type     = preg_replace("/[^A-Za-z0-9 -_]/", "", $news_type); 
   $news_year     = preg_replace("/[^0-9 ]/", "", $news_year); 
   
   $news_tax_query = array();
   $news_tax_query['relation'] = 'AND';
   if ($news_type != "")    {$news_tax_query[]= array('taxonomy' => 'news_type','terms' => $news_type,'field' => 'slug');}
   
} else {
	$news_type = "";
	$news_year = "";
   $news_tax_query = array();
   
   if($term) {
      $news_type = $term->slug;
      $news_tax_query['relation'] = 'AND';
      if ($news_type != "")    {$news_tax_query[]= array('taxonomy' => 'news_type','terms' => $news_type,'field' => 'slug');}
   }//end if   
   
   
}//end if
#------------------------------------------------------------------------# 
//GET INVESTMENTS, AND FUNDS FOR DROPDOWNS
$year_drop     = get_year_drop($news_year);

$types = get_terms(array('taxonomy' => 'news_type', 'hide_empty' => true));
foreach($types as $type) {
   if($news_type == $type->slug) {
      $type_drop .= "<option value='".$type->slug."' selected>".strtoupper($type->name)."</option>";
   } else {
      $type_drop .= "<option value='".$type->slug."'>".strtoupper($type->name)."</option>";
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
      if (strlen($excerpt) > 250) {$excerpt = substr($excerpt, 0, 250)." [...]";}
      
      $page_logo  = get_field("page_logo",$news_id);
      $image = wp_get_attachment_image_src($page_logo, "full");
      $logo_img = $image[0];        
      
      $thumb_id   = get_field("listing_image",$news_id);
      $image      = wp_get_attachment_image_src($thumb_id, "full");
      $feat_img   = $image[0];      
      
      if (!$feat_img) {$feat_img = get_template_directory_uri()."/img/Communications-Thumbnail.jpg";}
      
      $news_displ .= "<a href='".get_permalink($news_id)."'>";
      $news_displ .= "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container comms-wrap-container' style='padding:0;margin:10px 0;background:#000000;'>";
      $news_displ .= "   <div class='comms-single-image col-xs-12 col-sm-3 col-md-3 col-lg-3' style='padding:0;'>";
      $news_displ .= "      <img class='comms-image-width' src='".$feat_img."'  style='width: 100%;' alt='".get_the_title($news_id)."' title='".get_the_title($news_id)."'>";
      if($logo_img) {
         $news_displ .= "      <div class='comms-image-logo-width' style='position:absolute;bottom:10px;right:10px;padding:0px;width:120px;background:#FFFFFF;'>";
         $news_displ .= "         <img src='".$logo_img."' style='width:100%;'>";
         $news_displ .= "      </div>";
      } //end if      
      $news_displ .= "   </div>";
      $news_displ .= "   <div class='col-xs-12 col-sm-12 col-md-8 col-lg-9 all-comms-blocks' style=''>";
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
<div class="container-fluid" style="">
   <div class="container container-full page-container-top all-comms-page-container-top" style="">
     
      <div class="desk-filter-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container">  
         <div class="">   
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container">	  	
               <div class="row">	  	
                  <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" style="padding: 0">
                     <div class="lite-headers" style="padding-top: 20px">FILTER BY:</div>
                  </div>
                  <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9" style="padding-right: 0">
                     <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method='post'>         
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-right: 0"></div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-right: 0">  
                           <select id="option" name="news_type">
                              <option value="">NEWS TYPE</option>
                              <?php echo $type_drop; ?>
                           </select>
                        </div> 
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="padding-right: 0">
                           <select id="option" name="news_year">
                              <option value="">YEAR</option>
                              <?php echo $year_drop; ?>
                           </select>
                        </div> 		 
                     </form>		                    
                  </div> 	                    
                  <div class="block-divi-comms comms-block-divi-mobile"></div>
               </div>
            </div>
         </div>
		</div>
           
      <div class="comms-mobile-filter-btn col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container">
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container" style="padding: 0;">	  	
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 pull-right" style="padding: 0">
               <button id="filterbutton" style="width: 100%; color: #B3996B; background-color: #000;height: 48px;border: 0 solid #fff;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER </button>
            </div>	   	
            <div class="block-divi-comms comms-block-divi-mobile" style="margin-top: 70px"></div>
         </div>
      </div>	
      	 
   </div>
</div>  

<div class="modal custom fade" id="filterby" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog" style='margin-top:50px;'>
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
               <select id="option" name="news_year">
                  <option value="">YEAR</option>
                  <?php echo $year_drop; ?>
               </select> 
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