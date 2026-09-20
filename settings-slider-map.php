<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * ثبت تنظیمات عمومی سایت (اسلایدر ۳ عکسه، نقشه، لینک استعلام، فایل‌های شیوه‌نامه)
 */

function mahdmod_sanitize_map_embed( $value ) {
    $allowed = array(
        'iframe' => array(
            'src' => true,
            'width' => true,
            'height' => true,
            'style' => true,
            'allow' => true,
            'allowfullscreen' => true,
            'loading' => true,
            'referrerpolicy' => true,
            'frameborder' => true,
            'title' => true,
        ),
    );
    return wp_kses( $value, $allowed );
}

function mahdmod_register_settings() {
	register_setting( 'mahdmod_settings_group', 'mahdmod_slide1' );
	register_setting( 'mahdmod_settings_group', 'mahdmod_slide2' );
	register_setting( 'mahdmod_settings_group', 'mahdmod_slide3' );
	register_setting(
        'mahdmod_settings_group',
        'mahdmod_map_embed',
        array(
            'sanitize_callback' => 'mahdmod_sanitize_map_embed',
            'default' => '',
        )
    );
	register_setting( 'mahdmod_settings_group', 'mahdmod_inquiry_url' );
	register_setting( 'mahdmod_settings_group', 'mahdmod_shivenameh_moein' );
	register_setting( 'mahdmod_settings_group', 'mahdmod_shivenameh_khas' );
}
add_action( 'admin_init', 'mahdmod_register_settings' );

function mahdmod_settings_page_html() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	?>
	<div class="wrap">
		<h1>تنظیمات عمومی سایت مهدمد</h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'mahdmod_settings_group' ); ?>
			<h2>اسلایدر صفحه اصلی (سه عکس)</h2>
			<table class="form-table">
				<?php for ( $i = 1; $i <= 3; $i++ ) :
					$val = get_option( 'mahdmod_slide' . $i, '' ); ?>
					<tr>
						<th>اسلاید <?php echo $i; ?> (slide<?php echo $i; ?>.jpg)</th>
						<td>
							<input type="text" class="regular-text mahdmod-media-url" id="mahdmod_slide<?php echo $i; ?>" name="mahdmod_slide<?php echo $i; ?>" value="<?php echo esc_attr( $val ); ?>" />
							<button type="button" class="button mahdmod-upload-btn" data-target="mahdmod_slide<?php echo $i; ?>">انتخاب تصویر</button>
							<?php if ( $val ) : ?>
								<br><img src="<?php echo esc_url( $val ); ?>" style="max-width:180px;margin-top:8px;border:1px solid #ccc;" />
							<?php endif; ?>
						</td>
					</tr>
				<?php endfor; ?>
			</table>

			<h2>نقشه مکانی شرکت</h2>
			<table class="form-table">
				<tr>
					<th>کد Embed نقشه (گوگل مپ یا بلد)</th>
					<td>
						<textarea name="mahdmod_map_embed" rows="4" class="large-text" placeholder="کد iframe نقشه گوگل یا بلد را اینجا paste کنید"><?php echo esc_textarea( get_option( 'mahdmod_map_embed', '' ) ); ?></textarea>
						<p class="description">مثال: از گوگل مپ روی «اشتراک‌گذاری > جاسازی نقشه» کد iframe را کپی کنید یا از نقشه بلد (balad.ir) کد embed را دریافت کنید.</p>
					</td>
				</tr>
			</table>

			<h2>دکمه استعلام مدارک</h2>
			<table class="form-table">
				<tr>
					<th>آدرس استعلام مدارک</th>
					<td><input type="url" class="regular-text" name="mahdmod_inquiry_url" value="<?php echo esc_attr( get_option( 'mahdmod_inquiry_url', 'https://check.mahdmod.ir' ) ); ?>" /></td>
				</tr>
			</table>

			<h2>فایل‌های شیوه‌نامه نمایندگی</h2>
			<table class="form-table">
				<tr>
					<th>شیوه‌نامه نمایندگی معین</th>
					<td>
						<input type="text" class="regular-text mahdmod-media-url" id="mahdmod_shivenameh_moein" name="mahdmod_shivenameh_moein" value="<?php echo esc_attr( get_option( 'mahdmod_shivenameh_moein', '' ) ); ?>" />
						<button type="button" class="button mahdmod-upload-btn" data-target="mahdmod_shivenameh_moein">انتخاب فایل</button>
					</td>
				</tr>
				<tr>
					<th>شیوه‌نامه نمایندگی خاص</th>
					<td>
						<input type="text" class="regular-text mahdmod-media-url" id="mahdmod_shivenameh_khas" name="mahdmod_shivenameh_khas" value="<?php echo esc_attr( get_option( 'mahdmod_shivenameh_khas', '' ) ); ?>" />
						<button type="button" class="button mahdmod-upload-btn" data-target="mahdmod_shivenameh_khas">انتخاب فایل</button>
					</td>
				</tr>
			</table>
			<?php submit_button( 'ذخیره تنظیمات' ); ?>
		</form>

		<hr>
		<h2>شورت‌کدهای قابل استفاده در صفحات</h2>
		<table class="widefat striped" style="max-width:900px;">
			<tbody>
				<tr><td><code>[mahdmod_slider]</code></td><td>نمایش اسلایدر ۳ عکسه صفحه اصلی</td></tr>
				<tr><td><code>[mahdmod_news count="6"]</code></td><td>نمایش آخرین اخبار</td></tr>
				<tr><td><code>[mahdmod_specialties]</code></td><td>نمایش ستون ۱۳ رشته تخصصی و مربیان هر رشته</td></tr>
				<tr><td><code>[mahdmod_mentor_form]</code></td><td>فرم درخواست مربیگری</td></tr>
				<tr><td><code>[mahdmod_representatives]</code></td><td>نمایش نمایندگان (معین و خاص)</td></tr>
				<tr><td><code>[mahdmod_representative_form]</code></td><td>فرم درخواست نمایندگی</td></tr>
				<tr><td><code>[mahdmod_inquiry_button]</code></td><td>دکمه استعلام مدارک</td></tr>
				<tr><td><code>[mahdmod_map]</code></td><td>نمایش نقشه مکانی شرکت</td></tr>
			</tbody>
		</table>
	</div>
	<?php
}

function mahdmod_settings_enqueue_media( $hook ) {
	if ( strpos( $hook, 'mahdmod' ) === false ) { return; }
	wp_enqueue_media();
	wp_add_inline_script( 'jquery', "
		jQuery(document).ready(function($){
			$('.mahdmod-upload-btn').on('click', function(e){
				e.preventDefault();
				var btn = $(this);
				var target = btn.data('target');
				var frame = wp.media({ title: 'انتخاب فایل', multiple: false });
				frame.on('select', function(){
					var att = frame.state().get('selection').first().toJSON();
					$('#' + target).val(att.url);
				});
				frame.open();
			});
		});
	" );
}
add_action( 'admin_enqueue_scripts', 'mahdmod_settings_enqueue_media' );
