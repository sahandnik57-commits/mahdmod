<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ---------------- اسلایدر صفحه اصلی (۳ عکس) ---------------- */
function mahdmod_slider_shortcode() {
	$slides = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$url = get_option( 'mahdmod_slide' . $i, '' );
		if ( $url ) { $slides[] = $url; }
	}
	if ( empty( $slides ) ) {
		return '<div class="mahdmod-slider-empty">تصاویر اسلایدر هنوز از پنل مدیریت تنظیم نشده است.</div>';
	}
	ob_start();
	?>
	<div class="mahdmod-slider" id="mahdmodSlider">
		<?php foreach ( $slides as $index => $url ) : ?>
			<div class="mahdmod-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image:url('<?php echo esc_url( $url ); ?>');"></div>
		<?php endforeach; ?>
		<div class="mahdmod-slider-dots">
			<?php foreach ( $slides as $index => $url ) : ?>
				<span class="mahdmod-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"></span>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'mahdmod_slider', 'mahdmod_slider_shortcode' );

/* ---------------- اخبار ---------------- */
function mahdmod_news_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'count' => 6 ), $atts );
	$q = new WP_Query( array(
		'post_type'      => 'mahdmod_news',
		'posts_per_page' => intval( $atts['count'] ),
		'post_status'    => 'publish',
	) );
	if ( ! $q->have_posts() ) {
		return '<div class="mahdmod-empty">خبری برای نمایش وجود ندارد.</div>';
	}
	ob_start();
	echo '<div class="mahdmod-news-grid">';
	while ( $q->have_posts() ) { $q->the_post();
		?>
		<div class="mahdmod-news-card">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="mahdmod-news-img"><?php the_post_thumbnail( 'medium' ); ?></div>
			<?php endif; ?>
			<div class="mahdmod-news-body">
				<h3><?php the_title(); ?></h3>
				<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
				<a class="mahdmod-btn mahdmod-btn-outline" href="<?php the_permalink(); ?>">ادامه مطلب</a>
			</div>
		</div>
		<?php
	}
	echo '</div>';
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'mahdmod_news', 'mahdmod_news_shortcode' );

/* ---------------- ۱۳ رشته تخصصی + مربیان هر رشته (AJAX) ---------------- */
function mahdmod_specialties_shortcode() {
	$terms = get_terms( array( 'taxonomy' => 'mahdmod_specialty', 'hide_empty' => false ) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '<div class="mahdmod-empty">رشته تخصصی ثبت نشده است.</div>';
	}
	ob_start();
	?>
	<div class="mahdmod-specialties">
		<div class="mahdmod-specialties-col">
			<?php foreach ( $terms as $term ) : ?>
				<button type="button" class="mahdmod-specialty-item" data-term="<?php echo esc_attr( $term->term_id ); ?>">
					<?php echo esc_html( $term->name ); ?>
					<span class="mahdmod-arrow">›</span>
				</button>
			<?php endforeach; ?>
		</div>
		<div class="mahdmod-mentors-result" id="mahdmodMentorsResult">
			<p class="mahdmod-hint">برای مشاهده مربیان هر رشته، روی نام رشته کلیک کنید.</p>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'mahdmod_specialties', 'mahdmod_specialties_shortcode' );

