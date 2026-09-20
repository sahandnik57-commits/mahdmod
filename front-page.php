<?php get_header(); ?>
<main>
<section class="hero"><div class="wrap hero-grid">
 <div class="hero-copy">
  <div class="eyebrow">شرکت بین‌المللی مهد مد</div>
  <?php $hero_title = get_theme_mod('mm_hero_title','آموزش، تخصص و آینده‌ای حرفه‌ای در دنیای مد و لباس'); ?>
  <?php $hero_text  = get_theme_mod('mm_hero_text','۱۳ رشته تخصصی، مربیان حرفه‌ای و شبکه نمایندگی مهد مد'); ?>
  <h1><?php echo esc_html($hero_title); ?></h1>
  <p><?php echo esc_html($hero_text); ?></p>
  <div class="hero-actions">
   <a class="btn" href="#trainers">مشاهده مربیان</a>
   <?php echo do_shortcode('[mahdmod_inquiry_button]'); ?>
  </div>
 </div>
 <div><?php echo do_shortcode('[mahdmod_slider]'); ?></div>
</div></section>

<section class="section" id="news"><div class="wrap">
 <div class="section-head"><h2>آخرین اخبار</h2><p>آخرین مطالب منتشرشده توسط مدیریت سایت</p></div>
 <?php echo do_shortcode('[mahdmod_news count="6"]'); ?>
</div></section>

<section class="section alt" id="trainers"><div class="wrap">
 <div class="section-head"><h2>مربیان ۱۳ رشته تخصصی</h2><p>با انتخاب رشته، مربیان ثبت‌شده توسط مدیریت را مشاهده کنید.</p></div>
 <?php echo do_shortcode('[mahdmod_specialties]'); ?>
</div></section>

<section class="section" id="mentor-request"><div class="wrap">
 <div class="section-head"><h2>درخواست مربیگری</h2><p>اگر تمایل به همکاری دارید، فرم زیر را تکمیل کنید.</p></div>
 <?php echo do_shortcode('[mahdmod_mentor_form]'); ?>
</div></section>

<section class="section alt" id="representatives"><div class="wrap">
 <div class="section-head"><h2>نمایندگی‌های مهد مد</h2><p>نمایندگی‌های معین و خاص</p></div>
 <?php echo do_shortcode('[mahdmod_representatives]'); ?>
</div></section>

<section class="section" id="representative-request"><div class="wrap">
 <div class="section-head"><h2>درخواست نمایندگی</h2><p>برای شروع فرآیند همکاری، فرم درخواست را ارسال کنید.</p></div>
 <?php echo do_shortcode('[mahdmod_representative_form]'); ?>
</div></section>

<section class="section alt" id="contact"><div class="wrap">
 <div class="section-head"><h2>موقعیت مکانی شرکت</h2>
  <p><?php echo esc_html(get_theme_mod('mm_address','')); ?></p>
 </div>
 <?php echo do_shortcode('[mahdmod_map]'); ?>
</div></section>
</main>
<?php get_footer(); ?>
