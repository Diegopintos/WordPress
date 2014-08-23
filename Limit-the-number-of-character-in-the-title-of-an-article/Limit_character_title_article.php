<?php
/*
Plugin Name: Limiter le nombre de caractère dans le titre d'un article
Description: Limiter le nombre de caractère dans le titre d'un article
Version: 12 04 2014
Author: lesurfeur
*/

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
