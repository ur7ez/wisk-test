function loadData(e) {
    let id = e.target.id;
    let allowedClass = 'clickable-pagelink';
    let allowedID = ['user-login', 'public-phonebook', 'my-contact'];
    if (allowedID.includes(id)) {
        document.querySelectorAll('.nav .menu .btn').forEach(item => {
            (item.id !== id) ? item.classList.remove('active') : item.classList.add('active');
        });
    } else if (!e.target.classList.contains(allowedClass)) {
        return;
    }

    let old_alert = document.querySelector('.my-custom-alert');
    if (old_alert) old_alert.parentNode.removeChild(old_alert);

    e.preventDefault();
    e.stopPropagation();
    let url = e.target.href;
    let salt = url.includes('?') ? '&transport=xhr' : '?transport=xhr';
    let target = document.querySelector('main .content');

    fetch(url + salt, {
        method: 'GET',
        credentials: 'same-origin'  // cross-origin requests: 'include'
    })
        .then(response => response.json())
        .then(data => {
            target.innerHTML = data;
            let script = document.querySelector('.content script');
            if (script) {
                let scriptEl = document.createElement('script');
                scriptEl.src = script.src;
                document.body.appendChild(scriptEl);
                script.parentElement.removeChild(script);
            }
        });
}

document.body.addEventListener('click', loadData);
// document.querySelector('.menu').addEventListener('click', loadData);