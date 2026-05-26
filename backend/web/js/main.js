document.addEventListener('click', function (e) {
    const btn = e.target.closest('.modalUpdateBtn');
    if (!btn) return;

    e.preventDefault();

    const url = btn.dataset.url;
    if (!url) return;

    const modalEl = document.getElementById('updateModal');
    const modalBody = modalEl.querySelector('.modal-body');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

    modalBody.innerHTML = '<div class="p-4 text-center">Загрузка…</div>';
    modal.show();

    fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
        .then(r => r.text())
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(() => {
            modalBody.innerHTML = '<div class="p-4 text-danger">Ошибка загрузки</div>';
        });
});