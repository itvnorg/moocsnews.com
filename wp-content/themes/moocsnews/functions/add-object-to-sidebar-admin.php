<?php 

/*============== Add itvn.org Insert Course Page To Admin ===================*/
add_action( 'admin_menu', 'itvndocorg_admin_menu' );

// Function to add page to admin menu
function itvndocorg_admin_menu(){
	add_menu_page( 
		'itvn.org Manager', 
		'itvn.org', 
		'manage_options', 
		'itvndocorg', 
		'itvndocorg_index_page', 
		'dashicons-paperclip', 
		50  
	);
	
	add_submenu_page(
		'itvndocorg', 
		'itvn.org Insert Courses', 
		'Insert Courses', 
		'manage_options', 
		'itvndocorg/insert-course-admin-page', 
		'insert_course_admin_page'
	);
	
	add_submenu_page(
		'itvndocorg', 
		'itvn.org Clean Courses', 
		'Clean Courses', 
		'manage_options', 
		'itvndocorg/clean-course-admin-page', 
		'clean_course_admin_page'
	);
}

// Function to manage content of page itvn.org index
function itvndocorg_index_page(){
	?>
	<div class="wrap">
		<h2>Welcome To itvn.org tools</h2>
	</div>
	<?php
}

// Function to manage content of page insert course
function insert_course_admin_page(){
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Insert Course</h1>
		<form action="<?php get_site_url; ?>/wp-admin/admin-post.php" method="POST">
			<input type="hidden" name="action" value="itvndocorg_insert_courses_hook">
			<input type="hidden" name="custom_nonce" value="<?php echo $custom_form_nonce; ?>">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content" style="position: relative;">
						<p>
							<label for="course_status">Course Status</label>
							<br>
							<select name="course_status" id="course-status">
									<option value="draft" selected="selected">Draft</option>
									<option value="publish">Publish</option>
							</select>
						</p>

						<p>
							<label for="connection_host">Connection Host</label>
							<br>
							<input type="text" name="connection_host" placeholder="mysql://user:password@host.com:port/database_name" style="width:400px;">
						</p>

						<p>
							<label for="source">Source</label>
							<br>
							<select name="source" id="source">
									<option value="" selected="selected">Select Source of Courses</option>
									<option value="coursera">Coursera</option>
									<option value="edx">edX</option>
							</select>
						</p>
					
						<input type="submit" name="submit" class="button button-primary button-large">
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php
}

// Function to manage content of page clean course
function clean_course_admin_page(){
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Clean Courses</h1>
		<form action="<?php get_site_url; ?>/wp-admin/admin-post.php" method="POST">
			<input type="hidden" name="action" value="itvndocorg_clean_courses_hook">
			<input type="hidden" name="custom_nonce" value="<?php echo $custom_form_nonce; ?>">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content" style="position: relative;">
						<p>
							<label for="course_number">Number Courses 
								<span style="font-size: 12px; color: red;"> (Blank value is all courses)</span>
							</label>
							<br>
							<input type="text" name="course_number" id="course-number" />
						</p>

						<p>
							<label for="source">Source</label>
							<br>
							<select name="source" id="source">
									<option value="" selected="selected">All Source of Courses</option>
									<option value="coursera">Coursera</option>
									<option value="edx">edX</option>
							</select>
						</p>
					
						<input type="submit" name="submit" class="button button-primary button-large">
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php
}