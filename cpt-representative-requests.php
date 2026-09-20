<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function mahdmod_register_representative_requests_cpt() {
	register_post_type( 'mahdmod_rep_req', array(
		'labels' => array(
			'name'          => 'درخواست‌های نمایندگی',
			'singular_name' => 'درخواست نمایندگی',
			'edit_item'     => 'مشاهده درخواست',
			'search_items'  => 'جستجوی درخواست‌ها',
			'not_found'     => 'درخواستی یافت نشد',
			'menu_name'     => 'درخواست‌های نمایندگی',
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => 'mahdmod-panel',
		'menu_icon'       => 'dashicons-admin-network',
		'supports'        => array( 'title' ),
		'capability_type' => 'post',
		'map_meta_cap'    => true,
	) );
}
add_action( 'init', 'mahdmod_register_representative_requests_cpt' );

function mahdmod_rep_req_columns( $columns ) {
	$new = array();
	$new['cb'] = $columns['cb'];
	$new['title'] = 'نام درخواست‌دهنده';
	$new['phone'] = 'موبایل';
	$new['city'] = 'استان/شهر';
	$new['rep_type'] = 'نوع نمایندگی';
	$new['date'] = $columns['date'];
	return $new;
}
add_filter( 'manage_mahdmod_rep_req_posts_columns', 'mahdmod_rep_req_columns' );

function mahdmod_rep_req_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'phone':
			echo esc_html( get_post_meta( $post_id, '_phone', true ) );
			break;
		case 'city':
			echo esc_html( get_post_meta( $post_id, '_province', true ) ) . ' / ' . esc_html( get_post_meta( $post_id, '_city', true ) );
			break;
		case 'rep_type':
			echo esc_html( get_post_meta( $post_id, '_rep_type', true ) );
			break;
	}
}
add_action( 'manage_mahdmod_rep_req_posts_custom_column', 'mahdmod_rep_req_column_content', 10, 2 );

function mahdmod_rep_req_metabox() {
	add_meta_box( 'mahdmod_rep_req_details', 'جزئیات درخواست نمایندگی', 'mahdmod_rep_req_metabox_html', 'mahdmod_rep_req', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mahdmod_rep_req_metabox' );

function mahdmod_rep_req_metabox_html( $post ) {
	$fields = array(
		'_phone'          => 'شماره موبایل',
		'_province'       => 'استان',
		'_city'           => 'شهر',
		'_rep_type'       => 'نوع نمایندگی درخواستی',
		'_agency_name'    => 'نام نمایندگی',
		'_experience'     => 'سابقه کار',
		'_license_no'     => 'شماره پروانه کسب',
		'_license_date'   => 'تاریخ صدور پروانه',
		'_license_place'  => 'محل صدور پروانه',
	);
	echo '<table class="widefat"><tbody>';
	foreach ( $fields as $key => $label ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<tr><th style="width:200px;">' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( $val ) ) . '</td></tr>';
	}
	echo '</tbody></table>';
}
