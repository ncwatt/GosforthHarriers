<?php 
/*
	Template Name: Summer Relays - Edit Team
*/
// Check that the user is logged in - if not redirect to login page
if ( ! is_user_logged_in() ) { wp_redirect( wp_login_url() . '?redirect_to=' . urlencode( $_SERVER['REQUEST_URI'] ) ); }

// Determine if the user is an administrator or not
$user = wp_get_current_user();
if ( in_array( 'Administrator', (array) $user->roles ) ) { $isAdmin = true; } else { $isAdmin = false; }

// Get the TeamID from the QueryString. If there isn't one then leave the page.
if ( isset( $_GET['teamid'] ) ) { 
	$teamID = $_GET['teamid'];
	$team = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ghac_c_teams WHERE TeamID = %d", $teamID ) );
	if ( ! isset( $team ) ) { wp_redirect( get_page_permalink_by_pageslug( 'summer-relays/teams-manager' ) ); }
} else {
	wp_redirect( get_page_permalink_by_pageslug( 'summer-relays/teams-manager' ) );
}

if ( $_SERVER["REQUEST_METHOD"] == "GET" ) {
	$teamNum = $team->TeamNumber;
	$teamName = $team->TeamName;
	$clubName = $team->ClubName;
	$category = $team->Category;
	$runnerAName = $team->RunnerA;
	$runnerAURN = $team->RunnerAURN;
	$runnerBName = $team->RunnerB;
	$runnerBURN = $team->RunnerBURN;
	$runnerCName = $team->RunnerC;
	$runnerCURN = $team->RunnerCURN;
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	if ( isset( $_POST['editTeamForm'] ) ) {
		// Get the values from the form
		$teamNum = isset( $_POST['teamNumber'] ) ? form_input_checks( $_POST['teamNumber'] ) : null;
		$teamNumAuto = isset( $_POST['teamNumberAuto'] ) ? true : false;
		$teamName = form_input_checks( $_POST['teamName'] );
		$category = form_input_checks( $_POST["category"] );
		$clubName = form_input_checks( $_POST['clubName'] );
		$runnerAName = form_input_checks( $_POST['runnerAName'] );
		$runnerAURN = form_input_checks( $_POST['runnerAURN'] );
		$runnerBName = form_input_checks( $_POST['runnerBName'] );
		$runnerBURN = form_input_checks( $_POST['runnerBURN'] );
		$runnerCName = form_input_checks( $_POST['runnerCName'] );
		$runnerCURN = form_input_checks( $_POST['runnerCURN'] );
		//$email = form_input_checks( $_POST["emailAddress"] );

		// Loop through all posted items from the form to see if any refer to deleting an email address
		foreach ( $_POST as $postItemName => $postItemValue ) {
			if ( substr( $postItemName, 0, 8 ) == 'delEmail' ) {
				$delEmailArray = explode( ';', $postItemValue );
				$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}ghac_c_teamemails WHERE TeamID = %d AND EmailID = %d;", array( $teamID, $delEmailArray[0] ) ) );
				if ( empty( $wpdb->last_error ) ) {
					$delEmailToast = $delEmailArray[1] . ' has been deleted from the team.';	
				} else {
					$delEmailToast = 'There was an error deleting the email address from the team.';
				}
			}
		}

		// Only process this if the submit (save changes) button has been clicked
		if ( isset( $_POST['submit'] ) ) {
			// Set postSuccess to true - this will get set to false during validation if a value fails
			$postSuccess = true;
			$errors = array();
	
			// Validate team number
			$teamNumErr = false;
			$team_query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ghac_c_teams WHERE TeamNumber = %d", $teamNum );
			$team_results = $wpdb->get_results( $team_query );
			if ( !empty( $team_results ) ) {
				$teamNumErr = true;
				$errors[] = 'The team number you enetered is already in use';
				$postSuccess = false;
			} elseif ( strlen( $teamNum ) == 0 && $teamNumAuto == false ) {
				$teamNumErr = true;
				$errors[] = 'You must enter a team number or select the checkbox to generate one automatically';
				$postSuccess = false;
			}
			
			// Validate team name
			$teamNameErr = false;
			$team_query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ghac_c_teams WHERE TeamName = %s", $teamName );
			$team_results = $wpdb->get_results( $team_query );
			if ( !empty( $team_results ) ) {
				$teamNameErr = true;
				$errors[] = 'The team name you entered already exists';
				$postSuccess = false;
			}
	
			// Validate a club name
			$clubNameErr = false;
			if ( ! ( strlen( $clubName ) > 0 && strlen( $clubName ) <= 75) ) {
				$clubNameErr = true;
				$errors[] = 'You have not entered the running club name that this team is associated with';
				$postSuccess = false;
			}
	
			// Validate email address
			$emailErr = false;
			if ( ! ( strlen( $email ) > 0 ) ) {
				$emailErr = true;
				$errors[] = 'You have not entered the email address of the club contact used during registration';
				$postSuccess = false;
			} elseif ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) { 
				$emailErr = true;
				$errors[] = 'The email address entered is not valid';
				$postSuccess = false;
			}
	
			if ( $postSuccess ) {
				if ( ! isset( $teamNum ) ) {
					// A team number hasn't been entered therefore we need to generate one
					$teamNum = $wpdb->get_var( $wpdb->prepare( "SELECT MAX(TeamNumber) FROM {$wpdb->prefix}ghac_c_teams" ) );
					if ( isset( $teamNum ) ) {
						$teamNum = $teamNum + 1;
					} else {
						$teamNum = 1;
					}
				}
	
				if ( empty( $teamName ) ) {
					// A team name hasn't been entered therefore we need to generate one
					$teamName = 'Team #' . $teamNum;
				}
	
				// Insert the team into the table
				$wpdb->insert( "{$wpdb->prefix}ghac_c_teams", array(
					'TeamNumber' => $teamNum,
					'TeamName' => $teamName,
					'Category' => $category, 
					'ClubName' => $clubName
				) );
				$teamID = $wpdb->insert_id;
	
				// Check if the email address is already in the table
				$emailID = $wpdb->get_var( $wpdb->prepare( "SELECT EmailID FROM {$wpdb->prefix}ghac_c_emails WHERE EmailAddress = %s", $email ) );
				if ( ! isset( $emailID ) ) {
					// Insert the email into the table
					$wpdb->insert( "{$wpdb->prefix}ghac_c_emails", array(
						'EmailAddress' => $email
					) );
					// Get the emailID for the newly inserted record
					$emailID = $wpdb->insert_id;
				}
	
				// Insert link the team and the email address
				$wpdb->insert( "{$wpdb->prefix}ghac_c_teamemails", array(
					'TeamID' => $teamID,
					'EmailID' => $emailID
				) );
	
				// Everything went to plan so let's redirect to the edit page for this team
				wp_redirect( get_page_permalink_by_pageslug( 'summer-relays/edit-team' ) . '?teamid=' . $teamID );
				//header('Location: ' . get_page_permalink_by_pageslug( 'summer-relays/edit-team' ) . '?teamid=' . $wpdb->insert_id);
				exit;
			}
		}
	}
}
?>

