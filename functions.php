<?php
if(!defined('ABSPATH')) exit;
add_action('after_setup_theme',function(){add_theme_support('title-tag');add_theme_support('post-thumbnails');register_nav_menus(array('primary'=>'منوی اصلی'));});
add_action('wp_enqueue_scripts',function(){wp_enqueue_style('mahdmod-font','https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap',array(),null);wp_enqueue_style('mahdmod-style',get_stylesheet_uri(),array(),'1.1.0'); wp_enqueue_script('jquery'); wp_add_inline_script('jquery', "jQuery(function(\$){\$('.menu-toggle').on('click',function(){\$('header nav').toggleClass('open');});});");});
add_action('customize_register',function($c){
 $c->add_section('mm_home',array('title'=>'مهد مد — تنظیمات صفحه اصلی'));
 foreach(array('hero_title'=>'عنوان اصلی','hero_text'=>'متن معرفی','phone'=>'شماره تماس','address'=>'آدرس شرکت') as $key=>$label){
  $c->add_setting('mm_'.$key,array('default'=>'','sanitize_callback'=>'sanitize_text_field'));
  $c->add_control('mm_'.$key,array('label'=>$label,'section'=>'mm_home','type'=>'text'));
 }
});
add_filter('registration_errors',function($errors){$errors->add('disabled','ثبت‌نام عمومی کاربران غیرفعال است.');return $errors;});
add_filter('option_users_can_register',function(){return 0;});
function mm_nav(){if(has_nav_menu('primary')) wp_nav_menu(array('theme_location'=>'primary','container'=>'nav'));else echo '<nav><ul><li><a href="'.esc_url(home_url('/')).'">خانه</a></li><li><a href="#news">اخبار</a></li><li><a href="#trainers">مربیان</a></li><li><a href="#representatives">نمایندگی‌ها</a></li><li><a href="#contact">تماس با ما</a></li></ul></nav>';}
function mm_section($title,$content,$id=''){echo '<section class="section '.($id==='news'?'alt':'').'"'.($id?' id="'.esc_attr($id).'"':'').'><div class="wrap"><div class="section-head"><h2>'.esc_html($title).'</h2></div>'.$content.'</div></section>';}
