<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ================= فرم درخواست مربیگری ================= */
function mahdmod_mentor_form_shortcode() {
	$specialties = get_terms( array( 'taxonomy' => 'mahdmod_specialty', 'hide_empty' => false ) );
	ob_start();

	if ( isset( $_GET['mahdmod_mentor_sent'] ) ) {
		echo '<div class="mahdmod-alert mahdmod-alert-success">درخواست همکاری شما با موفقیت ثبت و برای ادمین ارسال شد. با شما تماس گرفته خواهد شد.</div>';
	}
	?>
	<form class="mahdmod-form" method="post" action="">
		<?php wp_nonce_field( 'mahdmod_mentor_form', 'mahdmod_mentor_form_nonce' ); ?>
		<input type="hidden" name="mahdmod_action" value="mentor_request" />

		<div class="mahdmod-form-row">
			<label>نام و نام خانوادگی *</label>
			<input type="text" name="full_name" required />
		</div>
		<div class="mahdmod-form-row">
			<label>شماره تماس *</label>
			<input type="tel" name="phone" required />
		</div>
		<div class="mahdmod-form-row">
			<label>رشته تخصصی *</label>
			<select name="specialty" required>
				<option value="">انتخاب کنید</option>
				<?php if ( ! is_wp_error( $specialties ) ) : foreach ( $specialties as $term ) : ?>
					<option value="<?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></option>
				<?php endforeach; endif; ?>
			</select>
		</div>
		<div class="mahdmod-form-row">
			<label>گِرید درخواستی *</label>
			<select name="grade" required>
				<option value="A">A</option>
				<option value="B">B</option>
				<option value="C">C</option>
			</select>
		</div>
		<div class="mahdmod-form-row">
			<label>سابقه کار *</label>
			<textarea name="experience" rows="4" required></textarea>
		</div>
		<div class="mahdmod-form-row">
			<label>بیوگرافی *</label>
			<textarea name="bio" rows="4" required></textarea>
		</div>
		<button type="submit" class="mahdmod-btn mahdmod-btn-primary">ثبت درخواست مربیگری</button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'mahdmod_mentor_form', 'mahdmod_mentor_form_shortcode' );

function mahdmod_handle_mentor_form() {
	if ( ! isset( $_POST['mahdmod_action'] ) || $_POST['mahdmod_action'] !== 'mentor_request' ) { return; }
	if ( ! isset( $_POST['mahdmod_mentor_form_nonce'] ) || ! wp_verify_nonce( $_POST['mahdmod_mentor_form_nonce'], 'mahdmod_mentor_form' ) ) { return; }

	$full_name  = sanitize_text_field( $_POST['full_name'] ?? '' );
	$phone      = sanitize_text_field( $_POST['phone'] ?? '' );
	$specialty  = sanitize_text_field( $_POST['specialty'] ?? '' );
	$grade      = sanitize_text_field( $_POST['grade'] ?? '' );
	$experience = sanitize_textarea_field( $_POST['experience'] ?? '' );
	$bio        = sanitize_textarea_field( $_POST['bio'] ?? '' );

	if ( ! $full_name || ! $phone ) { return; }

	$post_id = wp_insert_post( array(
		'post_type'   => 'mahdmod_mentor_req',
		'post_title'  => $full_name,
		'post_status' => 'publish',
	) );

	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_phone', $phone );
		update_post_meta( $post_id, '_specialty', $specialty );
		update_post_meta( $post_id, '_grade', $grade );
		update_post_meta( $post_id, '_experience', $experience );
		update_post_meta( $post_id, '_bio', $bio );

		$admin_email = get_option( 'admin_email' );
		wp_mail( $admin_email, 'درخواست همکاری مربیگری جدید', "نام: {$full_name}\nتماس: {$phone}\nرشته: {$specialty}\nگرید: {$grade}" );

		wp_safe_redirect( add_query_arg( 'mahdmod_mentor_sent', '1', wp_get_referer() ) );
		exit;
	}
}
add_action( 'init', 'mahdmod_handle_mentor_form' );

