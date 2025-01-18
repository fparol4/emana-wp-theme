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