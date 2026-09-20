<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function mahdmod_register_news_cpt() {
	register_post_type( 'mahdmod_news', array(
		'labels' => array(
			'name'               => 'اخبار مهدمد',
			'singular_name'      => 'خبر',
			'add_new'            => 'افزودن خبر',
			'add_new_item'       => 'افزودن خبر جدید',
			'edit_item'          => 'ویرایش خبر',
			'new_item'           => 'خبر جدید',
			'view_item'          => 'مشاهده خبر',
			'search_items'       => 'جستجوی اخبار',
			'not_found'          => 'خبری یافت نشد',
			'menu_name'          => 'اخبار',
		),
		'public'       => true,
		'show_in_menu' => 'mahdmod-panel',
		'menu_icon'    => 'dashicons-megaphone',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'akhbar' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'mahdmod_register_news_cpt' );