/* ================= فرم درخواست نمایندگی ================= */
function mahdmod_representative_form_shortcode() {
	ob_start();
	if ( isset( $_GET['mahdmod_rep_sent'] ) ) {
		echo '<div class="mahdmod-alert mahdmod-alert-success">درخواست نمایندگی شما با موفقیت ثبت شد. کارشناسان ما با شما تماس خواهند گرفت.</div>';
	}
	$moein = get_option( 'mahdmod_shivenameh_moein', '' );
	$khas  = get_option( 'mahdmod_shivenameh_khas', '' );
	?>
	<div class="mahdmod-shivenameh-links">
		<?php if ( $moein ) : ?><a href="<?php echo esc_url( $moein ); ?>" target="_blank" class="mahdmod-btn mahdmod-btn-outline">دانلود شیوه‌نامه نمایندگی معین</a><?php endif; ?>
		<?php if ( $khas ) : ?><a href="<?php echo esc_url( $khas ); ?>" target="_blank" class="mahdmod-btn mahdmod-btn-outline">دانلود شیوه‌نامه نمایندگی خاص</a><?php endif; ?>
	</div>
	<form class="mahdmod-form" method="post" action="">
		<?php wp_nonce_field( 'mahdmod_rep_form', 'mahdmod_rep_form_nonce' ); ?>
		<input type="hidden" name="mahdmod_action" value="rep_request" />

		<div class="mahdmod-form-row"><label>نام و نام خانوادگی *</label><input type="text" name="full_name" required /></div>
		<div class="mahdmod-form-row"><label>شماره موبایل *</label><input type="tel" name="phone" required /></div>
		<div class="mahdmod-form-row"><label>استان *</label><input type="text" name="province" required /></div>
		<div class="mahdmod-form-row"><label>شهر *</label><input type="text" name="city" required /></div>
		<div class="mahdmod-form-row">
			<label>نوع نمایندگی *</label>
			<select name="rep_type" required>
				<option value="نمایندگی معین">نمایندگی معین</option>
				<option value="نمایندگی خاص">نمایندگی خاص</option>
			</select>
		</div>
		<div class="mahdmod-form-row"><label>نام نمایندگی *</label><input type="text" name="agency_name" required /></div>
		<div class="mahdmod-form-row"><label>سابقه کار *</label><textarea name="experience" rows="3" required></textarea></div>
		<div class="mahdmod-form-row"><label>شماره پروانه کسب</label><input type="text" name="license_no" /></div>
		<div class="mahdmod-form-row"><label>تاریخ صدور پروانه</label><input type="text" name="license_date" placeholder="مثال: 1403/05/10" /></div>
		<div class="mahdmod-form-row"><label>محل صدور پروانه</label><input type="text" name="license_place" /></div>

		<button type="submit" class="mahdmod-btn mahdmod-btn-primary">ثبت درخواست نمایندگی</button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'mahdmod_representative_form', 'mahdmod_representative_form_shortcode' );

function mahdmod_handle_representative_form() {
	if ( ! isset( $_POST['mahdmod_action'] ) || $_POST['mahdmod_action'] !== 'rep_request' ) { return; }
	if ( ! isset( $_POST['mahdmod_rep_form_nonce'] ) || ! wp_verify_nonce( $_POST['mahdmod_rep_form_nonce'], 'mahdmod_rep_form' ) ) { return; }

	$data = array(
		'full_name'     => sanitize_text_field( $_POST['full_name'] ?? '' ),
		'phone'         => sanitize_text_field( $_POST['phone'] ?? '' ),
		'province'      => sanitize_text_field( $_POST['province'] ?? '' ),
		'city'          => sanitize_text_field( $_POST['city'] ?? '' ),
		'rep_type'      => sanitize_text_field( $_POST['rep_type'] ?? '' ),
		'agency_name'   => sanitize_text_field( $_POST['agency_name'] ?? '' ),
		'experience'    => sanitize_textarea_field( $_POST['experience'] ?? '' ),
		'license_no'    => sanitize_text_field( $_POST['license_no'] ?? '' ),
		'license_date'  => sanitize_text_field( $_POST['license_date'] ?? '' ),
		'license_place' => sanitize_text_field( $_POST['license_place'] ?? '' ),
	);

	if ( ! $data['full_name'] || ! $data['phone'] ) { return; }

	$post_id = wp_insert_post( array(
		'post_type'   => 'mahdmod_rep_req',
		'post_title'  => $data['full_name'],
		'post_status' => 'publish',
	) );

	if ( $post_id && ! is_wp_error( $post_id ) ) {
		foreach ( $data as $key => $val ) {
			if ( $key === 'full_name' ) { continue; }
			update_post_meta( $post_id, '_' . $key, $val );
		}
		$admin_email = get_option( 'admin_email' );
		wp_mail( $admin_email, 'درخواست نمایندگی جدید', "نام: {$data['full_name']}\nتماس: {$data['phone']}\nنوع: {$data['rep_type']}\nشهر: {$data['city']}" );

		wp_safe_redirect( add_query_arg( 'mahdmod_rep_sent', '1', wp_get_referer() ) );
		exit;
	}
}
add_action( 'init', 'mahdmod_handle_representative_form' );
