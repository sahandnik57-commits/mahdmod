<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function mahdmod_register_mentors_cpt() {
	register_post_type( 'mahdmod_mentor', array(
		'labels' => array(
			'name'          => 'مربیان',
			'singular_name' => 'مربی',
			'add_new'       => 'افزودن مربی',
			'add_new_item'  => 'افزودن مربی جدید',
			'edit_item'     => 'ویرایش مربی',
			'search_items'  => 'جستجوی مربیان',
			'not_found'     => 'مربی‌ای یافت نشد',
			'menu_name'     => 'مربیان',
		),
		'public'       => true,
		'show_in_menu' => 'mahdmod-panel',
		'menu_icon'    => 'dashicons-groups',
		'supports'     => array( 'title', 'editor', 'thumbnail' ),
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'morabi' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'mahdmod_register_mentors_cpt' );

/* ---------------- متاباکس اطلاعات مربی ---------------- */
function mahdmod_mentor_metabox() {
	add_meta_box( 'mahdmod_mentor_info', 'اطلاعات تکمیلی مربی', 'mahdmod_mentor_metabox_html', 'mahdmod_mentor', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mahdmod_mentor_metabox' );

function mahdmod_mentor_metabox_html( $post ) {
	wp_nonce_field( 'mahdmod_mentor_save', 'mahdmod_mentor_nonce' );
	$grade = get_post_meta( $post->ID, '_mahdmod_grade', true );
	$phone = get_post_meta( $post->ID, '_mahdmod_phone', true );
	$experience = get_post_meta( $post->ID, '_mahdmod_experience', true );
	?>
	<p>
		<label><strong>شماره تماس:</strong></label><br>
		<input type="text" name="mahdmod_phone" value="<?php echo esc_attr( $phone ); ?>" style="width:100%;" />
	</p>
	<p>
		<label><strong>گِرید مربی:</strong></label><br>
		<select name="mahdmod_grade" style="width:100%;">
			<option value="A" <?php selected( $grade, 'A' ); ?>>A</option>
			<option value="B" <?php selected( $grade, 'B' ); ?>>B</option>
			<option value="C" <?php selected( $grade, 'C' ); ?>>C</option>
		</select>
	</p>
	<p>
		<label><strong>سابقه کار:</strong></label><br>
		<textarea name="mahdmod_experience" style="width:100%;" rows="4"><?php echo esc_textarea( $experience ); ?></textarea>
	</p>
	<p style="color:#886;">توضیحات و بیوگرافی مربی را در باکس اصلی متن بنویسید. تصویر مربی را از «تصویر شاخص» تنظیم کنید. رشته تخصصی مربی را از باکس «رشته‌های تخصصی» (سمت راست صفحه) انتخاب کنید.</p>
	<?php
}

function mahdmod_mentor_save( $post_id ) {
	if ( ! isset( $_POST['mahdmod_mentor_nonce'] ) || ! wp_verify_nonce( $_POST['mahdmod_mentor_nonce'], 'mahdmod_mentor_save' ) ) { return; }
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( isset( $_POST['mahdmod_phone'] ) ) {
		update_post_meta( $post_id, '_mahdmod_phone', sanitize_text_field( $_POST['mahdmod_phone'] ) );
	}
	if ( isset( $_POST['mahdmod_grade'] ) ) {
		update_post_meta( $post_id, '_mahdmod_grade', sanitize_text_field( $_POST['mahdmod_grade'] ) );
	}
	if ( isset( $_POST['mahdmod_experience'] ) ) {
		update_post_meta( $post_id, '_mahdmod_experience', sanitize_textarea_field( $_POST['mahdmod_experience'] ) );
	}
}
add_action( 'save_post_mahdmod_mentor', 'mahdmod_mentor_save' );
