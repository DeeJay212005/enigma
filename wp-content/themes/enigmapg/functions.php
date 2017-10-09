<?php
#------------------------------------------------------------------------#
	// Register Custom Navigation Walker
	require_once('wp_bootstrap_navwalker.php');
	require_once('wp_sitemap_navwalker.php');
#------------------------------------------------------------------------#   
	//add menu support to theme
	add_theme_support( 'menus' );
	// add thumbnail support for posts
	add_theme_support( 'post-thumbnails' ); 
   // Add Menu_order to custom post types
   add_theme_support( 'page-attributes' ); 

   add_image_size('medium', get_option( 'medium_size_w' ), get_option( 'medium_size_h' ), true );
   add_image_size('large', get_option( 'large_size_w' ), get_option( 'large_size_h' ), true );

	register_nav_menu( 'header_menu' ,'Header Menu' );
	register_nav_menu( 'footer_menu' ,'Footer Menu' );
	register_nav_menu( 'footer_menu' ,'Footer Menu' );
	register_nav_menu( 'legal_menu' ,'Legal Menu' );
#------------------------------------------------------------------------#   
   //Add excerpts to Pages
	function my_add_excerpts_to_pages() {
      add_post_type_support( 'page', 'excerpt' );
	}   
   add_action( 'init', 'my_add_excerpts_to_pages' );   

#------------------------------------------------------------------------# 
   //Allow SVGs
   function cc_mime_types($mimes) {
     $mimes['svg'] = 'image/svg+xml';
     return $mimes;
   }
   add_filter('upload_mimes', 'cc_mime_types');
#------------------------------------------------------------------------#   	
   //repeater filter
   function my_posts_where( $where ) {

      $where = str_replace("meta_key = 'companies_%", "meta_key LIKE 'companies_%", $where);

      return $where;
   }

   add_filter('posts_where', 'my_posts_where');
#------------------------------------------------------------------------#   
 
   //Resolvesm HTTP Error on image upload
   function ms_image_editor_default_to_gd( $editors ) {
      $gd_editor = 'WP_Image_Editor_GD';
      $editors = array_diff( $editors, array( $gd_editor ) );
      array_unshift( $editors, $gd_editor );
      return $editors;
   }
   add_filter( 'wp_image_editors', 'ms_image_editor_default_to_gd' );
#------------------------------------------------------------------------#   

   //encrypts emails with shortcode [email] [/email]
   function wpdocs_hide_email_shortcode( $atts , $content = null ) {
       if ( ! is_email( $content ) ) {
           return;
       }
       return '<a href="mailto:' . antispambot( $content ) . '" class="email">' . antispambot( $content ) . '</a>';
   }
   add_shortcode( 'email', 'wpdocs_hide_email_shortcode' );

#------------------------------------------------------------------------#  
   //REWRITE
   function custom_rewrite_basic() {

      //Products section
      add_rewrite_rule('^private-equity/investment-portfolio/([^/]*)/?', 'index.php?page_id=82&thecatg=$matches[1]', 'top');
      add_rewrite_rule('^mezzanine/investment-portfolio/([^/]*)/?', 'index.php?page_id=140&thecatg=$matches[1]', 'top');
      add_rewrite_tag('%thecatg%','([^/]*)');
   }
   add_action('init', 'custom_rewrite_basic');

