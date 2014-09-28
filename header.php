<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Shiftwp
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'shiftwp' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="wrapper-wide">

			<div class="site-branding">
				<h1 id="logo" class="site-title">
					<a class="logo-text" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</h1>
			</div>

			<button class="menu-toggle"><i class="fa fa-bars"></i><i class="fa fa-times"></i><span><?php _e( 'Primary Menu', 'shiftwp' ); ?></span></button>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
				<ul class="hrd_cnt">
					<li>
						<a href="/sign-up-to-newsletter"><span class="fa-stack fa-lg"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-envelope-o fa-stack-1x"></i></span> Sign up to our e-newsletter</a>
					</li>
					<li>
						<span class="fa-stack fa-lg"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-phone fa-stack-1x"></i></span> Call us: +44 (0)20 7148 7666
					</li>
				</ul>
			</nav><!-- #site-navigation -->
			<p><?php bloginfo( 'description' ); ?></p>

		</div>
	</header><!-- #masthead -->

	<?php
	if( is_single() || is_front_page() ) {

		$bannerImage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

	}else if( is_author() ) {

		global $wp_query;
		$curauth = $wp_query->get_queried_object();
		$personAvatar = get_user_meta( $curauth->ID, 'avatar', true);
		$bannerImage = wp_get_attachment_image_src( $personAvatar, 'full' );
		
	}else {

		$bannerImage = wp_get_attachment_image_src( '8063', 'full' );

	}

	if( $bannerImage[0] ) {	?>
	<div class="banner" style="background-image: url(<?php echo $bannerImage[0]; ?>);">

		<?php if( get_post_type( $post ) == 'product' ) { ?>

			<?php echo '<span class="info"><h1 class="name">' . get_the_title() . '</h1><span class="title">' . get_post_meta( get_the_ID(), 'what_does_it_do', true ) . '</span></span>'; ?>
		
			<a class="nav-btn all" href="/products"><i class="fa fa-th"></i> All Products</a>
			<?php
			$next_post = get_next_post();
			if (!empty( $next_post )): ?>
			  <a class="nav-btn next" href="<?php echo get_permalink( $next_post->ID ); ?>">Next Project</a>
			<?php endif;

			// Find connected pages
			global $post_to_product;
			$post_to_product = new WP_Query( array(
				'connected_type' => 'post_to_product',
				'connected_items' => get_queried_object(),
				'nopaging' => true,
			) );

			// Find connected pages
			global $research_to_product;
			$research_to_product = new WP_Query( array(
			  'connected_type' => 'research_to_product',
			  'connected_items' => get_queried_object(),
			  'nopaging' => true,
			) );

			?>

			<ul class="subnav">
				<li><a href="#overview">Overview</a></li>
				<?php if ( $research_to_product->have_posts() ) { ?><li><a href="#research">Research</a></li><?php } ?>
				<?php if ( $post_to_product->have_posts() ) { ?><li><a href="#comment">Comment</a></li><?php } ?>
				<?php if( get_post_meta( get_the_ID(), 'coverage', true ) ) { ?><li><a href="#coverage">Coverage</a></li><?php } ?>
				<?php if( get_post_meta( get_the_ID(), 'partners', true ) ) { ?><li><a href="#partners">Partners</a></li><?php } ?>
				<?php if( get_post_meta( get_the_ID(), 'jobs', true ) ) { ?><li><a href="#jobs">Jobs</a></li><?php } ?>
				<?php if( get_post_meta( get_the_ID(), 'awards', true ) ) { ?><li><a href="#awards">Awards</a></li><?php } ?>
				<?php if( get_post_meta( get_the_ID(), 'link', true ) ) { ?><li><a target="_blank" href="<?php echo get_post_meta( get_the_ID(), 'link', true ); ?>">Website</a></li><?php } ?>
			</ul>

		<?php }	?>

		<?php if( is_author() ) { ?>
			
			<?php echo '<span class="info"><h1 class="name">' . $curauth->first_name . ' ' . $curauth->last_name . '</h1><span class="title">' . $curauth->job_title . '</span></span>'; ?>

			<a class="nav-btn all" href="/people"><i class="fa fa-th"></i> All People</a>
			<?php 
				$nextuser = get_users( array(
					'meta_query' => array(
						array(
							'key' => 'corder',
							'type' => 'numeric',
							'value' => (get_user_meta($curauth->ID, 'corder', true)+1),
							'compare' => '=',
						),
					),
					'orderby' => 'meta_value',
				) );

				foreach ( $nextuser as $user ) { ?>
				<a class="nav-btn next" href="/people/<?php echo $user->user_nicename; ?>"><i class="fa fa-angle-right"></i> Next Profile</a>
				<?php }

				// Find connected research
				global $connected_research;
				$connected_research = new WP_Query( array(
				  'connected_type' => 'research_to_user',
				  'connected_items' => get_queried_object(),
				  'nopaging' => true,
				) );

				// Find connected products
				global $connected_product;
				$connected_product = new WP_Query( array(
				  'connected_type' => 'product_to_users',
				  'connected_items' => get_queried_object(),
				  'nopaging' => true,
				) );

				$args = array(
					'author'        =>  $curauth->ID, // I could also use $user_ID, right?
					'orderby'       =>  'post_date',
					'order'         =>  'ASC' 
				);

				// get his posts 'ASC'
				global $current_user_posts;
				$current_user_posts = get_posts( $args );
			?>

			<ul class="subnav">
				<li><a href="#profile">Profile</a></li>
				<?php if ( count($current_user_posts) > 0 ) { ?><li><a href="#comment">Comment</a></li><?php } ?>
				<?php if ( $connected_research->have_posts() ) { ?><li><a href="#research">Research</a></li><?php } ?>
				<?php if ( $connected_product->have_posts() ) { ?><li><a href="#products">Products</a></li><?php } ?>
			</ul>

		<?php }	?>

	</div>
	<?php }	?>


	<div id="content" class="site-content <?php if( is_archive() || is_front_page() || $post->post_name == 'people' ) { ?>wrapper-wide<?php } else { ?>wrapper<?php } ?>">
