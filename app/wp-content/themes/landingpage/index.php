<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo bloginfo('name'); ?></title>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() . "/style.css"?>" >
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() . "/fonts.css"?>" >
</head>
<body>
	<?php include get_template_directory() . "/parts/header.php"; ?>
	<section id="hero-header">
		<div id="hero-header--content">
			<div id="hero-header--content--text">
				<h1><?php echo esc_html( get_theme_mod('hero_title', 'A lifelong pomodoro') ); ?></h1>
				<h4><?php echo esc_html( get_theme_mod('hero_tagline', 'Our pomodoro never sleeps, never stop, and never stop working.') ); ?></h4>
			</div>
			<a id="hero-header--content--link" style="background-color: <?php echo esc_attr(get_theme_mod('colors_2', '#ffffff')); ?> ; color: <?php echo esc_attr(get_theme_mod('colors_3', '#ffffff')); ?>" href="<?php echo esc_html( get_theme_mod('hero_ctalink', 'Get Started') ); ?>"><?php echo esc_html( get_theme_mod('hero_ctatext') ); ?></a>
		</div>
		<div id="hero-header--image">
			<img src="<?php echo esc_url( get_theme_mod('hero_mockup_image') ); ?>" alt="">
		</div>
	</section>
	<!-- <section id="statistics"></section> -->
	<section id="testimonials">
		<h1>What do people think of us : </h1>
		<div id="testimonials--wrapper">
		<?php 
			$testimonials = new WP_Query([
				'post_type' => 'testimonial',
				'posts_per_page' => -1,
			]);

			if ($testimonials->have_posts()) {
				while($testimonials->have_posts()) {
					$testimonials->the_post();

					$company = get_post_meta(get_the_ID(), '_client_company', true);
					$position = get_post_meta(get_the_ID(), '_client_position', true);
					$rating = get_post_meta(get_the_ID(), '_client_rating', true);
					$testimonial = get_post_meta(get_the_ID(), '_client_testimonial', true);
					$thumb = get_the_post_thumbnail(get_the_ID(), 'thumbnail');

					?>
					<div class="testimonial-card" style="
						display: flex; 
						gap: 20px; 
						padding: 20px; 
						margin-bottom: 20px; 
						border: 1px solid #ddd; 
						border-radius: 10px; 
						background-color: #f9f9f9;
						align-items: flex-start;
					">
						<div class="testimonial-photo" style="
							flex-shrink: 0;
							width: 80px; 
							height: 80px; 
							border-radius: 50%; 
							overflow: hidden;
							img {
								background-position: center;
							}
						">
							<?php 
							if ($thumb) {
								echo '<img src="' . get_the_post_thumbnail_url(get_the_ID(),'thumbnail') . '" style="
									width:100%;
									height:100%;
									object-fit: cover;
									object-position: center;
								" />';
							} else {
								echo '<div style="
									width:80px;
									height:80px;
									background:#ccc;
									border-radius:50%;
								"></div>';
							}
							?>
						</div>
						<div class="testimonial-content" style="flex:1;">
							<p class="testimonial-text" style="
								font-size: 16px; 
								line-height: 1.5; 
								margin-bottom: 10px;
								color: #333;
							"><?php echo esc_html($testimonial); ?></p>
							<p class="testimonial-client" style="
								font-weight: bold; 
								font-size: 14px; 
								margin-bottom: 5px; 
								color: #555;
							"><?php echo esc_html($position . ' @ ' . $company); ?></p>
							<p class="testimonial-rating" style="
								color: #f5a623; 
								font-size: 14px;
							"><?php echo str_repeat('⭐', intval($rating)); ?></p>
						</div>
					</div>
					<?php
				}
				wp_reset_postdata();
			}
			?>
		</div>
	</section>
	<section id="sponsors">
		<h1>This project is propulsed byS : </h1>
		<div id="sponsors--wrapper">
		<?php 
			$sponsors = new WP_Query([
				'post_type' => 'sponsor',
				'post_peer_page' => -1,
			]);

			if ($sponsors->have_posts()) {
				while ($sponsors->have_posts()) {
					$sponsors->the_post();
					?>
					<a href="<?php echo get_post_meta(get_the_ID(), '_sponsor_url', true);?>" class="sponsor-image">
						<?php the_post_thumbnail(); ?>
					</a>
					<?php
				}
				wp_reset_postdata();
			}
		?>
		</div>
	</section>
	<section id="footer-cta"></section>
	<?php include get_template_directory() . "/parts/footer.php"; ?>
</body>
</html>