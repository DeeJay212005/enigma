<?php

get_header(); 

$post_id = $post->ID;

$page_subtitle = get_field("page_subtitle",$prod_id);
$page_link  = get_field("page_link",$prod_id);
$accordian = get_field( "accordian", $post_id);

$reg_num       = get_field( "reg_num", $post_id);
$share_code    = get_field( "share_code", $post_id);
$switchboard   = get_field( "switchboard", $post_id);
$address       = get_field( "address", $post_id);
$gps           = get_field( "gps", $post_id);
$disclaimer    = get_field( "disclaimer", $post_id);
$_map          = get_field( "embedded_map", $post_id);
$enquiries     = get_field( "enquiries", $post_id);

$header .= "<div class='page-break-mid' style='width:100%;margin:0;'></div>";
$header .= "   <div class='container-fluid' style='background-color: #000000;'>";
$header .= "      <div class='container'>";
$header .= "         <div class=''>";
$header .= "            <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container' style=''>";
$header .= "               <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container' style='padding-left: 0;'>";
$header .= "                  <div class='top-contact-content-block'>";
if (function_exists('yoast_breadcrumb')) {
   $header .= yoast_breadcrumb("<p id='breadcrumbs'>","</p>",false);
} //end if
$header .= "                     <h1>".get_the_title($post_id)."</h1>";
$header .= "                     <h2 class='home-about-block-sub-heading'>".$page_subtitle."</h2>";
$header .= "                     <div class='block-divi'></div>";
$header .= "                  </div>";
$header .= "               </div>";
$header .= "               <div class='col-xs-12 col-sm-6 col-md-5 col-lg-4 no-padding-container' style='padding-left: 0;'>";
$header .= "                  <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6' style='padding:0'>";
$header .= "                     <p class='contact-reg'><b>REGISTRATION NO.</b><br>".$reg_num."</p>";
$header .= "                  </div>";
$header .= "                  <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6' style='padding:0'>";
$header .= "                     <p class='contact-reg'><b>SHARE CODE:</b><br>".$share_code."</p>";
$header .= "                  </div>";
$header .= "               </div>";
$header .= "               <div class='col-sm-6 col-md-7 pull-right  contact-map-l' style='padding:0;margin-bottom:-410px;margin-top:0;'>";
$header .= $_map;
$header .= "               </div>";
$header .= "            </div>";
$header .= "         </div>";
$header .= "      </div>";
$header .= "   </div>";
$header .= "</div>";

echo $header;

?>  

<!-- MAIN CONTENT -->
<div class="container-fluid">
   <div class="container container-full" >
      <div class='col-xs-12 col-sm-5 col-md-4 col-lg-4' style='min-height:375px;padding:35px 15px 30px 15px;'>
         <p><b>MAIN SWITCH BOARD:</b><br><?php echo $switchboard;?></p>
         <p><b>PHYSICAL ADDRESS:</b><br><?php echo $address;?></p>
         <p>GPS: <?php echo $gps;?></p>
         <p style='position:absolute;bottom: 0;margin:0!important;'><?php echo $disclaimer;?></p>
      </div>
      <div class='col-xs-12 contact-map-s' style='margin-top:50px;'>
     	<?php
		  $small_map .= "	<div class='' style='padding:0;margin-top:0;'>";
		  $small_map .= $_map;
		  $small_map .= "	</div>";
		 
		  echo $small_map;
		 ?>
     </div>
   </div>
</div>     

<div class="container-fluid" style='padding-top:70px;padding-bottom:70px;'>
   <div class="container container-full ">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding-container contacts-container" style="">
         <div class="row">	  	
         <?php 
            
            foreach($enquiries as $enquiry) {
               $enq_displ .= "<div class='col-sm-6 col-md-4 col-lg-4 three-blocks no-padding-container'>";
	            $enq_displ .= "   <div class='contact-block-headers'>".strtoupper($enquiry['title'])."</div>";
	            $enq_displ .= "   <p class='home-block-sub-headers'>ENQUIRIES</p>";
	            $enq_displ .= "   <div class='home-block-divi'></div>";
	            $enq_displ .= "   <div class='contact-person'>".strtoupper($enquiry['contact_person'])."</div>";
	            $enq_displ .= "   <a href='mailto:".antispambot($enquiry['email'])."' style='font-weight: 300;color:#333333;'><div class='cool-link'>".antispambot($enquiry['email'])."</div></a>";
	            $enq_displ .= "</div>";
            } //end foreach
            
            echo $enq_displ;
         ?>
	      </div>
	   </div>
   </div>
</div>
<div class="container-fluid" style='padding-top:70px;padding-bottom:70px;'>
   <div class="container container-full ">  
	  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
	    <div class="row">
		 <?php

			if ($accordian) {
			   accord_displ($accordian);
			} //end if
		 ?>  
	    </div>
	  </div>
   </div>
</div>
<?php get_footer(); ?>