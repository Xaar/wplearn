<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package artworks
 */

get_header(); ?>

<section id="primary" class="content-area">
  <div id="content" class="site-content" role="main">
<?php if ( have_posts() ) : ?>
    <header class="page-header">
      <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'artworks' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
    </header><!-- .page-header -->
  <?php while ( have_posts() ) : the_post(); ?>

    <div class='search-listing'>
<!--      <h3><?=strtoupper($post->post_type);?></h3> -->
      <h3><?="$post->post_title";?></h3>
      <p><?php if($type=='event') echo date_range();?></p>
      <p><?php echo get_post_meta($post->ID, 'excerpt', true); ?></p>
      <a href="<?php the_permalink(); ?>">Link to matching article &raquo;</a>
    </div><!-- content-listing-->

  <?php endwhile; ?>

<?php else : ?>

    <div class="entry-content">
<?php if ( is_search() ) : ?>
      <div class='search-listing'>
        <h3>No match found for: <?=get_search_query();?></h3>
        <p>Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>
      </div><!-- content-listing-->

<?php else : ?>
      <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'IML' ); ?></p>
<?php endif; ?>
    </div><!-- .entry-content -->
<?php endif; ?>
  </div><!-- #content -->
</section><!-- #primary -->

<?php get_footer(); ?>
