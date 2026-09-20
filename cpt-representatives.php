<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function mahdmod_register_representatives_cpt() {
	register_post_type( 'mahdmod_representative', array(
		'labels' => array(
			'name'          => 'نمایندگان',
			'singular_name' => 'نماینده',
			'add_new'       => 'افزودن نماینده',
			'add_new_item'  => 'افزودن نماینده جدید',
			'edit_item'     => 'ویرایش نماینده',
			'search_items'  => 'جستجوی نمایندگان',
			'not_found'     => 'نماینده‌ای یافت نشد',
			'menu_name'     => 'نمایندگان',
		),
		'public'       => true,
		'show_in_menu' => 'mahdmod-panel',
		'menu_icon'    => 'dashicons-store',
		'supports'     => array( 'title', 'editor', 'thumbnail' ),
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'namayandeh' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'mahdmod_register_representatives_cpt' );

function mahdmod_representative_metabox() {
	add_meta_box( 'mahdmod_rep_info', 'اطلاعات نماینده', 'mahdmod_representative_metabox_html', 'mahdmod_representative', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mahdmod_representative_metabox' );

function mahdmod_representative_metabox_html( $post ) {
	wp_nonce_field( 'mahdmod_rep_save', 'mahdmod_rep_nonce' );
	$fields = array(
		'phone'         => 'شماره موبایل',
		'province'      => 'استان',
		'city'          => 'شهر',
		'agency_name'   => 'نام نمایندگی',
		'license_no'    => 'شماره پروانه کسب',
		'license_date'  => 'تاریخ صدور پروانه',
		'license_place' => 'محل صدور پروانه',
	);
	foreach ( $fields as $key => $label ) {
		$val = get_post_meta( $post->ID, '_' . $key, true );
		echo '<p><label><strong>' . esc_html( $label ) . ':</strong></label><br>';
		echo '<input type="text" name="mahdmod_rep_' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" style="width:100%;" /></p>';
	}
	echo '<p style="color:#886;">سابقه کار و توضیحات کامل را در باکس اصلی متن بنویسید. «نوع نمایندگی» (معین/خاص) را از باکس سمت راست انتخاب کنید.</p>';
}

function mahdmod_representative_save( $post_id ) {
	if ( ! isset( $_POST['mahdmod_rep_nonce'] ) || ! wp_verify_nonce( $_POST['mahdmod_rep_nonce'], 'mahdmod_rep_save' ) ) { return; }
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	$fields = array( 'phone', 'province', 'city', 'agency_name', 'license_no', 'license_date', 'license_place' );
	foreach ( $fields as $key ) {
		$field_name = 'mahdmod_rep_' . $key;
		if ( isset( $_POST[ $field_name ] ) ) {
			update_post_meta( $post_id, '_' . $key, sanitize_text_field( $_POST[ $field_name ] ) );
		}
	}
}
add_action( 'save_post_mahdmod_representative', 'mahdmod_representative_save' );
