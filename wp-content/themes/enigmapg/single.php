<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header();

$page_gallery = get_field( "page_gallery",$post_id);

$thumb_id = get_post_thumbnail_id($post_id);
$image = wp_get_attachment_image_src($thumb_id, "full");
$flow_img_xl = $image[0];	

$image = wp_get_attachment_image_src($thumb_id, "large");
$flow_img_lrg = $image[0];	

$image = wp_get_attachment_image_src($thumb_id, "medium");
$flow_img_med = $image[0];	

$image = wp_get_attachment_image_src($thumb_id, "thumbnail");
$flow_img_sml = $image[0];	

?>

<!-- FLOW DIV -->
<div class="container-fluid">
	<div class="flow-table">
      <div class="flow-text" style="vertical-align: top;">
         <?php mns_breadcrumb();?>
         <div class='main-content-header'><h1><?php echo get_the_title($post_id).$subtitle;?></h1></div>
         <div class='main-content'><?php the_content();?></div>
      </div>
      <div class="flow-image">
         <?php
            if($page_gallery) {
               gallery_display($page_gallery);
            } else {
               echo "   <picture>";
               echo "      <source src='".$flow_img_sml."' media='(max-width: 497px)'>";
               echo "      <source src='".$flow_img_med."' media='(max-width: 992px)'>";
               echo "      <source src='".$flow_img_lrg."' media='(max-width: 1200px)'>";
               echo "      <img src='".$flow_img_xl."' style='width:100%;'>";
               echo "   </picture>";
            }//end if
         ?>        
      </div>      
	</div>
</div>    
<?php get_footer(); ?>
