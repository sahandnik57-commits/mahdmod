<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function mahdmod_register_mentor_requests_cpt() {
	register_post_type( 'mahdmod_mentor_req', array(
		'labels' => array(
			'name'          => 'درخواست‌های مربیگری',
			'singular_name' => 'درخواست مربیگری',
			'edit_item'     => 'مشاهده درخواست',
			'search_items'  => 'جستجوی درخواست‌ها',
			'not_found'     => 'درخواستی یافت نشد',
			'menu_name'     => 'درخواست‌های مربیگری',
		),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => 'mahdmod-panel',
		'menu_icon'           => 'dashicons-id-alt',
		'supports'            => array( 'title' ),
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
	) );
}
add_action( 'init', 'mahdmod_register_mentor_requests_cpt' );

/* ستون‌های سفارشی لیست درخواست‌ها */
function mahdmod_mentor_req_columns( $columns ) {
	$new = array();
	$new['cb'] = $columns['cb'];
	$new['title'] = 'نام درخواست‌دهنده';
	$new['phone'] = 'شماره تماس';
	$new['specialty'] = 'رشته تخصصی';
	$new['grade'] = 'گرید درخواستی';
	$new['date'] = $columns['date'];
	return $new;
}
add_filter( 'manage_mahdmod_mentor_req_posts_columns', 'mahdmod_mentor_req_columns' );

function mahdmod_mentor_req_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'phone':
			echo esc_html( get_post_meta( $post_id, '_phone', true ) );
			break;
		case 'specialty':
			echo esc_html( get_post_meta( $post_id, '_specialty', true ) );
			break;
		case 'grade':
			echo esc_html( get_post_meta( $post_id, '_grade', true ) );
			break;
	}
}
add_action( 'manage_mahdmod_mentor_req_posts_custom_column', 'mahdmod_mentor_req_column_content', 10, 2 );

/* متاباکس نمایش جزئیات کامل درخواست برای ادمین */
function mahdmod_mentor_req_metabox() {
	add_meta_box( 'mahdmod_mentor_req_details', 'جزئیات درخواست مربیگری', 'mahdmod_mentor_req_metabox_html', 'mahdmod_mentor_req', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mahdmod_mentor_req_metabox' );

function mahdmod_mentor_req_metabox_html( $post ) {
	$fields = array(
		'_phone'      => 'شماره تماس',
		'_specialty'  => 'رشته تخصصی',
		'_grade'      => 'گرید درخواستی',
		'_experience' => 'سابقه کار',
		'_bio'        => 'بیوگرافی',
	);
	echo '<table class="widefat"><tbody>';
	foreach ( $fields as $key => $label ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<tr><th style="width:180px;">' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( $val ) ) . '</td></tr>';
	}
	echo '</tbody></table>';
	echo '<p style="margin-top:10px;color:#886;">پس از بررسی، در صورت تأیید می‌توانید از منوی «مربیان» یک مربی جدید با همین مشخصات ثبت کنید.</p>';
}
