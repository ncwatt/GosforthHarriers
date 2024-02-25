<?php 
/*
	Template Name: Shop Page
*/
?>
<?php get_header(); ?>
<div class="content-1">
	<div class="container my-3">
		<div class="row">
			<div class="col-12">
				<h1><?php the_title(); ?></h1>
				<?php
					if(have_posts()) : while(have_posts()) : the_post();
						the_content();
					endwhile; else : endif;
				?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>