<?php

/*============== Show breadcrumbs on Page ===================*/
function itvndocorg_show_breadcrumb($breadcrumbs){
?>
	<nav aria-label="breadcrumb">
	  	<ol class="breadcrumb">
	  		<?php foreach ($breadcrumbs as $item) {
				if($item['url'] != ''){ ?>
					<li class="breadcrumb-item"><a href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a></li>
				<?php }else{ ?>
					<li class="breadcrumb-item active" aria-current="page"><?php echo $item['title']; ?></li>
				<?php }
			}?>
	  	</ol>
	</nav>
<?php
}

/*============== Show alert and redirect 	===================*/
function show_alert_and_redirect($message){
	echo "<script>alert('".$message."');";
	echo "window.location.href='/wp-admin/admin.php?page=itvndocorg%2Finsert-course-admin-page';";
	echo "</script>";
	exit();
}

/*============== Show the breadcrumb 	===================*/
function the_breadcrumb() {
	global $the_home;
	echo '<nav aria-label="breadcrumb"><ol id="crumbs" class="breadcrumb">';
	if (!is_home()) {
		echo '<li class="breadcrumb-item"><a href="';
		echo $the_home;
		echo '">';
		_e('Home', 'moocsnews');
		echo "</a></li>";
		if (is_taxonomy('subject') || is_single()) {
			if(is_taxonomy('subject')){
				echo '<li class="breadcrumb-item"><a href="'.$the_home.'/subjects'.'">';
				_e('Subjects', 'moocsnews');
				echo '</a></li><li class="breadcrumb-item">';
				$term = get_queried_object();
				echo $term->name;
				echo '</li>';
			}
			if (is_single()) {
				echo '<li class="breadcrumb-item"><a href="'.$the_home.'/courses'.'">';
				_e('Courses', 'moocsnews');
				echo '</a></li><li class="breadcrumb-item">';
				the_title();
				echo '</li>';
			}
		} elseif (is_page()) {
			echo '<li class="breadcrumb-item">';
			echo the_title();
			echo '</li>';
		}
		elseif (is_tag()) {
			$current_tag = single_tag_title("", false);
			echo '<li class="breadcrumb-item">';
			echo $current_tag;
			echo '</li>';
		}
	}
	echo '</ol></nav>';
}