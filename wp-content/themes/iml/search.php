<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package IML
 */

get_header(); ?>


<div id="content" class="hero-content row clear-nav" role="main">
<?php if ( have_posts() ) : ?>
    <div class="page-title row">
      <h1><?php printf( __( 'Search Results for: %s', 'artworks' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
    </div>
  <?php while ( have_posts() ) : the_post(); ?>

    <div class='search-listing row'>
<!--      <h3><?=strtoupper($post->post_type);?></h3> -->
<?php
        if ( has_post_thumbnail()) : ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php { the_post_thumbnail('thumbnail') ; } ?></a>
<?php
        endif;?>
        <div class="search-listing-text">
      <h3><?="$post->post_title";?></h3>
      <p><?php if($type=='event') echo date_range();?></p>
      <p><?php echo get_post_meta($post->ID, 'summary', true); ?></p>
    
      <a href="<?php the_permalink(); ?>">Read article &raquo;</a>
    </div> <!-- search-listing-text -->
    </div><!-- search-listing-->

  <?php endwhile; ?>

<?php else : ?>
<?php if ( is_search() ) : ?>
  <div class="page-title row">
      <h1><?php printf( __( 'Search Results for: %s', 'IML' ),  get_search_query()  ); ?></h1>
    </div>

         <div class='search-fail'>
        <p>Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>
      </div>


<?php else : ?>
  <div class='search-fail'>
      <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'IML' ); ?></p>
    </div>
<?php endif; ?>
    
<?php endif; ?>


  </div><!-- #content -->
<?php get_sidebar('quicklinks'); ?>

<?php get_footer(); ?>
