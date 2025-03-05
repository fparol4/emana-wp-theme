const { log } = console

log('Emana Blog - WP Theme [1.0.0]')

/** @helpers */
function win_redirect(path) {
    window.location.href = path;
}

function show_toast(message, options = {}) {
    Toastify({
        text: message,
        duration: 3000,
        style: { background: "linear-gradient(to right, #00b09b, #96c93d)" },
        ...options
    }).showToast();
}

/** _sm_search-bar */
function toggle_sm_search() {
    var _hidden_classess = ['invisible', 'opacity-0']
    var _visible_classess = ['visible', 'opacity-100',]
    var _sm_search_box = document.querySelector('#sm_search_box')
    var _sm_search_input = _sm_search_box.querySelector('input')
    var _is_open = _sm_search_box.classList.contains('visible')

    function show() {
        _sm_search_box.classList.remove(..._hidden_classess)
        _sm_search_box.classList.add(..._visible_classess)
    }

    function hide() {
        _sm_search_box.classList.remove(..._visible_classess)
        _sm_search_box.classList.add(..._hidden_classess)
        _sm_search_input.value = ''
    }

    if (!_is_open) {
        show()
        var _sm_search_listener = document.addEventListener('click', (evt) => {
            var _allowed_click = ['#_sm_search_btn', '#sm_search_box'].find(v => evt.target.closest(v))
            if (!_allowed_click) {
                hide();
                document.removeEventListener('click', _sm_search_listener)
            }
        })
    }
}

function _show_sm_sidebar() {
    var _sm_side_bar = document.querySelector('#_sm_side-bar')
    _sm_side_bar.classList.add('_sm_sidebar-show')
    _toggle_pg_scroll()
}

function _hide_sm_sidebar() {
    var _sm_side_bar = document.querySelector('#_sm_side-bar')
    _sm_side_bar.classList.remove('_sm_sidebar-show')
    _toggle_pg_scroll()
}

/** _toggle_pg_scroll */
function _toggle_pg_scroll() {
    var _lock_class = '_scroll_lock'
    var bdy = document.querySelector('body')
    var _is_locked = !!bdy.classList.contains(_lock_class)
    if (!_is_locked) return bdy.classList.add(_lock_class)
    return bdy.classList.remove(_lock_class)
}

/** @loadmore */

var _load_more_offset = 3;
async function _load_more_posts() {
    var _load_limit = 1; //@TODO 
    var _remaining_posts_box = document.querySelector('#remaining-posts');
    var base_url = `?rest_route=/api/posts&offset=${_load_more_offset}&limit=${_load_limit}`;
    var { posts, total_posts } = await fetch(base_url)
        .then(response => response.json());

    var posts_html = posts.reduce((p, c) => {
        var _post_html = `
            <div id="r-post" class="flex flex-col md:flex-row shadow-md">
                <img class="md:w-1/2 object-cover" src="${c.banner}">
                <div
                    class="md:w-1/2  bg-slate-100 flex flex-col p-12 md:px-12 justify-center items-center gap-4 text-center">
                    <h4 class="uppercase text-amber-800 font-bold">${c.category}</h4>
                    <p class="text-xl font-light">${c.title}</p>
                    <a href="/?p=${c.id}">
                        <button class="uppercase bg-primary-900 rounded-lg px-3 py-2 text-xl">Leia mais</button>
                    </a>
                </div>
            </div>
        `
        return p + _post_html
    }, '')

    _load_more_offset += _load_limit

    if (_load_more_offset === total_posts) {
        var btn_load_more = document.querySelector('#btn_load_more');
        btn_load_more.remove();
    }

    _remaining_posts_box.innerHTML += posts_html
}

/** @searchkeyevents */

function set_search_keypress() {
    var _search_input = document.querySelector('#_search_input');
    var _sm_search_input = document.querySelector('#_sm_search_input');

    [_search_input, _sm_search_input].forEach(element => {
        element.addEventListener('keypress', event => {
            var _search = element.value
            var _is_enter = event.key === 'Enter'
            if (_search && _is_enter) win_redirect(`/?s=${encodeURIComponent(_search)}`)
        })
    })

    var _search_btn = document.querySelector('#_search_btn');
    var _sm_search_btn = document.querySelector('#_sm_search_btn');

    _search_btn.addEventListener('click', () => {
        var _search = _search_input.value
        if (_search) win_redirect(`/?s=${_search}`)
    })

    _sm_search_btn.addEventListener('click', () => {
        var _search = _sm_search_input.value
        if (_search) win_redirect(`/?s=${_search}`)
    })
}

