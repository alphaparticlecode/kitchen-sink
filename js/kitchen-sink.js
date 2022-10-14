var expand_links = document.querySelectorAll('.view-all-instances').forEach((item) => {
	item.addEventListener('click', (event) => {
		var other_items_list = item.parentElement.nextSibling;

		if( other_items_list.style.display == 'none' ) {
			other_items_list.style.display = 'block';
			item.textContent = '(collapse)';
		} else {
			other_items_list.style.display = 'none';
			item.textContent = '(expand)';
		}
	});
});