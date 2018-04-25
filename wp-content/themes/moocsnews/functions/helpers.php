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