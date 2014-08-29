/******************
** UTILISATION **
******************
Replace in your theme (Remplacer dans) content.php :
<?php if ( is_single() ) {
by (par)
<?php if ( is_single() || is_search() ) { ?>
Replace (remplacer) :
<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'Your_Theme' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
by (par)
<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'Your_Theme' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php shortened_title(); ?></a>
******************/
