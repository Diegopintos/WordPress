<?php
/**
 * Plugin Name: Tronquer les titres
 * Plugin URI: https://github.com/lesurfeur/Plugin-WP
 * Description: Limiter le nombre de caractère dans le titre d'un article.
 * Version: Version 1.0.0
 * Author: lesurfeur
 * Author URI:  https://github.com/lesurfeur
 * License: GPL2 license
 */

/******************
 **  UTILISATION **
 ******************
 Chercher dans votre theme content.php et remplacer :
 <?php if ( is_single() ) {

 Par :

 <?php if ( is_single() || is_search() ) { ?>

 Remplacer :

 <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'Votre_Theme' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>

 Par :

 <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'Votre_Theme' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php shortened_title(); ?></a>

 ******************/

function shortened_title() {

	$original_title = get_the_title();
	$title = html_entity_decode($original_title, ENT_QUOTES, "UTF-8");
	// the number of character (indiquer le nombre de caratère)
	$limit = "24";
	// end of the title cut (fin du titre couper)
	$ending="...";

	if(strlen($title) >= ($limit+3)) {
	$title = mb_substr($title, 0, $limit) . $ending;
	}

	echo $title;
}