#------------------------------------------------------------------------# 
   //Twitter 
   function twitter_output() {
      
      $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
      
      $oauth_access_token = "243179650-omSqpjpdO3DpDKzVYSLcyezKQD7ymcTy4NKt7ncD";
      $oauth_access_token_secret = "u5x4wXJAS4cxCfROJBAgidw013qwqxMzoDRSnqoYkBbae";
      $consumer_key = "6AZ2eAAygzPFNFnpWiS4vAE89";
      $consumer_secret = "DnIyXxU7LfsSi9PF5SvqT5QO4THRLSYRbktwLqjVFOFTjtHoyX";

      $screen_name = 'EthosPvtEquity';
      $count = 20; // How many tweets to output
      $retweets = 0; // 0 to exclude, 1 to include  
      // First we populate an array with the parameters needed by the API
      $oauth = array(
         'count' => $count,
         'include_rts' => $retweets,
         'oauth_consumer_key' => $consumer_key,
         'oauth_nonce' => time(),
         'oauth_signature_method' => 'HMAC-SHA1',
         'oauth_timestamp' => time(),
         'oauth_token' => $oauth_access_token,
         'oauth_version' => '1.0'
      );

      $arr = array();
      foreach($oauth as $key => $val) {
         $arr[] = $key.'='.rawurlencode($val);
      }

      // Then we create an encypted hash of these values to prove to the API that they weren't tampered with during transfer
      $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', 'GET&'.rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json').'&'.rawurlencode(implode('&', $arr)), rawurlencode($consumer_secret).'&'.rawurlencode($oauth_access_token_secret), true));

      $arr = array();
      foreach($oauth as $key => $val) {
        $arr[] = $key.'="'.rawurlencode($val).'"';
      }

      // Next we use Curl to access the API, passing our parameters and the security hash within the call
      $tweets = curl_init();
      curl_setopt_array($tweets, array(
         CURLOPT_HTTPHEADER => array('Authorization: OAuth '.implode(', ', $arr), 'Expect:'),
         CURLOPT_HEADER => false,
         CURLOPT_URL => 'https://api.twitter.com/1.1/statuses/user_timeline.json?count='.$count.'&include_rts='.$retweets,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_SSL_VERIFYPEER => false,
      ));
      $json = curl_exec($tweets);
      curl_close($tweets);

      
      $return_arr = array();
      // Loop through them for output
      foreach(json_decode($json) as $status) {
         // Convert links back into actual links, otherwise they're just output as text
         $enhancedStatus = htmlentities($status->text, ENT_QUOTES, 'UTF-8');
         $enhancedStatus = preg_replace('/http:\/\/t.co\/([a-zA-Z0-9]+)/i', '<a href="http://t.co/$1">http://$1</a>', $enhancedStatus);
         $enhancedStatus = preg_replace('/https:\/\/t.co\/([a-zA-Z0-9]+)/i', '<a href="https://t.co/$1">http://$1</a>', $enhancedStatus);

         // Finally, output a simple paragraph containing the tweet and a link back to the Twitter account itself. You can format/style this as you like.

          $return_arr[] = "&quot;".html_entity_decode($enhancedStatus)."&quot;<br /><a href='https://twitter.com/intent/user?screen_name=".$screen_name."'>@".$screen_name."</a>";

      } //end foreach
      
      return $return_arr;
         
   } //end function
#------------------------------------------------------------------------#   

	add_shortcode('team_member', 'member_output');
   // [team_member name='STUART MACKENZIE']   
	function member_output($atts){ 

      $a = shortcode_atts( array('name' => ''), $atts );
      $member = get_page_by_title($a['name'], OBJECT, 'team_members');
      
      if($member) {
         
         $member_id = $member->ID;
         $designation = get_field( "designation",$member_id);
         
         $rollover   = get_field("rollover",$member_id);
         $rollimage  = wp_get_attachment_image_src($rollover, "thumbnail");
         $roll_img   = $rollimage[0];         

         $output .= "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container cord-content-container'>";
         $output .= "   <div class='col-xs-3 col-sm-3 col-md-3 col-lg-3 cord-content-img-container'>";
         $output .= "      <img src='".$roll_img."' >";
         $output .= "   </div>";
         $output .= "   <div class='col-xs-9 col-sm-9 col-md-9 col-lg-9 no-padding-container cord-content'>";
         $output .= "      <h4>".get_the_title($member_id)."</h4>";
         $output .= "      <p>".$designation."</p>";
         $output .= "      <div class='team-divider' style='margin-bottom:10px'></div>";
         $output .= "      <a href='".get_permalink($member_id)."'>";
         $output .= "         <div class='cool-link find-out-links' style='margin-top:8px;'>VIEW PROFILE</div>";
         $output .= "      </a>";
         $output .= "   </div>";
         $output .= "</div>";  
         
      }
      
      return $output;	
      
	} //end function

