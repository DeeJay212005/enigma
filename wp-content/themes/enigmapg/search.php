<?php
get_header(); 
?>

<div class="container-fluid three-blocks-container" style="background-color: #ffffff;padding: 250px 0 0 0;">
   <div class="container container-full">
      <div class="col-xs-12" >
         <h1><?php printf( __( 'Search Results for: %s', 'twentysixteen' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
      </div>
   </div>
</div>

<div class="container-fluid three-blocks-container">
   <div class="container container-full">
      <div class="col-xs-12" >
         
         <?php 
         if ( have_posts()) { 
         ?> 
            <div class="list-group">
               <?php while ( have_posts() ) : the_post(); ?>
                  <a href="<?php echo the_permalink(); ?>" class="list-group-item" style="color:#5e5e5e!important;">
                      <?php
                          $title 	= get_the_title();
            /*
                          $keys= explode(" ",$s);
                          $title 	= preg_replace('/('.implode('|', $keys) .')/iu','<strong class="search-excerpt">\0</strong>', $title);*/
                      ?>
                      <h4><?php echo $title; ?></h4>
                      <p><?php the_excerpt(); ?></p>
                  </a>
               <?php endwhile; ?>
            </div>
         <?php 
         } else {
         ?>
            <p style="text-align:left;margin-top:10px">Your search query cannot be found throughout the site.</p>
         <?php
         }
         ?> 
      </div>
   </div>
</div>  
    
<?php
	get_footer(); 
?>

