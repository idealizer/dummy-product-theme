(function () {
	var button = document.getElementById('dummy-theme-toggle');
	if (!button) {
		return;
	}

	function apply(dark) {
		document.documentElement.classList.toggle('dark', dark);
		button.setAttribute('aria-pressed', dark ? 'true' : 'false');
		try {
			localStorage.setItem('dummy-product-theme', dark ? 'dark' : 'light');
		} catch (e) {}
	}

	apply(document.documentElement.classList.contains('dark'));

	button.addEventListener('click', function () {
		apply(!document.documentElement.classList.contains('dark'));
	});

	var navToggle = document.getElementById('dummy-nav-toggle');
	var nav = document.getElementById('site-nav');
	if (navToggle && nav) {
		navToggle.addEventListener('click', function () {
			var open = nav.classList.toggle('is-open');
			navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}
}());
