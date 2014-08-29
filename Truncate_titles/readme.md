<h3>Truncate Titles</h3>
___

<strong>How to use</strong>

Locate and open the file content.php in your theme:
 - Replace:

```php
<?php if ( is_single() ) {
```
 - by:
  
 
```
<?php if ( is_single() || is_search() ) { ?>
```
 - Replace:

```
<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'Your_Theme' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
```
 - by:

```
<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'Your_Theme' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php shortened_title(); ?></a>
```
