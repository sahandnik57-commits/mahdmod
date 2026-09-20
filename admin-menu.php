<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function mahdmod_admin_menu() {
	add_menu_page(
		'پنل مدیریت مهدمد',
		'پنل مهدمد',
		'manage_options',
		'mahdmod-panel',
		'mahdmod_dashboard_page_html',
		'dashicons-store',
		3
	);

	add_submenu_page( 'mahdmod-panel', 'داشبورد', 'داشبورد', 'manage_options', 'mahdmod-panel', 'mahdmod_dashboard_page_html' );
	add_submenu_page( 'mahdmod-panel', 'تنظیمات عمومی سایت', 'تنظیمات عمومی', 'manage_options', 'mahdmod-settings', 'mahdmod_settings_page_html' );
}
add_action( 'admin_menu', 'mahdmod_admin_menu' );

function mahdmod_dashboard_page_html() {
	$news_count = wp_count_posts( 'mahdmod_news' )->publish;
	$mentors_count = wp_count_posts( 'mahdmod_mentor' )->publish;
	$mentor_req_count = wp_count_posts( 'mahdmod_mentor_req' )->publish + wp_count_posts( 'mahdmod_mentor_req' )->draft;
	$reps_count = wp_count_posts( 'mahdmod_representative' )->publish;
	$rep_req_count = wp_count_posts( 'mahdmod_rep_req' )->publish + wp_count_posts( 'mahdmod_rep_req' )->draft;
	?>
	<div class="wrap">
		<h1>خوش آمدید به پنل مدیریت مهدمد</h1>
		<p>از این پنل می‌توانید اخبار، اسلایدر صفحه اصلی، رشته‌های تخصصی، مربیان، نمایندگان و درخواست‌های دریافتی را مدیریت کنید.</p>
		<div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:20px;">
			<div class="mahdmod-box"><h2><?php echo intval( $news_count ); ?></h2><p>اخبار منتشر شده</p></div>
			<div class="mahdmod-box"><h2><?php echo intval( $mentors_count ); ?></h2><p>مربیان ثبت‌شده</p></div>
			<div class="mahdmod-box"><h2><?php echo intval( $mentor_req_count ); ?></h2><p>درخواست‌های مربیگری</p></div>
			<div class="mahdmod-box"><h2><?php echo intval( $reps_count ); ?></h2><p>نمایندگان ثبت‌شده</p></div>
			<div class="mahdmod-box"><h2><?php echo intval( $rep_req_count ); ?></h2><p>درخواست‌های نمایندگی</p></div>
		</div>
		<style>
			.mahdmod-box{background:#fff;border:1px solid #e2b980;border-radius:8px;padding:20px 26px;min-width:160px;text-align:center;box-shadow:0 2px 6px rgba(0,0,0,.04);}
			.mahdmod-box h2{margin:0;font-size:32px;color:#e07b1f;}
			.mahdmod-box p{margin:6px 0 0;color:#294a70;}
		</style>
		<h2 style="margin-top:30px;">راهنمای سریع</h2>
		<ol>
			<li>از منوی «اخبار» خبرهای صفحه اصلی را اضافه کنید.</li>
			<li>از منوی «تنظیمات عمومی» سه عکس اسلایدر، نقشه، لینک استعلام و شیوه‌نامه‌ها را تنظیم کنید.</li>
			<li>از منوی «رشته‌های تخصصی» ۱۳ رشته از پیش ساخته شده‌اند؛ در صورت نیاز می‌توانید ویرایش کنید.</li>
			<li>از منوی «مربیان» برای هر رشته، مربی مربوطه را ثبت کنید (گرید A/B/C، سابقه، بیوگرافی، عکس).</li>
			<li>درخواست‌های مربیگری و نمایندگی که کاربران از سایت ارسال می‌کنند، در منوهای مربوطه قابل مشاهده و بررسی است.</li>
			<li>نمایندگان (معین و خاص) را از منوی «نمایندگان» ثبت کنید.</li>
			<li>شورت‌کدهای موجود در صفحه تنظیمات را در صفحات وردپرس (Elementor / Gutenberg) قرار دهید.</li>
		</ol>
	</div>
	<?php
}
