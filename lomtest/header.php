<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">
<header id="header">
<div id="site-title">
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>" rel="home"><img src="<?php echo get_template_directory_uri(); ?>/img/logo.png"></a>
</div>
<nav id="menu-principal">
<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
</nav>

<nav id="menu-social">
<?php get_search_form(); ?>
<?php wp_nav_menu( array( 'theme_location' => 'social-menu' ) ); ?>
</nav>
</header>