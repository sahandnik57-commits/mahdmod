<?php
/**
 * Plugin Name: پنل مدیریت مهدمد (Mahd Mod Panel)
 * Plugin URI:  https://mahdmod.ir
 * Description: پنل مدیریت اختصاصی سایت مهدمد شامل مدیریت اخبار، اسلایدر صفحه اصلی، ۱۳ رشته تخصصی، پنل مربیان، درخواست مربیگری، پنل نمایندگی، درخواست نمایندگی، دکمه استعلام مدارک و نقشه مکانی شرکت. تمام بخش‌ها به‌طور کامل از پنل مدیریت وردپرس قابل ویرایش هستند.
 * Version: 1.0.0
 * Author: Mahd Mod
 * Text Domain: mahdmod-panel
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'MAHDMOD_VERSION', '1.0.0' );
define( 'MAHDMOD_PATH', plugin_dir_path( __FILE__ ) );
define( 'MAHDMOD_URL', plugin_dir_url( __FILE__ ) );

/**
 * ۱۳ رشته تخصصی ثابت مهدمد
 * (به‌صورت تاکسونومی ثبت می‌شود تا هم برای مربیان و هم برای درخواست‌ها قابل استفاده باشد)
 */
function mahdmod_get_specialties_list() {
	return array(
		'علوم و مهارت خیاطی',
		'روش ها و فنون دوخت روز',
		'طراحی مد و جامه آرایی',
		'آرایه های تزیینی لباس',
		'طراحی و بافت پارچه',
		'طراحی کیف و کفش',
		'انواع سرپوش، کلاه و تاج',
		'لباس فاخر ایران',
		'طراحی لباس اقوام ایران',
		'طراحی و دوخت لباس عروس',
		'طراحی و دوخت لباس داماد',
		'طراحی و دوخت لباس جامع',
		'طراحی و دوخت لباس کودک و نوجوان',
	);
}

// فایل‌های اصلی پلاگین
require_once MAHDMOD_PATH . 'includes/cpt-news.php';
require_once MAHDMOD_PATH . 'includes/cpt-specialty-taxonomy.php';
require_once MAHDMOD_PATH . 'includes/cpt-mentors.php';
require_once MAHDMOD_PATH . 'includes/cpt-mentor-requests.php';
require_once MAHDMOD_PATH . 'includes/cpt-representatives.php';
require_once MAHDMOD_PATH . 'includes/cpt-representative-requests.php';
require_once MAHDMOD_PATH . 'includes/settings-slider-map.php';
require_once MAHDMOD_PATH . 'includes/admin-menu.php';
require_once MAHDMOD_PATH . 'includes/shortcodes.php';
require_once MAHDMOD_PATH . 'includes/frontend-forms.php';
require_once MAHDMOD_PATH . 'includes/restrict-registration.php';

/**
 * بارگذاری استایل و اسکریپت در فرانت
 */
function mahdmod_enqueue_assets() {
	wp_enqueue_style( 'mahdmod-style', MAHDMOD_URL . 'assets/css/mahdmod.css', array(), MAHDMOD_VERSION );
	wp_enqueue_script( 'mahdmod-script', MAHDMOD_URL . 'assets/js/mahdmod.js', array( 'jquery' ), MAHDMOD_VERSION, true );
	wp_localize_script( 'mahdmod-script', 'MahdmodAjax', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'mahdmod_nonce' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'mahdmod_enqueue_assets' );

/**
 * فعال‌سازی پلاگین: ساخت رشته‌های تخصصی پیش‌فرض و تنظیم پرمالینک
 */
function mahdmod_activate_plugin() {
	mahdmod_register_specialty_taxonomy();
	mahdmod_register_news_cpt();
	mahdmod_register_mentors_cpt();
	mahdmod_register_mentor_requests_cpt();
	mahdmod_register_representatives_cpt();
	mahdmod_register_representative_requests_cpt();

	// ثبت ۱۳ رشته تخصصی به‌صورت خودکار در تاکسونومی
	foreach ( mahdmod_get_specialties_list() as $term ) {
		if ( ! term_exists( $term, 'mahdmod_specialty' ) ) {
			wp_insert_term( $term, 'mahdmod_specialty' );
		}
	}
	// ثبت انواع نمایندگی (معین / خاص)
	foreach ( array( 'نمایندگی معین', 'نمایندگی خاص' ) as $term ) {
		if ( ! term_exists( $term, 'mahdmod_rep_type' ) ) {
			wp_insert_term( $term, 'mahdmod_rep_type' );
		}
	}
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mahdmod_activate_plugin' );

function mahdmod_deactivate_plugin() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'mahdmod_deactivate_plugin' );
