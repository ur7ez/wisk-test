function toggleDetails(e) {
    if (e.target.className !== 'details-toggler') return;
    e.stopPropagation();
    e.preventDefault();
    let elem = document.querySelector(`#public-phonebook-${e.target.dataset.id}`);
    e.target.innerText = elem.classList.contains('d-none') ? 'hide details' : 'view details';
    elem.classList.toggle('d-none');
}

document.querySelector('.public-phonebook').addEventListener('click', toggleDetails);