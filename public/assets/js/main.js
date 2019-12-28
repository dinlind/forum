const flash = document.getElementById("flash");

if (flash !== null) {
	setTimeout(() => flash.remove(), 5000);
}

/*window.addEventListener("DOMContentLoaded", function() {
	const filters = document.getElementsByClassName('filter');

	for (let i = 0; i < filters.length; i++) {
		filters[i].addEventListener("click", function() {
			document.forms["user_filter"].submit();
		});
	}
});*/
