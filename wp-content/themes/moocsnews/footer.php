		<section class="follow-us text-center">
			<div class="container">
				<h5 class="mb-4"><span class="text-uppercase text-blue font-weight-bold"><?php echo get_bloginfo('name'); ?></span></h5>
				<p class="mb-4 font-size-20">Stay up to date. Follow us on</p>
				<a href="#" class="text-blue"><i class="fab fa-facebook-square fa-3x"></i></a>
			</div>
		</section>
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-md-3">
						<h6 class="section-title"><?php echo __('Browse online courses and MOOCs By'); ?></h6>
						<ul class="list-horizontal">
							<li class="list-horizontal-item"><a class="text-blue text-link" href="#">Providers</a></li>
							<li class="list-horizontal-item"><a class="text-blue text-link" href="#">Institutions</a></li>
							<li class="list-horizontal-item"><a class="text-blue text-link" href="#">Categories</a></li>
						</ul>
					</div>
					<div class="col-md-3">
						<h6 class="section-title"><?php echo __('Quick Links'); ?></h6>
						<ul class="list-horizontal">
							<li class="list-horizontal-item"><a class="text-blue text-link" href="#">Providers</a></li>
							<li class="list-horizontal-item"><a class="text-blue text-link" href="#">Institutions</a></li>
							<li class="list-horizontal-item"><a class="text-blue text-link" href="#">Categories</a></li>
						</ul>
					</div>
					<div class="col-md-6">
						<h6 class="section-title"><?php echo __(get_bloginfo('name').' Newsletter'); ?></h6>
						<div class="row">
							<form class="form-inline subscribe-section">
								<div class="form-group mx-sm-3 mb-2">
									<label for="email" class="sr-only">Email</label>
									<input type="email" class="form-control" id="email" placeholder="Email">
								</div>
								<button type="submit" class="btn btn-primary mb-2">Subscribe</button>
							</form>
						</div>
						<h6><?php echo __('About '.get_bloginfo('name')); ?></h6>
						<p>Moocsnews is a search engine and reviews site for free online courses popularly known as MOOCs or Massive Open Online Courses.</p>
					</div>
				</div>
				<div class="row copyright">
					<div class="col-md-12"><span class="site-name"><?php echo get_bloginfo('name'); ?></span> © Copyright 2018</div>
				</div>
			</div>
		</footer>
		<?php wp_footer(); ?>
	</body>
	</html>