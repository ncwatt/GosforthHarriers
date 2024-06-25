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
	$runnerATime = $team->RunnerATime;
	$runnerALegTime = $team->RunnerALegTime;
	$runnerBName = $team->RunnerB;
	$runnerBTime = $team->RunnerBTime;
	$runnerBLegTime = $team->RunnerBLegTime;
	$runnerCName = $team->RunnerC;
	$runnerCTime = $team->RunnerCTime;
	$runnerCLegTime = $team->RunnerCTime;
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	if ( isset( $_POST['editTeamForm'] ) ) {
		// Get the values from the form
		$teamNum = isset( $_POST['teamNumber'] ) ? form_input_checks( $_POST['teamNumber'] ) : null;
		$teamName = form_input_checks( $_POST['teamName'] );
		$category = form_input_checks( $_POST["category"] );
		$clubName = form_input_checks( $_POST['clubName'] );
		$runnerAName = form_input_checks( $_POST['runnerAName'] );
		$runnerATime = form_input_checks( $_POST['runnerATime'] );
		$runnerALegTime = form_input_checks( $_POST['runnerALegTime'] );
		$runnerBName = form_input_checks( $_POST['runnerBName'] );
		$runnerBTime = form_input_checks( $_POST['runnerBTime'] );
		$runnerBLegTime = form_input_checks( $_POST['runnerBLegTime'] );
		$runnerCName = form_input_checks( $_POST['runnerCName'] );
		$runnerCTime = form_input_checks( $_POST['runnerCTime'] );
		$runnerCLegTime = form_input_checks( $_POST['runnerCLegTime'] );
		$firstName = form_input_checks( $_POST["firstName"] );
		$lastName = form_input_checks( $_POST["lastName"] );
		$email = form_input_checks( $_POST["emailAddress"] );

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

		// Only process this if the add contact button has been clicked
		if ( isset( $_POST['submitContact'] ) ) {
			// Set contactSuccess to true - this will get set to false during validation if a value fails
			$contactSuccess = true;
			$errors = array();

			// Validate the first name
			$firstNameErr = false;
			if ( ! ( strlen( $firstName ) > 0 && strlen( $firstName ) <= 35 ) ) {
				$firstNameErr = true;
				$errors[] = 'You have not entered the first name of the new club contact';
				$contactSuccess = false;
			}

			// Validate the last name
			$lastNameErr = false;
			if ( ! ( strlen( $lastName ) > 0 && strlen( $lastName ) <= 35 ) ) {
				$lastNameErr = true;
				$errors[] = 'You have not entered the last name of the new lub contact';
				$contactSuccess = false;
			}

			// Validate email address
			$emailErr = false;
        	if ( ! ( strlen( $email ) > 0 ) ) {
				$emailErr = true;
				$errors[] = 'You have not entered the email address of the new club contact';
				$contactSuccess = false;
			} elseif ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) { 
            	$emailErr = true;
				$errors[] = 'The email address entered is not valid';
            	$contactSuccess = false;
        	}

			if ( $contactSuccess ) {
				// Check if the email address is already in the table
				$emailID = $wpdb->get_var( $wpdb->prepare( "SELECT EmailID FROM {$wpdb->prefix}ghac_c_emails WHERE EmailAddress = %s", $email ) );
				if ( ! isset( $emailID ) ) {
					// Insert the email into the table
					$wpdb->insert( "{$wpdb->prefix}ghac_c_emails", array(
						'EmailAddress' => $email,
						'FirstName' => $firstName,
						'LastName' => $lastName
					) );
					// Get the emailID for the newly inserted record
					$emailID = $wpdb->insert_id;
				} else {
					// Update the name and last name for the email address supplied
					$wpdb->update( 
						"{$wpdb->prefix}ghac_c_emails", 
						array(
							'FirstName' => $firstName,
							'LastName' => $lastName
						),
						array(
							'EmailID' => $emailID
						)
					);
				}
	
				// Insert link the team and the email address
				$wpdb->insert( "{$wpdb->prefix}ghac_c_teamemails", 
					array(
						'TeamID' => $teamID,
						'EmailID' => $emailID
					) 
				);

				// Reset the add contact values
				$firstName = "";
				$firstNameErr = null;
				$lastName = "";
				$lastNameErr = null;
				$email = "";
				$emailErr = null;
				$contactSuccess = null;
			}
		}

		// Only process this if the submit (save changes) button has been clicked
		if ( isset( $_POST['submit'] ) ) {
			// Set postSuccess to true - this will get set to false during validation if a value fails
			$postSuccess = true;
			$errors = array();
	
			// Validate team number
			$teamNumErr = false;
			$team_query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ghac_c_teams WHERE TeamNumber = %d AND TeamID <> %d", array( $teamNum, $teamID ) );
			$team_results = $wpdb->get_results( $team_query );
			if ( ! empty( $team_results ) ) {
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
			$team_query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ghac_c_teams WHERE TeamName = %s AND TeamID <> %d", array( $teamName, $teamID ) );
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
	
			// Validate Runner B time
			$runnerBTimeErr = false;
			if ( ( date( 'H:i:s', strtotime( $runnerBTime ) ) != '00:00:00' ) && ( strtotime( $runnerBTime ) <= strtotime( $runnerATime ) ) ) {
				$runnerBTimeErr = true;
				$errors[] = 'Runner B\'s time is less than or equal to Runner A\'s';
				$postSuccess = false;
			}

			// Validate Runner C time
			$runnerCTimeErr = false;
			if ( ( date( 'H:i:s', strtotime( $runnerCTime ) ) != '00:00:00' ) && ( strtotime( $runnerCTime ) <= strtotime( $runnerATime ) || strtotime( $runnerCTime ) <= strtotime( $runnerBTime ) ) ) {
				$runnerCTimeErr = true;
				$errors[] = 'Runner C\'s time is less than one of the other runner\'s times';
				$postSuccess = false;
			}

			// Update the leg times
			$runnerALegTime = $runnerATime;
			$runnerBLegTime = gmdate( "H:i:s", strtotime( $runnerBTime ) - strtotime( $runnerATime ) );
			//gmdate("H:i:s", $seconds);
			echo $runnerBLegTime;
			$runnerCLegTime = gmdate( "H:i:s", strtotime( $runnerCTime ) - strtotime( $runnerBTime ) - strtotime( $runnerATime ) );

			if ( $postSuccess ) {
				// Update the values
				$wpdb->update( 
					"{$wpdb->prefix}ghac_c_teams", 
					array(
						'TeamNumber' => $teamNum,
						'TeamName' => $teamName,
						'ClubName' => $clubName,
						'Category' => $category,
						'RunnerA' => $runnerAName,
						'RunnerATime' => $runnerATime,
						'RunnerALegTime' => $runnerALegTime,
						'RunnerB' => $runnerBName,
						'RunnerBTime' => $runnerBTime,
						'RunnerBLegTime' => $runnerBLegTime,
						'RunnerC' => $runnerCName,
						'RunnerCTime' => $runnerCTime,
						'RunnerCLegTime' => $runnerCLegTime
					),
					array(
						'TeamID' => $teamID
					)
				);

				// Redirect back to the teams manager page
				wp_redirect( get_page_permalink_by_pageslug( 'summer-relays/teams-manager' ) );
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
					<?php if ( ( ( isset( $postSuccess ) ) && ( $postSuccess == false ) ) || ( ( isset( $contactSuccess ) ) && ( $contactSuccess == false ) ) ) : ?>
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
											<input type="number" id="teamNumber" name="teamNumber" aria-describedby="teamNumberHelp" value="<?php echo( isset( $teamNum ) ) ? $teamNum : ''; ?>" class="form-control <?php if ( isset( $teamNumErr ) ) { echo( $teamNumErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
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
										Club Contacts
									</div>
									<div class="card-body">
										<div class="mb-3">
											<table class="table">
												<thead>
    												<tr>
														<th scope="col">First Name</th>
														<th scope="col">Last Name</th>
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
															<td><?php echo $emailRow->FirstName; ?></td>
															<td><?php echo $emailRow->LastName; ?></td>
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
											<div class="mb-3">
												<button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addContact" aria-expanded="false" aria-controls="addContact">
    												Add Contact
  												</button>
											</div>
											<div class="<?php echo( isset( $contactSuccess ) ) ? 'collapse show' : 'collapse'; ?>" id="addContact">
												<div class="card mb-3">
													<div class="card-body">
														<div class="mb-3">
															<div class="row">
																<div class="col-md-6 mb-3">
																	<label for="firstName" class="form-label">First Name</label>
                        											<input type="text" id="firstName" name="firstName" aria-describedby="firstNameHelp" maxlength="35" value="<?php echo( isset( $firstName ) ) ? $firstName : ''; ?>" class="form-control <?php if ( isset( $firstNameErr ) ) { echo( $firstNameErr == true ) ? 'is-invalid' : 'is-valid'; } ?>" >
                        											<div id="firstNameHelp" class="form-text">Enter the club contact's first name.</div>
																</div>
																<div class="col-md-6 mb-3">
																	<label for="lastName" class="form-label">Last Name</label>
                        											<input type="text" id="lastName" name="lastName" aria-describedby="lastNameHelp" maxlength="35" value="<?php echo( isset( $lastName ) ) ? $lastName : ''; ?>" class="form-control <?php if ( isset( $lastNameErr ) ) { echo( $lastNameErr == true ) ? 'is-invalid' : 'is-valid'; } ?>" >
                        											<div id="lastNameHelp" class="form-text">Enter the club contact's last name.</div>
																</div>
															</div>
															<div class="row">
																<div class="mb-3">
																	<label for="emailAddress" class="form-label">Email address</label>
                        											<input type="email" id="emailAddress" name="emailAddress" aria-describedby="emailAddressHelp" maxlength="254" value="<?php echo( isset( $email ) ) ? $email : ''; ?>" class="form-control <?php if ( isset( $emailErr ) ) { echo( $emailErr == true ) ? 'is-invalid' : 'is-valid'; } ?>" >
                        											<div id="emailAddressHelp" class="form-text">Enter the email address of the club contact.</div>
																</div>
															</div>
															<div class="row">
																<div class="col">
																	<button type="submit" name="submitContact" value="submitContact" class="btn btn-primary">Submit</button>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
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
											<label for="runnerATime" class="form-label">Gun Time</label>
                        					<input type="time" id="runnerATime" name="runnerATime" aria-describedby="runnerATimeHelp" step="1" value="<?php echo( isset( $runnerATime ) ) ? $runnerATime : ''; ?>" class="form-control <?php if ( isset( $runnerATimeErr ) ) { echo( $runnerATimeErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                        					<div id="runnerATimeHelp" class="form-text">Enter the gun time for the runner of the first leg.</div>
										</div>
										<div class="mb-3">
											<input type="hidden" name="runnerALegTime" value="<?php echo( isset( $runnerALegTime ) ) ? $runnerALegTime : ''; ?>">
											<p>Leg Time: <?php echo $runnerALegTime; ?></p>
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
											<label for="runnerBTime" class="form-label">Gun Time</label>
                        					<input type="time" id="runnerBTime" name="runnerBTime" aria-describedby="runnerBTimeHelp" step="1" value="<?php echo( isset( $runnerBTime ) ) ? $runnerBTime : ''; ?>" class="form-control <?php if ( isset( $runnerBTimeErr ) ) { echo( $runnerBTimeErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                        					<div id="runnerBTimeHelp" class="form-text">Enter the gun time for the runner of the second leg.</div>
										</div>
										<div class="mb-3">
											<input type="hidden" name="runnerBLegTime" value="<?php echo( isset( $runnerBLegTime ) ) ? $runnerBLegTime : ''; ?>">
											<p>Leg Time: <?php echo $runnerBLegTime; ?></p>
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
											<label for="runnerCTime" class="form-label">Gun Time</label>
                        					<input type="time" id="runnerCTime" name="runnerCTime" aria-describedby="runnerCTimeHelp" step="1" value="<?php echo( isset( $runnerCTime ) ) ? $runnerCTime : ''; ?>" class="form-control <?php if ( isset( $runnerCTimeErr ) ) { echo( $runnerCTimeErr == true ) ? 'is-invalid' : 'is-valid'; } ?>">
                        					<div id="runnerCTimeHelp" class="form-text">Enter the gun time for the runner of the third leg.</div>
										</div>
										<div class="mb-3">
											<input type="hidden" name="runnerCLegTime" value="<?php echo( isset( $runnerCLegTime ) ) ? $runnerCLegTime : ''; ?>">
											<p>Leg Time: <?php echo $runnerCLegTime; ?></p>
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