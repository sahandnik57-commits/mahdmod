<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * غیرفعال‌سازی کامل ثبت‌نام عمومی کاربران در سایت.
 * فقط ادمین از پنل مدیریت وردپرس می‌تواند کاربر/مربی/نماینده جدید تعریف کند.
 * کاربران عادی صرفاً از طریق فرم‌های «درخواست مربیگری» و «درخواست نمایندگی» درخواست ارسال می‌کنند
 * و پس از تأیید ادمین، توسط خود ادمین در سایت ثبت می‌شوند.
 */

// غیرفعال کردن ثبت‌نام عمومی از تنظیمات وردپرس
add_filter( 'option_users_can_register', '__return_false' );

// جلوگیری از دسترسی به صفحه wp-login.php?action=register
function mahdmod_block_register_page() {
	global $pagenow;
	if ( $pagenow === 'wp-login.php' && isset( $_GET['action'] ) && $_GET['action'] === 'register' ) {
		wp_safe_redirect( home_url() );
		exit;
	}
}
add_action( 'init', 'mahdmod_block_register_page' );

// مسدود کردن ثبت‌نام از طریق REST API برای کاربران غیرمجاز
function mahdmod_disable_user_registration_rest( $result, $server, $request ) {
	if ( $request->get_route() === '/wp/v2/users' && $request->get_method() === 'POST' && ! current_user_can( 'manage_options' ) ) {
		return new WP_Error( 'rest_forbidden', 'ثبت‌نام کاربر جدید غیرفعال است.', array( 'status' => 403 ) );
	}
	return $result;
}
add_filter( 'rest_pre_dispatch', 'mahdmod_disable_user_registration_rest', 10, 3 );
