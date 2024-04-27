<?php 
/*
	Template Name: Summer Relays - Teams List
*/

// Determine if the user is an administrator or not
$user = wp_get_current_user();
if ( in_array( 'administrator', array_map( fn($str) => strtolower( $str ), (array) $user->roles ) ) ) { $isAdmin = true; } else { $isAdmin = false; }
?>
<?php get_header(); ?>
<div class="content-1">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1><?php the_title(); ?></h1>
				<?php if ( $isAdmin == true ) : ?>
					<a href="<?php echo get_page_permalink_by_pageslug( 'summer-relays/add-team' ) ?>" class="btn btn-primary">Add Team</a>
				<?php endif; ?>
				<table class="table">
					<thead>
    					<tr>
      						<th scope="col">#</th>
      						<th scope="col">Team Name</th>
      						<th scope="col">Club Name</th>
      						<th scope="col">Category</th>
							<th scope="col">Runner A</td>
							<th scope="col">Runner B</td>
							<th scope="col">Runner C</td>
    					</tr>
  					</thead>
					<tbody>
						<?php
							$teams = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ghac_c_teams" );
							foreach($teams as $row) {
						?>
							<tr>
								<th scope="row"><a href="<?php echo get_page_permalink_by_pageslug( 'summer-relays/edit-team' ) . '?teamid=' . $row->TeamID; ?>"><?php echo $row->TeamNumber; ?></a></th>
								<td><a href="<?php echo get_page_permalink_by_pageslug( 'summer-relays/edit-team' ) . '?teamid=' . $row->TeamID; ?>"><?php echo $row->TeamName ?></a></td>
								<td><?php echo $row->ClubName; ?></td>
								<td><?php echo $row->Category; ?></td>
								<td><?php echo $row->RunnerA; ?></td>
								<td><?php echo $row->RunnerB; ?></td>
								<td><?php echo $row->RunnerC; ?></td>
							</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>