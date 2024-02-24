<?php get_header(); ?>
<?php
  $slider = get_children( 
    array(
      'post_parent' => get_pageid_by_pageslug( 'front-page/slider' ),
      'post_status' => 'inherit',
      'post_type' => 'attachment',
      'post_mime_type' => 'image',
      'orderby' => 'title',
      'order' => 'ASC'
    )
  );
?>
<div id="frontPageCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-pause="false">
  <div class="carousel-inner">
    <?php
      $slider_active = ' active';
      foreach( $slider as $att_id => $attachment ) :
    ?>
    <div class="carousel-item<?php echo $slider_active; ?>">
      <img src="<?php echo wp_get_attachment_url( $attachment->ID ); ?>" class="d-block w-100" alt="<?php echo( $attachment->post_excerpt ); ?>">
    </div>
    <?php
        $slider_active = '';
      endforeach;
    ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#frontPageCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#frontPageCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
<div class="content-1 features">
  <div class="container">
    <div class="row gx-5">
      <div class="col-md-4 feature">
        <div class="shadow pt-4 pb-2 px-4 bg-white rounded h-100 w-100 d-inline-block">
          <?php get_frontpage_feature( 1 ); ?>
        </div>
      </div>
      <div class="col-md-4 feature">
        <div class="shadow pt-4 pb-2 px-4 bg-white rounded h-100 w-100 d-inline-block">
          <?php get_frontpage_feature( 2 ); ?>
        </div>
      </div>
      <div class="col-md-4 feature">
        <div class="shadow pt-4 pb-2 px-4 bg-white rounded h-100 w-100 d-inline-block">
        <?php get_frontpage_feature( 3 ); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="content-4">
  <div class="container">
    <?php $welcome = get_page_by_path( 'front-page/welcome', OBJECT, [ 'page' ] ); ?>
    <?php if ( ! empty( $welcome ) ): ?>
      <?php echo apply_filters( 'the_content', $welcome->post_content ); ?>
    <?php else: ?>
      <?php echo "There is a problem with the template."; ?>
    <?php endif; ?>
  </div>
</div>
<div class="content-1 latest">
  <div class="container">
    <div class="row">
      <div class="col">
        <h2>Latest Posts</h2>
      </div>
    </div>
    <div class="row">
      <?php
        $latest_args = array(
          'posts_per_page' => 3
        );
        $latest = new WP_Query( $latest_args );
      ?>
    </div>
  </div>
</div>
<div class="content-1">
<h1><?php bloginfo( 'name' ); ?></h1>
<h2><?php bloginfo( 'description' ); ?></h2>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<h3><?php the_title(); ?></h3>

<?php the_content(); ?>
<?php wp_link_pages(); ?>
<?php edit_post_link(); ?>

<?php endwhile; ?>

<?php
if ( get_next_posts_link() ) {
next_posts_link();
}
?>
<?php
if ( get_previous_posts_link() ) {
previous_posts_link();
}
?>

<?php else: ?>

<p>No posts found. :(</p>

<?php endif; ?>
</div>
<?php get_footer(); ?>