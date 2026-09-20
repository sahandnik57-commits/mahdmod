<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function mahdmod_register_specialty_taxonomy() {
	register_taxonomy( 'mahdmod_specialty', array( 'mahdmod_mentor' ), array(
		'labels' => array(
			'name'          => 'رشته‌های تخصصی',
			'singular_name' => 'رشته تخصصی',
			'add_new_item'  => 'افزودن رشته تخصصی',
			'edit_item'     => 'ویرایش رشته تخصصی',
			'menu_name'     => 'رشته‌های تخصصی',
		),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'public'            => true,
		'rewrite'           => array( 'slug' => 'reshte-takhasosi' ),
	) );

	register_taxonomy( 'mahdmod_rep_type', array( 'mahdmod_representative' ), array(
		'labels' => array(
			'name'          => 'نوع نمایندگی',
			'singular_name' => 'نوع نمایندگی',
			'add_new_item'  => 'افزودن نوع نمایندگی',
			'edit_item'     => 'ویرایش نوع نمایندگی',
			'menu_name'     => 'نوع نمایندگی',
		),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'public'            => true,
		'rewrite'           => array( 'slug' => 'noe-namayandegi' ),
	) );
}
add_action( 'init', 'mahdmod_register_specialty_taxonomy' );
