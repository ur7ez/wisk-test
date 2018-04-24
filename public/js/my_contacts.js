function formSaveContact(e) {
    e.stopPropagation();
    e.preventDefault();
    let url = e.target.action;
    let salt = '?transport=xhr';
    let target = document.querySelector('main .content');

    let contactFormData = document.querySelector('#my-contact-form');
    let data = new FormData(contactFormData);

    data.set('published', data.has('published')? '1': '0');

    // if we need to send data with encoding 'application/x-www-form-urlencoded' - may use this instead:
    /*
    let data = new URLSearchParams();  // let data = new URLSearchParams(contactFormData);
    for (const pair of new FormData(contactFormData)) {
        data.append(pair[0], pair[1]);
    }
    */

    fetch(url + salt, {
        body: data,
        //headers: {
        // "Content-Type": "application/x-www-form-urlencoded",
        //  "Content-Type": "multipart/form-data",
        //},
        method: 'POST',
        credentials: 'same-origin'  // cross-origin requests: 'include'
    })
        .then(response => response.json())
        .then(data => {
            let c = document.createElement('div');
            c.className = 'my-custom-alert';
            c.innerHTML = data;
            target.parentNode.insertBefore(c, target);
        });
}

function addContactData(e) {
    if (!e.target.classList.contains('add-button')) return;
    e.stopPropagation();
    let type = e.target.dataset.type;  // type attr of input
    let name1 = e.target.dataset.name1;  // name attr of input
    let name2 = e.target.dataset.name2;  // name attr of checkbox
    let title1, title2;
    if (type === 'email') {
        title1 = document.querySelector('.contact-emails input').title;
        title2 = document.querySelector('.contact-emails input[type=checkbox]').title;
    } else {
        title1 = document.querySelector('.contact-phones input').title;
        title2 = document.querySelector('.contact-phones input[type=checkbox]').title;
    }

    let template = document.querySelector('template');
    let c = document.createElement('div');
    c.innerHTML = template.innerHTML;
    c.setAttribute('class', template.dataset.class);
    let new_node = document.querySelector(`.${e.target.dataset.class.split(' ').join('.')}`)
        .appendChild(c.cloneNode(true));

    let tmp = new_node.querySelector('input');  // 1st input in a div rendered from our template
    tmp.setAttribute('type', type);
    tmp.setAttribute('name', name1);
    tmp.setAttribute('title', title1);

    tmp = new_node.querySelector('input[type=checkbox]');  // 1st input in a div rendered from our template
    tmp.setAttribute('name', name2);
    tmp.setAttribute('title', title2);

}

document.querySelector('.my-contact-section').addEventListener('click', addContactData);
document.querySelector('.my-contact-form').addEventListener('submit', formSaveContact);