function mahdmod_ajax_get_mentors() {
	check_ajax_referer( 'mahdmod_nonce', 'nonce' );
	$term_id = isset( $_POST['term_id'] ) ? intval( $_POST['term_id'] ) : 0;
	$term = get_term( $term_id, 'mahdmod_specialty' );
	if ( ! $term || is_wp_error( $term ) ) { wp_send_json_error(); }

	$q = new WP_Query( array(
		'post_type'      => 'mahdmod_mentor',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'tax_query'      => array( array(
			'taxonomy' => 'mahdmod_specialty',
			'field'    => 'term_id',
			'terms'    => $term_id,
		) ),
	) );

	ob_start();
	echo '<h3 class="mahdmod-result-title">مربیان رشته «' . esc_html( $term->name ) . '»</h3>';
	if ( $q->have_posts() ) {
		echo '<div class="mahdmod-mentor-grid">';
		while ( $q->have_posts() ) { $q->the_post();
			$grade = get_post_meta( get_the_ID(), '_mahdmod_grade', true );
			?>
			<div class="mahdmod-mentor-card">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="mahdmod-mentor-img"><?php the_post_thumbnail( 'medium' ); ?></div>
				<?php endif; ?>
				<h4><?php the_title(); ?></h4>
				<?php if ( $grade ) : ?><span class="mahdmod-grade-badge grade-<?php echo esc_attr( $grade ); ?>">گرید <?php echo esc_html( $grade ); ?></span><?php endif; ?>
				<p><?php echo esc_html( wp_trim_words( get_the_content(), 20 ) ); ?></p>
			</div>
			<?php
		}
		echo '</div>';
	} else {
		echo '<p class="mahdmod-hint">در حال حاضر مربی‌ای برای این رشته ثبت نشده است.</p>';
	}
	wp_reset_postdata();
	wp_send_json_success( ob_get_clean() );
}
add_action( 'wp_ajax_mahdmod_get_mentors', 'mahdmod_ajax_get_mentors' );
add_action( 'wp_ajax_nopriv_mahdmod_get_mentors', 'mahdmod_ajax_get_mentors' );

/* ---------------- نمایندگان (معین / خاص) ---------------- */
function mahdmod_representatives_shortcode() {
	$types = get_terms( array( 'taxonomy' => 'mahdmod_rep_type', 'hide_empty' => false ) );
	ob_start();
	echo '<div class="mahdmod-representatives">';
	if ( ! is_wp_error( $types ) ) {
		foreach ( $types as $type ) {
			$q = new WP_Query( array(
				'post_type'      => 'mahdmod_representative',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'tax_query'      => array( array(
					'taxonomy' => 'mahdmod_rep_type',
					'field'    => 'term_id',
					'terms'    => $type->term_id,
				) ),
			) );
			echo '<h3 class="mahdmod-result-title">' . esc_html( $type->name ) . '</h3>';
			if ( $q->have_posts() ) {
				echo '<div class="mahdmod-rep-grid">';
				while ( $q->have_posts() ) { $q->the_post();
					$city = get_post_meta( get_the_ID(), '_city', true );
					$province = get_post_meta( get_the_ID(), '_province', true );
					$phone = get_post_meta( get_the_ID(), '_phone', true );
					?>
					<div class="mahdmod-rep-card">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="mahdmod-rep-img"><?php the_post_thumbnail( 'medium' ); ?></div>
						<?php endif; ?>
						<h4><?php the_title(); ?></h4>
						<p><?php echo esc_html( $province ); ?> - <?php echo esc_html( $city ); ?></p>
						<?php if ( $phone ) : ?><p class="mahdmod-rep-phone">تماس: <?php echo esc_html( $phone ); ?></p><?php endif; ?>
					</div>
					<?php
				}
				echo '</div>';
			} else {
				echo '<p class="mahdmod-hint">نماینده‌ای در این بخش ثبت نشده است.</p>';
			}
			wp_reset_postdata();
		}
	}
	echo '</div>';
	return ob_get_clean();
}
add_shortcode( 'mahdmod_representatives', 'mahdmod_representatives_shortcode' );

/* ---------------- دکمه استعلام مدارک ---------------- */
function mahdmod_inquiry_button_shortcode() {
	$url = get_option( 'mahdmod_inquiry_url', 'https://check.mahdmod.ir' );
	return '<a href="' . esc_url( $url ) . '" target="_blank" class="mahdmod-btn mahdmod-btn-inquiry">استعلام مدارک</a>';
}
add_shortcode( 'mahdmod_inquiry_button', 'mahdmod_inquiry_button_shortcode' );

/* ---------------- نقشه مکانی شرکت ---------------- */
function mahdmod_map_shortcode() {
	$embed = get_option( 'mahdmod_map_embed', '' );
	if ( ! $embed ) {
		return '<div class="mahdmod-empty">نقشه هنوز از پنل مدیریت تنظیم نشده است.</div>';
	}
	return '<div class="mahdmod-map-wrap">' . $embed . '</div>';
}
add_shortcode( 'mahdmod_map', 'mahdmod_map_shortcode' );
