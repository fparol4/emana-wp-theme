<?php
$header_links = cmb2_get_option('cmb_theme_options', 'header_group');
?>

<!-- header -->
<header
    class="bg-primary-300 py-2 px-4 md:px-12 w-full h-[124px] flex justify-between md:justify-center items-center gap-16 relative">
    <div>
        <a href="/">
            <img class="w-[180px] md:w-[220px]" src="<?php g_asset('/emana.png') ?>" alt="Logo Emana">
        </a>
    </div>

    <div class="hidden md:flex items-center flex-grow max-w-[40rem] relative">
        <input id="_search_input"
            class="w-full h-9 p-2 border border-gray-700 rounded-full placeholder-gray-700 focus:outline-none bg-transparent"
            type="text" name="name" placeholder='Buscar no Blog...' />

        <button id="_search_btn" class="absolute right-4">
            <svg width='27' height='26' fill='none' xmlns='http://www.w3.org/2000/svg'>
                <g clip-path='url(#clip0_464_423)' fill='#000'>
                    <path
                        d='M10.923 3.259c-4.457 0-8.071 3.485-8.071 7.783s3.614 7.782 8.07 7.782c4.458 0 8.072-3.484 8.072-7.782-.005-4.297-3.616-7.779-8.071-7.783zm0 14.268c-3.715 0-6.726-2.904-6.726-6.485 0-3.582 3.012-6.486 6.726-6.486s6.725 2.904 6.725 6.486c-.004 3.58-3.013 6.481-6.725 6.485z' />
                    <path
                        d='M10.923 5.853c-2.97.003-5.377 2.324-5.381 5.189a.66.66 0 00.672.648.66.66 0 00.672-.648c.003-2.149 1.808-3.89 4.036-3.892a.66.66 0 00.672-.648.66.66 0 00-.672-.648v-.001z' />
                    <path
                        d='M22.812 17.92a1.38 1.38 0 00-1.902 0l-1.09-1.051c3.346-4.747 2.068-11.208-2.854-14.435C12.043-.792 5.342.441 1.996 5.186-1.348 9.933-.07 16.396 4.852 19.621c3.655 2.396 8.458 2.396 12.114 0l1.09 1.05a1.265 1.265 0 000 1.835l2.595 2.501c1.29 1.288 3.42 1.324 4.756.08a3.162 3.162 0 000-4.665l-2.594-2.503zM1.506 11.042c0-5.015 4.216-9.08 9.417-9.08 5.2 0 9.416 4.065 9.416 9.08 0 5.014-4.216 9.08-9.416 9.08-5.198-.006-9.411-4.068-9.417-9.08zm17.476 6.853l.977.942-.952.918-.977-.942c.336-.287.655-.594.952-.918zm5.473 6.196a2.122 2.122 0 01-2.853 0l-2.595-2.502 2.854-2.752 2.594 2.502c.787.76.787 1.992 0 2.752z' />
                </g>
                <defs>
                    <clipPath id='clip0_464_423'>
                        <path fill='#fff' transform='translate(.132 .636)' d='M0 0h26.302v25.362H0z' />
                    </clipPath>
                </defs>
            </svg>
        </button>
    </div>

    <div class="uppercase gap-6 hidden md:flex items-center text-xs">
        <?php foreach ($header_links as $link): ?>
            <a class="items-center flex gap-2" href="<?php echo $link['header_link_url']; ?>">
                <?php echo $link['header_link_text']; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="flex md:hidden justify-center items-center gap-4">
        <button id="_sm_search_btn" class="mt-2" onclick="toggle_sm_search()">
            <svg width='35px' height='35px' fill='none' xmlns='http://www.w3.org/2000/svg'>
                <g clip-path='url(#clip0_464_423)' fill='#000'>
                    <path
                        d='M10.923 3.259c-4.457 0-8.071 3.485-8.071 7.783s3.614 7.782 8.07 7.782c4.458 0 8.072-3.484 8.072-7.782-.005-4.297-3.616-7.779-8.071-7.783zm0 14.268c-3.715 0-6.726-2.904-6.726-6.485 0-3.582 3.012-6.486 6.726-6.486s6.725 2.904 6.725 6.486c-.004 3.58-3.013 6.481-6.725 6.485z' />
                    <path
                        d='M10.923 5.853c-2.97.003-5.377 2.324-5.381 5.189a.66.66 0 00.672.648.66.66 0 00.672-.648c.003-2.149 1.808-3.89 4.036-3.892a.66.66 0 00.672-.648.66.66 0 00-.672-.648v-.001z' />
                    <path
                        d='M22.812 17.92a1.38 1.38 0 00-1.902 0l-1.09-1.051c3.346-4.747 2.068-11.208-2.854-14.435C12.043-.792 5.342.441 1.996 5.186-1.348 9.933-.07 16.396 4.852 19.621c3.655 2.396 8.458 2.396 12.114 0l1.09 1.05a1.265 1.265 0 000 1.835l2.595 2.501c1.29 1.288 3.42 1.324 4.756.08a3.162 3.162 0 000-4.665l-2.594-2.503zM1.506 11.042c0-5.015 4.216-9.08 9.417-9.08 5.2 0 9.416 4.065 9.416 9.08 0 5.014-4.216 9.08-9.416 9.08-5.198-.006-9.411-4.068-9.417-9.08zm17.476 6.853l.977.942-.952.918-.977-.942c.336-.287.655-.594.952-.918zm5.473 6.196a2.122 2.122 0 01-2.853 0l-2.595-2.502 2.854-2.752 2.594 2.502c.787.76.787 1.992 0 2.752z' />
                </g>
                <defs>
                    <clipPath id='clip0_464_423'>
                        <path fill='#fff' transform='translate(.132 .636)' d='M0 0h26.302v25.362H0z' />
                    </clipPath>
                </defs>
            </svg>
        </button>

        <button onclick="_show_sm_sidebar()">
            <svg width="36px" height="36px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 18L20 18" stroke="#000000" stroke-width="2" stroke-linecap="round" />
                <path d="M4 12L20 12" stroke="#000000" stroke-width="2" stroke-linecap="round" />
                <path d="M4 6L20 6" stroke="#000000" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>
    </div>

    <!-- sm_search_box -->
    <div id="sm_search_box"
        class="absolute opacity-0 w-[95%] py-5 top-[124px] z-10 inset-0 m-auto bg-primary-900 justify-center items-center shadow-lg | flex  invisible | ease-transition">
        <input id="_sm_search_input" type="text" placeholder="Digite sua pesquisa..."
            class="w-[98%] h-8 pl-2 outline-none">
    </div>
    <!-- sm_search_box -->

    <!-- sm_side-bar -->
    <div id="_sm_side-bar" class="w-lvw h-lvh absolute top-0 z-10 bg-white">
        <button onclick="_hide_sm_sidebar()" class="absolute cursor-pointer w-[28px] h-[28px] top-8 right-8">
            <svg fill="#000000" height="28px" width="28px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 460.775 460.775" xml:space="preserve">
                <path d="M285.08,230.397L456.218,59.27c6.076-6.077,6.076-15.911,0-21.986L423.511,4.565c-2.913-2.911-6.866-4.55-10.992-4.55
    c-4.127,0-8.08,1.639-10.993,4.55l-171.138,171.14L59.25,4.565c-2.913-2.911-6.866-4.55-10.993-4.55
    c-4.126,0-8.08,1.639-10.992,4.55L4.558,37.284c-6.077,6.075-6.077,15.909,0,21.986l171.138,171.128L4.575,401.505
    c-6.074,6.077-6.074,15.911,0,21.986l32.709,32.719c2.911,2.911,6.865,4.55,10.992,4.55c4.127,0,8.08-1.639,10.994-4.55
    l171.117-171.12l171.118,171.12c2.913,2.911,6.866,4.55,10.993,4.55c4.128,0,8.081-1.639,10.992-4.55l32.709-32.719
    c6.074-6.075,6.074-15.909,0-21.986L285.08,230.397z" />
            </svg>
        </button>

        <section id="side-content" class="flex flex-col min-h-[60%] justify-center items-center gap-8">
            <div id="side-links" class="flex flex-col items-center text-2xl font-medium gap-4">
                <?php foreach ($header_links as $link): ?>
                    <a class="hover:underline" href="<?php echo $link['header_link_url']; ?>">
                        <?php echo $link['header_link_text']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <div id="side-tags">
                <?php get_template_part('parts/components/tags-sm'); ?>
            </div>
        </section>
    </div>
    <!-- sm_side-bar -->
</header>