<?php get_header(); ?>
<div class="content-1">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php if ( $isAdmin = false ) : ?>
					<div class="alert alert-danger" role="alert">
                        <p>You are not authorised to view this page.</p>
                    </div>
				<?php else: ?>
					<h1><?php the_title(); ?></h1>
					<?php if ( ( isset( $postSuccess ) ) && ( $postSuccess == false ) ) : ?>
                    	<div class="alert alert-danger" role="alert">
                        	<p>There are errors on the form. Please correct the following and re-submit:</p>
							<ul>
								<?php foreach ( $errors as $error ) { ?>
									<li><?php echo $error; ?></li>
								<?php } ?>
							</ul>
                    	</div>
                	<?php endif; ?>
                	<form id="teamForm" name="teamForm" method="post" action="" novalidate class="mb-4 mb-lg-0">
                    	<input type="hidden" name="editTeamForm" value="1" />
						<div class="row">
							<div class="col-md-6">
								<div class="card mb-3">
									<div class="card-header">
										Team Details
									</div>
									<div class="card-body">
										<div class="mb-3">
                        					<label for="teamNumber" class="form-label">Team Number</label>
                        					<div class="input-group">
                            					<input type="number" id="teamNumber" name="teamNumber" aria-describedby="teamNumberHelp" <?php if ( isset( $teamNumAuto ) ) { echo ( $teamNumAuto == true ) ? 'disabled' : ''; } ?> value="<?php echo( isset( $teamNum ) ) ? $teamNum : ''; ?>" class="form-control <?php if ( isset( $teamNumErr ) ) { echo( $teamNumErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                            					<div class="input-group-text">
                                					<input type="checkbox" id="teamNumberAuto" name="teamNumberAuto" <?php if ( isset( $teamNumAuto ) ) { echo ( $teamNumAuto == true ) ? 'checked' : ''; } ?> value="teamNumberAuto" aria-label="Check to automatically generate team number" class="form-check-input mt-0" onclick="teamNumberToggle(this.checked)">
                            					</div>
												<script>
													function teamNumberToggle(checked) {
														document.getElementById('teamNumber').value = '';
														document.getElementById('teamNumber').disabled = checked;
													}
												</script>
                        					</div>
                        					<div id="teamNumberHelp" class="form-text">Enter the team number which will appear on the runners' bibs. If it is not known at this stage select the checkbox to automatically generate a number for now. This can be changed later.</div>
                    					</div>
										<div class="mb-3">
											<label for="teamName" class="form-label">Team Name</label>
                        					<input type="text" id="teamName" name="teamName" aria-describedby="teamNameHelp" maxlength="50" value="<?php echo( isset( $teamName ) ) ? $teamName : ''; ?>" class="form-control <?php if ( isset( $teamNameErr ) ) { echo( $teamNameErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                        					<div id="teamNameHelp" class="form-text">Enter a unique name for the team which will appear on the results page. Leave blank to use the team number.</div>
                    					</div>
										<div class="mb-3">
											<label for="clubName" class="form-label">Club Name</label>
                        					<input type="text" id="clubName" name="clubName" aria-describedby="clubNameHelp" maxlength="75" value="<?php echo( isset( $clubName ) ) ? $clubName : ''; ?>" class="form-control <?php if ( isset( $clubNameErr ) ) { echo( $clubNameErr == true ) ? 'is-invalid' : 'is-valid'; } ?>" >
                        					<div id="clubNameHelp" class="form-text">Enter the name of the running club to associate this team with.</div>
										</div>
										<div class="mb-3">
											<label for="category" class="form-label">Category</label>
                        					<select class="form-select" id="category" name="category" aria-describedby="categoryHelp">
  												<option value="Uncategorised" <?php if ( ! isset( $category ) || $category == 'Uncategorised' ) echo( 'selected' );  ?>>Select a category</option>
  												<option value="Senior Ladies" <?php if ( isset( $category ) && $category == 'Senior Ladies' ) echo( 'selected' );  ?>>Senior Ladies</option>
  												<option value="Senior Mens" <?php if ( isset( $category ) && $category == 'Senior Mens' ) echo( 'selected' );  ?>>Senior Mens</option>
  												<option value="Veteran Ladies" <?php if ( isset( $category ) && $category == 'Veteran Ladies' ) echo( 'selected' );  ?>>Veteran Ladies</option>
												<option value="Veteran Mens" <?php if ( isset( $category ) && $category == 'Veteran Mens' ) echo( 'selected' );  ?>>Veteran Mens</option>
											</select>
                        					<div id="categoryHelp" class="form-text">Select the category for the team. Leave blank if not known.</div>
                    					</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card mb-3">
									<div class="card-header">
										Team Status
									</div>
									<div class="card-body">
										<div class="mb-3">
											Runner fields go here
										</div>
									</div>
								</div>
								<div class="card mb-3">
									<div class="card-header">
										Admin Email Addresses
									</div>
									<div class="card-body">
										<div class="mb-3">
											<table class="table">
												<thead>
    												<tr>
      													<th scope="col">Email Address</th>
														<th scope="col"></th>
    												</tr>
  												</thead>
												<tbody>
													<?php
														$emailAddresses = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ghac_c_teamemails te INNER JOIN {$wpdb->prefix}ghac_c_emails em ON te.EmailID = em.EmailID WHERE te.TeamID = %d", $teamID ) );
														foreach($emailAddresses as $emailRow) {
													?>
														<tr>
															<td><?php echo $emailRow->EmailAddress; ?></td>
															<td>
																<span class="float-end"><button type="button" class="btn btn-link bi bi-trash3 icon-button" data-bs-toggle="modal" data-bs-target="#modalDelEmail<?php echo $emailRow->EmailID; ?>"></button></span>
																<!-- Confirmation Modal -->
																<div class="modal fade" id="modalDelEmail<?php echo $emailRow->EmailID; ?>" tabindex="-1" aria-labelledby="modalDelEmailLabel<?php echo $emailRow->EmailID; ?>" aria-hidden="true">
  																	<div class="modal-dialog">
    																	<div class="modal-content">
      																		<div class="modal-header">
        																		<h1 class="modal-title fs-5" id="modalDelEmailLabel<?php echo $emailRow->EmailID; ?>">Delete Email Address</h1>
        																		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      																		</div>
      																		<div class="modal-body">
																				<p>Are you sure you want delete the following email address from this team:</p>
																				<p class="text-center"><strong><?php echo $emailRow->EmailAddress; ?></strong></p>
																				<p>Deleting the email address from this team does not affect any other teams that it is associated with.</p>
      																		</div>
      																		<div class="modal-footer">
        																		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
																				<button type="submit" class="btn btn-primary" name="delEmail<?php echo $emailRow->EmailID; ?>" value="<?php echo $emailRow->EmailID . ';' . $emailRow->EmailAddress; ?>">Confirm Deletion</button>
      																		</div>
    																	</div>
  																	</div>
																</div>
															</td>
														</tr>
													<?php
														}
													?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="card-footer text-body-secondary">
    2 days ago
  </div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="card mb-3">
									<div class="card-header">
										Runner A
									</div>
									<div class="card-body">
										<div class="mb-3">
											<label for="runnerAName" class="form-label">Full Name</label>
                        					<input type="text" id="runnerAName" name="runnerAName" aria-describedby="runnerANameHelp" maxlength="60" value="<?php echo( isset( $runnerAName ) ) ? $runnerAName : ''; ?>" class="form-control <?php if ( isset( $runnerANameErr ) ) { echo( $runnerANameErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                        					<div id="runnerANameHelp" class="form-text">Enter the name of the runner for the first leg.</div>
										</div>
										<div class="mb-3">
											<label for="runnerAURN" class="form-label">UKA URN</label>
                        					<input type="number" id="runnerAURN" name="runnerAURN" aria-describedby="runnerAURNHelp" maxlength="60" value="<?php echo( isset( $runnerAURN ) ) ? $runnerAURN : ''; ?>" class="form-control <?php if ( isset( $runnerAURNErr ) ) { echo( $runnerAURNErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                        					<div id="runnerANameHelp" class="form-text">Enter the UK Athletics URN for the runner for the first leg.</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card mb-3">
									<div class="card-header">
										Runner B
									</div>
									<div class="card-body">
										<div class="mb-3">
											<label for="runnerBName" class="form-label">Full Name</label>
                        					<input type="text" id="runnerBName" name="runnerBName" aria-describedby="runnerBNameHelp" maxlength="60" value="<?php echo( isset( $runnerBName ) ) ? $runnerBName : ''; ?>" class="form-control <?php if ( isset( $runnerBNameErr ) ) { echo( $runnerBNameErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                        					<div id="runnerBNameHelp" class="form-text">Enter the name of the runner for the second leg.</div>
										</div>
										<div class="mb-3">
											<label for="runnerBURN" class="form-label">UKA URN</label>
                        					<input type="number" id="runnerBURN" name="runnerBURN" aria-describedby="runnerBURNHelp" maxlength="60" value="<?php echo( isset( $runnerBURN ) ) ? $runnerBURN : ''; ?>" class="form-control <?php if ( isset( $runnerBURNErr ) ) { echo( $runnerBURNErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                        					<div id="runnerANameHelp" class="form-text">Enter the UK Athletics URN for the runner for the second leg.</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="card mb-3">
									<div class="card-header">
										Runner C
									</div>
									<div class="card-body">
										<div class="mb-3">
											<label for="runnerCName" class="form-label">Full Name</label>
                        					<input type="text" id="runnerCName" name="runnerCName" aria-describedby="runnerCNameHelp" maxlength="60" value="<?php echo( isset( $runnerCName ) ) ? $runnerCName : ''; ?>" class="form-control <?php if ( isset( $runnerCNameErr ) ) { echo( $runnerCNameErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                        					<div id="runnerCNameHelp" class="form-text">Enter the name of the runner for the last leg.</div>
										</div>
										<div class="mb-3">
											<label for="runnerCURN" class="form-label">UKA URN</label>
                        					<input type="number" id="runnerCURN" name="runnerCURN" aria-describedby="runnerCURNHelp" maxlength="60" value="<?php echo( isset( $runnerCURN ) ) ? $runnerCURN : ''; ?>" class="form-control <?php if ( isset( $runnerCURNErr ) ) { echo( $runnerCURNErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                        					<div id="runnerCNameHelp" class="form-text">Enter the UK Athletics URN for the runner for the last leg.</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<button type="submit" name="submit" value="submit" class="btn btn-primary">Save Changes</button>&nbsp;&nbsp;
								<a href="<?php echo get_page_permalink_by_pageslug( 'summer-relays/teams-manager' ); ?>" class="btn btn-secondary">Cancel</a>
							</div>
						</div>
                	</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<script>
	var form_clean;
	// Serialize the clean form
	//window.addEventListener('DOMContentLoaded', function () {
    //	form_clean = document.forms['teamForm'].elements;
	//});

	// Compare clean and dirty form before leaving
	//window.onbeforeunload = function (e) {
    //	var form_dirty = document.forms['teamForm'].elements;
    //	for (var i = 0; i < form_clean.length; i++) {
    //    	if (form_clean[i].value !== form_dirty[i].value) {
    //        	return 'There is unsaved form data.';
    //    	}
    //	}
	//};
</script>
<?php if ( isset( $delEmailToast ) ) : ?>
	<div class="toast-container position-fixed bottom-0 end-0 p-3">
		<div id="toastDelEmail" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header">
				<i class="bi bi-envelope-x"></i>&nbsp;
				<strong class="me-auto">Delete Email</strong>
				<small>Just now</small>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body">
				<?php echo $delEmailToast; ?>
			</div>
		</div>
	</div>
	<script>
		window.onload = function () {
			const toastBootstrap = bootstrap.Toast.getOrCreateInstance(document.getElementById("toastDelEmail"));
			toastBootstrap.show();
		}
	</script>
<?php endif; ?>	
<?php get_footer(); ?>