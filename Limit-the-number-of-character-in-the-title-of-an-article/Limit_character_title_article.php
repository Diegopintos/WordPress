<?php
/*
Plugin Name: Truncate titles // Tronquer les titres
Description: Limit the number of characters in the title of an article // Limiter le nombre de caractère dans le titre d'un article
Version: 1.0.1 - 12 04 2014
Author: lesurfeur
*/
/*****************
**  UTILISATION **
******************
Replace in your theme (Remplacer dans) content.php :
<?php if ( is_single() ) {

by (par) 

<?php if ( is_single() || is_search() ) { ?>

Replace (remplacer) :

<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'Your_Theme' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>

by (par)

<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'Your_Theme' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php shortened_title(); ?></a>

******************
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
