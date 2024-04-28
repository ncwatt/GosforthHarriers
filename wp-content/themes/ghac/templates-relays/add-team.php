<?php 
/*
	Template Name: Summer Relays - Add Team
*/

// Check that the user is logged in - if not redirect to login page
if ( ! is_user_logged_in() ) { wp_redirect( wp_login_url() . '?redirect_to=' . urlencode( $_SERVER['REQUEST_URI'] ) ); }

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	if ( isset( $_POST['addTeamForm'] ) ) {
		// Get the values from the form
		$teamNum = isset( $_POST['teamNumber'] ) ? form_input_checks( $_POST['teamNumber'] ) : null;
		$teamNumAuto = isset( $_POST['teamNumberAuto'] ) ? true : false;
		$teamName = form_input_checks( $_POST['teamName'] );
		$category = form_input_checks( $_POST["category"] );
		$clubName = form_input_checks( $_POST['clubName'] );
		$email = form_input_checks( $_POST["emailAddress"] );
		$firstName = form_input_checks( $_POST["firstName"] );
		$lastName = form_input_checks( $_POST["lastName"] );

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
		if ( ! ( strlen( $clubName ) > 0 && strlen( $clubName ) <= 75 ) ) {
            $clubNameErr = true;
			$errors[] = 'You have not entered the running club name that this team is associated with';
            $postSuccess = false;
        }

		// Validate the first name
		$firstNameErr = false;
		if ( ! ( strlen( $firstName ) > 0 && strlen( $firstName ) <= 35 ) ) {
			$firstNameErr = true;
			$errors[] = 'You have not entered the first name of the club contact';
			$postSuccess = false;
		}

		// Validate the last name
		$lastNameErr = false;
		if ( ! ( strlen( $lastName ) > 0 && strlen( $lastName ) <= 35 ) ) {
			$lastNameErr = true;
			$errors[] = 'You have not entered the last name of the club contact';
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
				'ClubName' => $clubName,
			) );
			$teamID = $wpdb->insert_id;

			// Check if the email address is already in the table
			$emailID = $wpdb->get_var( $wpdb->prepare( "SELECT EmailID FROM {$wpdb->prefix}ghac_c_emails WHERE EmailAddress = %s", $email ) );
			if ( ! isset( $emailID ) ) {
				// Insert the email into the table
				$wpdb->insert( "{$wpdb->prefix}ghac_c_emails", 
					array(
						'EmailAddress' => $email,
						'FirstName' => $firstName,
						'LastName' => $lastName
					) 
				);
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
?>

<?php get_header(); ?>
<div class="content-1">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php if ( ! is_user_in_role( 'administrator' ) ) : ?>
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
                	<form id="contactForm" method="post" action="" novalidate class="mb-4 mb-lg-0">
                    	<input type="hidden" name="addTeamForm" value="1" />
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
										Club Details
									</div>
									<div class="card-body">
										<div class="mb-3">
											<label for="clubName" class="form-label">Club Name</label>
                        					<input type="text" id="clubName" name="clubName" aria-describedby="clubNameHelp" maxlength="75" value="<?php echo( isset( $clubName ) ) ? $clubName : ''; ?>" class="form-control <?php if ( isset( $clubNameErr ) ) { echo( $clubNameErr == true ) ? 'is-invalid' : 'is-valid'; } ?>" >
                        					<div id="clubNameHelp" class="form-text">Enter the name of the running club to associate this team with.</div>
										</div>
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
										<div class="mb-3">
											<label for="emailAddress" class="form-label">Email address</label>
                        					<input type="email" id="emailAddress" name="emailAddress" aria-describedby="emailAddressHelp" maxlength="254" value="<?php echo( isset( $email ) ) ? $email : ''; ?>" class="form-control <?php if ( isset( $emailErr ) ) { echo( $emailErr == true ) ? 'is-invalid' : 'is-valid'; } ?>" >
                        					<div id="emailAddressHelp" class="form-text">Enter the email address of the club contact used during registration. Additional email addresses can be added later.</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<button type="submit" class="btn btn-primary">Add Team</button>&nbsp;&nbsp;
								<a href="<?php echo get_page_permalink_by_pageslug( 'summer-relays/teams-manager' ); ?>" class="btn btn-secondary">Cancel</a>
							</div>
						</div>
                	</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>