const { log } = console

log('Emana Blog - WP Theme [1.0.0]')

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
            var _allowed_click = ['#sm_search_btn', '#sm_search_box'].find(v => evt.target.closest(v))
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
    var _load_limit = 1; 
    var _remaining_posts_box = document.querySelector('#remaining-posts');
    var base_url = `?rest_route=/api/posts&offset=${_load_more_offset}&limit=${_load_limit}`;
    var next_posts = await fetch(base_url)
        .then(response => response.json());

    if (!next_posts.length) { 
        var btn_load_more = document.querySelector('#btn_load_more'); 
        btn_load_more.remove(); 
    }

    var posts_html = next_posts.reduce((p, c) => {
        var [_post_category] = c.category
        var _post_html = `
            <div id="r-post" class="flex flex-col md:flex-row shadow-md">
                <img class="md:w-1/2 object-cover" src="${c.banner}">
                <div
                    class="md:w-1/2  bg-slate-100 flex flex-col p-12 md:px-12 justify-center items-center gap-4 text-center">
                    <h4 class="uppercase text-amber-800 font-bold">${_post_category.name}</h4>
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
    _remaining_posts_box.innerHTML += posts_html
}

window.onload = () => {
    new Swiper('.top-header-swiper', {
        // autoplay: { delay: 1000 },
        spaceBetween: 100
    });

    new Swiper('.banner-carroussel', {
        // autoplay: { delay: 1000 },
        loop: true,
        pagination: {
            el: '.banner_swp-pagination',
            clickable: true,
        }
    })

    new Swiper('.product-swiper', {
        slidesPerView: 1,
        pagination: {
            el: '.product_swp-pag',
            clickable: true
        },
        breakpoints: {
            768: {
                slidesPerView: 3,
                spaceBetween: 30
                // centeredSlides: true,
            }
        }
    })
}