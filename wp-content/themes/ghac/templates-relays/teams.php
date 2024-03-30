<?php 
/*
	Template Name: Summer Relays - Teams List
*/
?>
<?php get_header(); ?>
<div class="content-1">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1><?php the_title(); ?></h1>
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
								<th scope="row"><a href="relays-edit-team"><?php echo $row->TeamNumber ?></a></th>
								<td><a href="relays-edit-team"><?php echo $row->TeamName ?></a></td>
								<td><?php echo $row->ClubName ?></td>
								<td><?php echo $row->Category ?></td>
								<td><?php echo $row->RunnerA ?></td>
								<td><?php echo $row->RunnerB ?></td>
								<td><?php echo $row->RunnerC ?></td>
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