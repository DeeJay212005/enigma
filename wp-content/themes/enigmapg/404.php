<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>
<div class="container-fluid" style="background-color: #000; margin: 0 0 50px 0; padding: 0; height: 300px;">&nbsp;</div>

<div class="container">

   <div class="" style="color: #ededed; font-size: 10vw; font-weight: 900">404</div>
      <h1 class="page-title" style="font-size: 2vw; font-weight: 400;margin: 0 0 50px 0; padding: 0;"><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentysixteen' ); ?>
      </h1>
</div>


<?php
	wp_reset_query();
	get_footer(); 
?>