/** Swiper */
function set_swiper() {
    new Swiper('.top-header-swiper', {
        autoplay: { delay: 3000 },
        spaceBetween: 100
    });

    new Swiper('.banner-carroussel', {
        autoplay: { delay: 5000 },
        loop: true,
        pagination: {
            el: '.banner_swp-pagination',
            clickable: true,
        }
    })

    new Swiper('.products-slider', {
        slidesPerView: 1,
        scrollbar: {
            el: '.swiper-scrollbar',
        },
        breakpoints: {
            768: {
                slidesPerView: 3,
                centered: true,
            }
        }
    })
}

function set_open_contact() {
    var _contact_email_inpt = document.querySelector('#_contact_email_inpt')
    var _contact_hidden_wrapper = document.querySelector('#_contact_hidden_wrapper')

    _contact_email_inpt.addEventListener('keyup', () => {
        if (_contact_email_inpt.value) return _contact_hidden_wrapper.classList.add('show')
        if (!_contact_email_inpt.value) return _contact_hidden_wrapper.classList.remove('show')
    })
}

function _handle_form_submit() {
    var form = document.querySelector('#_contact_form')
    var form_data = new FormData(form)
    
    var _contact_form_btn = form.querySelector('#_contact_form_btn')
    var _required_alert = form.querySelector('#required_alert')
    var _terms_alert_text = form.querySelector('#_terms_alert')
    
    var _required_inputs = [...form.querySelectorAll('input[type="text')]
    var _confirm_inputs = [...form.querySelectorAll('input[type="checkbox"]')]
    
    var _fill_all = _required_inputs.every(i => i.value); 
    if (!_fill_all) _required_alert.classList.remove('hidden') 
    else _required_alert.classList.add('hidden')
    
    var _uncofirmed = _confirm_inputs.find(e => !e.checked)
    if (_uncofirmed) return _terms_alert_text.classList.remove('hidden')
    else _terms_alert_text.classList.add('hidden')

    var _payload = {
        email: form_data.get('email'),
        first_name: form_data.get('first_name'),
        last_name: form_data.get('last_name'),
        birth_day: form_data.get('birth'),
        phone: form_data.get('phone'),
    }

    log('[IMPLEMENT] - Add newsletter call', _payload)
    show_toast('Cadastro realizado com sucesso ;)')
    _contact_form_btn.disabled = true;
}


function setup_home_form() {
    /** @masks */
    var _contact_birth_input = document.querySelector('#_contact_birth')
    var _contact_phone_input = document.querySelector('#_contact_phone')

    VMasker(_contact_birth_input).maskPattern('99/99/9999')
    VMasker(_contact_phone_input).maskPattern('(99) 99999-9999')
}

/** navbar-positioning */
function setup_nav_positioning() {
    function _set_nav_subitems_position(_nav_item) {
        var _nav_subitems = _nav_item.querySelector('._nav_subitems')
        if (!_nav_subitems) return

        var _box_pos = _nav_subitems.getBoundingClientRect();
        if (_box_pos.right > window.innerWidth) {
            _nav_subitems.style.left = 'auto'
            _nav_subitems.style.right = '0'
        } 
    }

    var _nav_subitems = document.querySelectorAll('._nav_item')
    _nav_subitems.forEach(item => item.addEventListener('mouseenter', () => _set_nav_subitems_position(item)))
}

function safe_execute(fn) {
    try {
        fn();
    } catch {
        log(`error executing '${fn.name}'`)
    }
}

window.onload = () => {
    safe_execute(set_swiper);
    safe_execute(set_search_keypress);
    safe_execute(set_open_contact);
    safe_execute(setup_home_form);
    safe_execute(setup_nav_positioning);
}

console.log('Helloworld - END')