#------------------------------------------------------------------------#    
function home_main_block($block){

   $link_title = $block['link_title'];
   if(!$link_title) {$link_title = "FIND OUT MORE";}
   
   echo "<div class='home-block-headers'>".$block['block_title']."</div>";
   echo "<p class='home-block-sub-headers'>".$block['block_sub_title']."</p>";
   echo "<div class='home-block-divi'></div>";
   echo "<div class='home-block-content'>".$block['block_content']."</div>";
   echo "<a href='".$block['block_link']."' target='_blank'><div class='cool-link find-out-links' >".$link_title."</div></a>";
   
} //end function
#------------------------------------------------------------------------#
function home_blocks($blocks){
   
   $item_cnt = count($blocks);
   
   if($item_cnt >= 5)  {
      $items_per_row = "4";
   } else {             
      if($item_cnt == 1){$items_per_row = 12;$one_style = " style='min-height:0;'";}
      if($item_cnt == 2){$items_per_row = 6;}
      if($item_cnt == 3){$items_per_row = 4;}
      if($item_cnt == 4){$items_per_row = 6;}
   } //end if*/   
   
   foreach ($blocks as $block) {
      
      $post_id          = $block['block_link'];
      
      $block_title      = $block['block_title'];
      $block_sub_title  = $block['block_sub_title'];
      $block_content    = $block['block_content'];
      $link_title       = $block['link_title'];

      $block_content    = preg_replace(" (\[.*?\])",'',$block_content);
      $block_content    = strip_shortcodes($block_content);
      $block_content    = strip_tags($block_content);
      $block_content    = trim(preg_replace( '/\s+/', ' ', $block_content));   
      
      $info = get_post($post_id); 
      
      
      if (!$block_title)                  {$block_title   = get_the_title($post_id);}
      if (!$block_sub_title)              {$block_sub_title     = get_field("page_subtitle",$post_id);}
      if (!$block_content)                {$block_content = $info->post_excerpt;}
      if (strlen($block_content) > 130)   {$block_content = substr($block_content, 0, 130)." [...]";}                                        
      if (!$link_title)                   {$link_title = "FIND OUT MORE";}                                        
                                        
                                        
      echo "<div class='col-xs-12 col-sm-6 col-md-".$items_per_row." col-lg-".$items_per_row." home-top-blocks no-padding-container'>";
      echo "   <div class='home-block-headers'>".$block_title."</div>";
      echo "   <p class='home-block-sub-headers'>".$block_sub_title."</p>";
      echo "   <div class='home-block-divi'></div>";
      echo "   <div class='home-block-content'>".$block_content."</div>";
      echo "   <a href='".get_permalink($post_id)."'><div class='cool-link find-out-links' >".$link_title."</div></a>";
      echo "</div>";
      
   } //end foreach
} //end function
#------------------------------------------------------------------------#
function header_block($post) {
   
   $post_id = $post->ID;
   
   $subtitle   = get_field("page_subtitle",$prod_id);
   $excerpt    = get_field("header_excerpt",$prod_id);
   $link_text  = get_field("link_text",$prod_id);
   $page_link  = get_field("page_link",$prod_id);
   
   $header_links  = get_field("header_links",$prod_id);
   
   
   
   if (!$feat_img) {$feat_img = get_template_directory_uri()."/img/pagebanner.png";}
   
   $header .= "<div class='page-break-mid' style='width:100%;margin:0;overflow:hidden;position:relative;'>";
   $header .= "   <img src='".$feat_img."' style='top:0;margin:0;padding:0;position:absolute;width:100%;'>";    
   $header .= "</div>";
   $header .= "<div class='container-fluid' style='background-color: #000000;'>";
   $header .= "   <div class='container container-full no-padding-container'>";
   $header .= "      <div class='home-about-ethos-container header-block-no-padding'>";
   $header .= "         <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container header-block-no-padding'>";
   $header .= "            <div class=''>";
   $header .= "               <div class='top-content-block'>";
   
   if (function_exists('yoast_breadcrumb')) {
      $header .= yoast_breadcrumb("<p id='breadcrumbs'>","</p>",false);
   } //end if
   
   $header .= "                  <h1>".get_the_title($post_id)."</h1>";
   $header .= "                  <h2 class='home-about-block-sub-heading'>".$subtitle."</h2>";
   $header .= "                  <div class='block-divi'></div>";
   $header .= "                  <p style='margin-bottom: 30px;'>".$excerpt."</p>";
   
   if($page_link) {
      $header .= "                  <a href='".$page_link."'>";
      $header .= "                     <div class='cool-link find-out-links banner-content-block-alink banner-contact-link-l' style='float:left;'>".$link_text."</div>";
//      $header .= "                     <div class='cool-link find-out-links banner-content-block-alink banner-contact-link-m' style='float:left;'>View More</div>";
      $header .= "                     <div class='head-white-dot' style='float:left;'>";
      $header .= "                        <div class='head-black-dot'>";
      $header .= "                        <img src='".get_template_directory_uri()."/icons/arrow.svg'>";
      $header .= "                     </div></div>";          
      $header .= "                  </a>";   
   } //end if
   
   if($header_links) {
      foreach($header_links as $header_link) {
         
         $link       = $header_link['page_link'];
         $link_text  = $header_link['link_text'];
         
         $header .= "                  <a href='".$link."'>";
         $header .= "                     <div class='cool-link find-out-links banner-content-block-alink' style='float:left;clear:both'>".$link_text."</div>";
         $header .= "                  </a>";  
         
      } //end foreach   
   } //end if   
   
   $header .= "               </div>";
   $header .= "            </div>";
   $header .= "         </div>";
   $header .= "      </div>";
   $header .= "   </div>";
   $header .= "</div>";   

   echo $header;
   
   
} //end function
#------------------------------------------------------------------------#
function accord_displ($accordian){
   echo "<div class='container-fluid' style='padding:0;'>";
   echo "   <div class='container accord-content-container'>";
   
   $cnt = 0;
   foreach ($accordian as $accordian_set) { 
      $cnt ++;
      $active = "";
      if ($cnt == 1) {$active = "active";}
      
      $acc_header = $accordian_set["accordian_header"];
      $acc_content = $accordian_set["accordian_content"];               
      $acc_blocks = $accordian_set["accordian_content_blocks"];               

      $output .= "<button class='accordion ".$active."' style='font-weight: 600;'>".$acc_header."</button>";
      $output .= "<div class='panel' style='padding-top:10px;'>";
      $output .=     apply_filters('the_content', $acc_content);
                     
      if($acc_blocks) {
         foreach ($acc_blocks as $acc_block) {
            
            $acb_title  = $acc_block['title'];
            $acb_sub    = $acc_block['sub_title'];
             
            $acb_img_id = $acc_block['feat_img'];
            $image      = wp_get_attachment_image_src($acb_img_id, "full");
            $acb_img    = $image[0];             

            $acb_cont   = do_shortcode($acc_block['content']);
             
            
            $output .= "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container cord-content-container'>";
            $output .= "   <div class='col-xs-2 col-sm-2 col-md-2 col-lg-2 cord-content-img-container'>";
            $output .= "      <img src='".$acb_img."' >";
            $output .= "   </div>";
            $output .= "   <div class='col-xs-10 col-sm-10 col-md-10 col-lg-10 no-padding-container cord-content'>";
            $output .= "       <h4>".$acb_title."</h4>";
            $output .= "       <p>".$acb_sub."</p>";
            $output .= "       <div class='team-divider'></div>";
            $output .= $acb_cont;
            $output .= "   </div>";
            $output .= "</div>";            

         } //end foreach
      } //end if
      
      $output .= "</div>";    

   } //end foreach
   echo $output;   
   
   echo "   </div>";
   echo "</div>";   
}
#------------------------------------------------------------------------#    
function page_link_div($page_links) {
   
   $item_cnt = count($page_links);
   
   if($item_cnt >= 5)  {
      $items_per_row = "4";
   } else {             
      if($item_cnt == 1){$items_per_row = 12;$one_style = " style='min-height:0;'";}
      if($item_cnt == 2){$items_per_row = 6;}
      if($item_cnt == 3){$items_per_row = 4;}
      if($item_cnt == 4){$items_per_row = 6;}
   } //end if*/
   
   echo "<div class='container-fluid page-link-margin' style=''>";
   echo "   <div class='container' style=''>";
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
      
      
      echo "   <div class='col-xs-12 col-sm-".$items_per_row." col-md-".$items_per_row." col-lg-".$items_per_row." no-padding-container company-blocks'>";
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
   
}
#------------------------------------------------------------------------#    
function team_display($team_id) {
   
   $meta_query = array(array('key' => 'team','value' => '"'.$team_id.'"','compare' => 'LIKE'));
   
   
   $team_args = array("post_type"=>'team_members', 'meta_query' => $meta_query, "posts_per_page"=>"4", 'orderby'=>'rand', 'order'=>'asc');
   $team_members = get_posts($team_args);   
   
   echo "<div class='container no-padding-container team-container comms-wrap-container' style='padding: 0;'>";
   echo "   <div class='container no-padding-container'>";
   echo "      <div class='col-xs-12'>";
   echo "         <div class='row'>";
   echo "            <div class='col-xs-12 col-sm-12 col-md-8 col-lg-8' style='padding: 0;background-color: #252525;'>";
   foreach ($team_members as $team_member) {
      
      $post_id = $team_member->ID;

      $thumb_id   = get_field("thumbnail_image",$post_id);
      $image      = wp_get_attachment_image_src($thumb_id, "thumbnail");
      $feat_img   = $image[0];      
      
      $rollover   = get_field("rollover",$post_id);
      $rollimage  = wp_get_attachment_image_src($rollover, "thumbnail");
      $roll_img   = $rollimage[0];
      
      if (!$feat_img) {$feat_img = get_template_directory_uri()."/img/thumbnail.jpg";}
      if (!$roll_img) {$roll_img = get_template_directory_uri()."/img/thumbnail.jpg";}
      
      if($post_id == "637") {
         $first_displ .= "      <div class='thumb-block-image col-xs-3 col-sm-3 col-md-3 col-lg-3' style='float:right; padding: 0px;'>";
         $first_displ .= "         <a href='".get_permalink($post_id)."'>";
         $first_displ .= "            <img class='bwimage' src='".$feat_img."' style='width:100%'>";
         $first_displ .= "            <img class='rollover' src='".$roll_img."' style='width:100%' alt='".get_the_title($post_id)."' title='".get_the_title($post_id)."'>";
         $first_displ .= "         </a>";
         $first_displ .= "      </div>";
      } else {
         $team_displ .= "      <div class='thumb-block-image col-xs-3 col-sm-3 col-md-3 col-lg-3' style='float:right; padding: 0px;'>";
         $team_displ .= "         <a href='".get_permalink($post_id)."'>";
         $team_displ .= "            <img class='bwimage' src='".$feat_img."' style='width:100%'>";
         $team_displ .= "            <img class='rollover' src='".$roll_img."' style='width:100%' alt='".get_the_title($post_id)."' title='".get_the_title($post_id)."'>";
         $team_displ .= "         </a>";
         $team_displ .= "      </div>";
      }      
      
      
   } //end foreach
   
   
   echo $first_displ.$team_displ;
   echo "            </div>";
   echo "            <div class='no-padding-container mid-block col-xs-12 col-sm-12 col-md-4 col-lg-4 team-content-block' style='padding-right: 0;'>";
  // echo "               <div class='row'>";
   echo "               <div class='mid-block-header'>THE ETHOS TEAM</div>";
   echo "               <p class='mid-block-sub-headers'>EXEPTIONAL, MULTI-DISIPLINARY EXPERTISE </p>";
   echo "               <div class='home-block-divi'></div>";
   echo "               <a href='".get_permalink($team_id)."'>";
   echo "                  <div class='cool-link find-out-links view-team-buttom-l' style='color: #b3996b; font-weight: 600;text-transform: uppercase;letter-spacing: 2px;'>VIEW THE ".get_the_title($team_id)."</div>";
   echo "                  <div class='cool-link find-out-links view-team-buttom-m' style='color: #b3996b; font-weight: 600;text-transform: uppercase;letter-spacing: 2px;'>VIEW THE ".get_the_title($team_id)."</div>";
   echo "               </a>";
   echo "            </div>";
   echo "         </div>";
   echo "      </div>";
   echo "   </div>";
   echo "</div>";
   
}
#------------------------------------------------------------------------#   
function get_fund_companies($funds,$comp_search) {
   
   $tax_query['relation'] = 'OR';
   foreach ($funds as $fund) {
      $tax_query[] = array(array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $fund->slug));
   } //end foreach
   
   // get associated funds
   $fund_args = array("post_type" => 'funds',  'tax_query'=>$tax_query, "posts_per_page" => "-1", 'orderby'=>'post_date', 'order'=>'asc' );

   $funds = get_posts($fund_args);
   if($funds) {  
      $fund_arr = array();
      foreach ($funds as $fund) {
         $fund_arr[] = $fund->ID;
      } //end foreach
   } //end if

   $meta_query = array(array('key' => 'investment_fund','value' => $fund_arr,'compare' => 'IN'));
   $invest_args = array("post_type"=>'invest_portfolio', 'meta_query'=> $meta_query, "posts_per_page"=>"-1", 'orderby'=>'post_title', 'order'=>'asc' );
   
   $investments = get_posts($invest_args);
   if($investments) {  
      $company_drop = "";
      foreach ($investments as $invest) {
         $invest_id  = $invest->ID;
         $company    = get_field( "investment_company", $invest_id);
         
         if($company) {
            if($comp_search == $company) {
               $company_drop .= "<option value='".$company."' selected>".strtoupper(get_the_title($company))."</option>";
            } else {
               $company_drop .= "<option value='".$company."'>".strtoupper(get_the_title($company))."</option>";
            }
         } //end if
      } //end foreach
   } //end if   
   return $company_drop;
}
#------------------------------------------------------------------------#   
function get_invest_drop($fund_types,$deal_invest) {
   
   $tax_query['relation'] = 'OR';
   foreach ($fund_types as $fund_type) {
      $tax_query[] = array(array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $fund_type->slug));
   } //end foreach
   
   // get associated funds
   $fund_args = array("post_type" => 'funds',  'tax_query'=>$tax_query, "posts_per_page" => "-1", 'orderby'=>'post_date', 'order'=>'asc' );
   $funds = get_posts($fund_args);
   if($funds) {  
      $fund_arr = array();
      foreach ($funds as $fund) {
         $fund_arr[] = $fund->ID;
      } //end foreach
   } //end if

   $meta_query = array(array('key' => 'investment_fund','value' => $fund_arr,'compare' => 'IN'));
   $invest_args = array("post_type"=>'invest_portfolio', 'meta_query'=> $meta_query, "posts_per_page"=>"-1", 'orderby'=>'post_title', 'order'=>'asc' );
   
   $investments = get_posts($invest_args);
   if($investments) {  
      $invest_drop = "";
      foreach ($investments as $invest) {
         $invest_id  = $invest->ID;

         if($deal_invest == $invest_id) {
            $invest_drop .= "<option value='".$invest_id."' selected>".strtoupper(get_the_title($invest_id))."</option>";
         } else {
            $invest_drop .= "<option value='".$invest_id."'>".strtoupper(get_the_title($invest_id))."</option>";
         } //end if
         
      } //end foreach
   } //end if   
   return $invest_drop;
}
#------------------------------------------------------------------------#   
function get_fund_drop($fund_types,$deal_fund) {
   
   $tax_query['relation'] = 'OR';
   foreach ($fund_types as $fund_type) {
      $tax_query[] = array(array('taxonomy' => 'fund_type','field' => 'slug', 'terms' => $fund_type->slug));
   } //end foreach
   
   // get associated funds
   $fund_args = array("post_type" => 'funds',  'tax_query'=>$tax_query, "posts_per_page" => "-1", 'orderby'=>'post_title', 'order'=>'asc' );
   $funds = get_posts($fund_args);
   if($funds) {  
      foreach ($funds as $fund) {
         $fund_id = $fund->ID;
         if($deal_fund == $fund_id) {
            $fund_drop .= "<option value='".$fund_id."' selected>".strtoupper(get_the_title($fund_id))."</option>";
         } else {
            $fund_drop .= "<option value='".$fund_id."'>".strtoupper(get_the_title($fund_id))."</option>";
         } //end if         
         
      } //end foreach
   } //end if

   return $fund_drop;
}
#------------------------------------------------------------------------#  
function get_year_drop($deal_year) {
   
   $fund_drop = "";
   for($i=date("Y"); $i>=1984; $i--) {
      if($deal_year == $i) {
         $year_drop .= "<option value='".$i."' selected>".$i."</option>";
      } else {
         $year_drop .= "<option value='".$i."'>".$i."</option>";
      } //end if       
   } //end if
   return $year_drop;
}
#------------------------------------------------------------------------#  
function next_prev_display($next_id, $prev_id, $fundtype = '') {
            
   $np_dspl .="<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12' style='padding:50px 0 50px 0;'>";
   $np_dspl .="   <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6' style='padding:0;'>";

   if ($next_id) {

      $np_dspl .="      <a href='".get_permalink($next_id)."'>";
      $np_dspl .="       <div class='head-linking'>";
      $np_dspl .="         <div class='artical-dot' style='float:left;margin: 6px 20px 0 0px;'>";
      $np_dspl .="            <div class='articalb-dot'>";
      $np_dspl .="               <img src='".get_template_directory_uri()."/icons/arrow.svg' style='transform:rotate(-180deg);margin: 8px 0 10px 9px;'>";
      $np_dspl .="            </div>";
      $np_dspl .="         </div>";            
      $np_dspl .="         <div class='cool-link find-out-links banner-content-block-alink banner-contact-link-l' style='float:left;'>Next </div>";
      $np_dspl .="         <div class='cool-link find-out-links banner-content-block-alink banner-contact-link-m' style='float:left;'>Next </div>";
      $np_dspl .="       </div>";
      $np_dspl .="      </a>";

   }

   $np_dspl .="   </div>";
   $np_dspl .="   <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6 ' style='padding:0;'>";
   $np_dspl .="      <div style='float:right;'>";

   if ($prev_id) {

      $np_dspl .="      <a href='".get_permalink($prev_id)."'>";
      $np_dspl .="       <div class='head-linking'>";
      $np_dspl .="         <div class='cool-link find-out-links banner-content-block-alink banner-contact-link-l' style='float:left;'>Prev </div>";
      $np_dspl .="         <div class='cool-link find-out-links banner-content-block-alink banner-contact-link-m' style='float:left;'>Prev </div>";
      $np_dspl .="         <div class='artical-dot' style='float:left;margin: 6px 0 0 20px;'>";
      $np_dspl .="            <div class='articalb-dot'>";
      $np_dspl .="               <img src='".get_template_directory_uri()."/icons/arrow.svg'>";
      $np_dspl .="            </div>";
      $np_dspl .="         </div>";
      $np_dspl .="       </div>";
      $np_dspl .="      </a>";

   }
   $np_dspl .="      </div>";
   $np_dspl .="   </div>";
   $np_dspl .="</div>";

   return $np_dspl;
   
}
#------------------------------------------------------------------------#  
