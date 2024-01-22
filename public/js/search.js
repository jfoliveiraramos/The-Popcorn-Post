

document.addEventListener("DOMContentLoaded", function () {
	search_filter();
	search_pagination();
	search_attributes();
});

function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
}

function search_filter() {
	const filter = document.querySelector('#filter');

	if (filter == null) return;

	const typeSelector = filter.querySelector('#type-selector');

	typeSelector.addEventListener('change', function () {
		filter.submit();
	});

	exactMatch = filter.querySelector('#exact-match');

	exactMatch.addEventListener('click', function () {
		filter.submit();
	});

	const sortSelector = filter.querySelector('#sort-selector');

	sortSelector.addEventListener('change', function () {
		filter.submit();
	});
}

function search_pagination() {

	let currentPage = 1;

	const content = document.querySelector('#content');

	if (content == null) return;

	const search_results = content.querySelector('#search-results');

    content.addEventListener('scroll', loadMoreItems);

	function loadMoreItemsAjax() {
		const filter = document.querySelector('#filter');

		if (filter == null) return;

		const query = filter.querySelector('#query');
		const typeSelector = filter.querySelector('#type-selector');
		const exactMatch = filter.querySelector('#exact-match');
		const time = filter.querySelector('#time-selector');
		const minScore = filter.querySelector('#min-score');
		const maxScore = filter.querySelector('#max-score');
		const topic = filter.querySelector('#topic-selector');
		const sortSelector = filter.querySelector('#sort-selector');

		const url = new URL('/api/search', window.location.origin);
		const params = new URLSearchParams();
		if (query != null)
			params.set('query', query.value);
		params.set('type', typeSelector.value);
		params.set('exactMatch', exactMatch.checked);
		if (time != null)
			params.set('time', time.value);
		if (minScore != null)
			params.set('minScore', minScore.value);
		if (maxScore != null)
			params.set('maxScore', maxScore.value);
		if (topic != null)
			params.set('topic', topic.value);
		params.set('sort', sortSelector.value);
		params.set('page', currentPage);
		url.search = params.toString();

		sendAjaxRequest('post', url.toString(), null, function () {
			let response = JSON.parse(this.responseText);
			search_results.innerHTML += response.html;
			if (response.isLastPage)
				content.removeEventListener('scroll', loadMoreItems);
		});
	}
	function loadMoreItems() {
		if (content.scrollTop + content.clientHeight >= content.scrollHeight - window.innerHeight) {
			currentPage++;
			loadMoreItemsAjax();
		}
	}
}

function search_attributes() {

	const filter_button = document.getElementById('filter-button');

	if (filter_button == null) return;

	const filter_dialog = document.getElementById('filter-dialog');

	const cancel_button = document.getElementById('filter-cancel-button');

	filter_button.addEventListener('click', function () {
		filter_dialog.showModal();
	});

	cancel_button.addEventListener('click', function () {
		filter_dialog.close();
	});
}