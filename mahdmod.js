jQuery(document).ready(function ($) {

	/* ---------- اسلایدر خودکار ---------- */
	var slider = document.getElementById('mahdmodSlider');
	if (slider) {
		var slides = slider.querySelectorAll('.mahdmod-slide');
		var dots = slider.querySelectorAll('.mahdmod-dot');
		var current = 0;

		function showSlide(index) {
			slides.forEach(function (s) { s.classList.remove('active'); });
			dots.forEach(function (d) { d.classList.remove('active'); });
			slides[index].classList.add('active');
			if (dots[index]) { dots[index].classList.add('active'); }
			current = index;
		}

		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				showSlide(parseInt(dot.getAttribute('data-index'), 10));
			});
		});

		if (slides.length > 1) {
			setInterval(function () {
				var next = (current + 1) % slides.length;
				showSlide(next);
			}, 4500);
		}
	}

	/* ---------- کلیک روی رشته تخصصی -> واکشی مربیان با AJAX ---------- */
	$('.mahdmod-specialty-item').on('click', function () {
		var btn = $(this);
		var termId = btn.data('term');
		var resultBox = $('#mahdmodMentorsResult');

		$('.mahdmod-specialty-item').removeClass('active');
		btn.addClass('active');
		resultBox.html('<p class="mahdmod-hint">در حال بارگذاری...</p>');

		$.post(MahdmodAjax.ajax_url, {
			action: 'mahdmod_get_mentors',
			nonce: MahdmodAjax.nonce,
			term_id: termId
		}).done(function (res) {
			if (res.success) {
				resultBox.html(res.data);
			} else {
				resultBox.html('<p class="mahdmod-hint">خطا در دریافت اطلاعات.</p>');
			}
		});
	});

});
