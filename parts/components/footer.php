<?php
$footer_institutional_links = cmb2_get_option('cmb_theme_options', 'footer_institutional') ?: [];
$footer_links = cmb2_get_option('cmb_theme_options', 'footer_links') ?: [];

$footer_contacts = cmb2_get_option('cmb_theme_options', 'footer_contact') ?: [[
    'footer_phone' => '', 
    'footer_whatsapp' => '',
    'footer_email' => '',
]];

if ($footer_contacts) $footer_contacts = $footer_contacts[0];

?>

<!-- footer -->
<footer class="bg-primary-300 flex flex-col gap-4 items-center py-6">
    <!-- footer/info -->
    <div
        class="w-full max-w-5xl h-2/3 p-4 flex flex-col md:flex-row justify-between items-center  text-center md:text-start gap-6 md:gap-0">
        <div class="flex justify-center items-center">
            <a href="/">
                <img class="w-[220px]" src="<?php g_asset('/emana.png') ?>" alt="Logo Emana">
            </a>
        </div>

        <div id="institucional">
            <h4 class="font-bold text-sm mb-2">Institucional</h4>
            <div class="text-sm flex flex-col gap-1">
                <?php if (!empty($footer_institutional_links)): ?>
                    <?php foreach ($footer_institutional_links as $link): ?>
                        <a href="<?php echo $link['footer_institutional_url']; ?>">
                            <?php echo $link['footer_institutional_text']; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="links-uteis">
            <h4 class="font-bold text-sm mb-2">Links Úteis</h4>
            <div class="text-sm flex flex-col gap-1">
                <?php if (!empty($footer_links)): ?>
                    <?php foreach ($footer_links as $link): ?>
                        <a href="<?php echo $link['footer_links_url']; ?>">
                            <?php echo $link['footer_links_text']; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div id="contato">
            <h4 class="font-bold text-sm mb-2">Contato</h4>
            <div class="text-sm flex flex-col gap-1">
                <a href="mailto:<?php echo $footer_contacts['footer_email']; ?>">
                    <?php echo $footer_contacts['footer_email']; ?>
                </a>

                <a href="https://wa.me/55<?php echo preg_replace('/\D/', '', $footer_contacts['footer_phone']); ?>"
                    class="flex items-center gap-2">
                    <!-- whatsapp-svg -->
                    <svg width="12px" height="12px" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 5V1H7V5L4.5 7.5L8.5 11.5L11 9H15V15H11C5.47715 15 1 10.5228 1 5Z" fill="#000000" />
                    </svg>
                    <!-- whatsapp-svg -->
                    <span>
                        <?php echo $footer_contacts['footer_phone']; ?>
                    </span>
                </a>

                <a href="https://wa.me/55<?php echo preg_replace('/\D/', '', $footer_contacts['footer_phone']); ?>"
                    class="flex items-center gap-2">
                    <!-- whatsapp-svg -->
                    <svg width="12px" height="12px" viewBox="0 0 48 48" version="1.1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Color-" transform="translate(-700.000000, -360.000000)" fill="#67C15E">
                                <path
                                    d="M723.993033,360 C710.762252,360 700,370.765287 700,383.999801 C700,389.248451 701.692661,394.116025 704.570026,398.066947 L701.579605,406.983798 L710.804449,404.035539 C714.598605,406.546975 719.126434,408 724.006967,408 C737.237748,408 748,397.234315 748,384.000199 C748,370.765685 737.237748,360.000398 724.006967,360.000398 L723.993033,360.000398 L723.993033,360 Z M717.29285,372.190836 C716.827488,371.07628 716.474784,371.034071 715.769774,371.005401 C715.529728,370.991464 715.262214,370.977527 714.96564,370.977527 C714.04845,370.977527 713.089462,371.245514 712.511043,371.838033 C711.806033,372.557577 710.056843,374.23638 710.056843,377.679202 C710.056843,381.122023 712.567571,384.451756 712.905944,384.917648 C713.258648,385.382743 717.800808,392.55031 724.853297,395.471492 C730.368379,397.757149 732.00491,397.545307 733.260074,397.27732 C735.093658,396.882308 737.393002,395.527239 737.971421,393.891043 C738.54984,392.25405 738.54984,390.857171 738.380255,390.560912 C738.211068,390.264652 737.745308,390.095816 737.040298,389.742615 C736.335288,389.389811 732.90737,387.696673 732.25849,387.470894 C731.623543,387.231179 731.017259,387.315995 730.537963,387.99333 C729.860819,388.938653 729.198006,389.89831 728.661785,390.476494 C728.238619,390.928051 727.547144,390.984595 726.969123,390.744481 C726.193254,390.420348 724.021298,389.657798 721.340985,387.273388 C719.267356,385.42535 717.856938,383.125756 717.448104,382.434484 C717.038871,381.729275 717.405907,381.319529 717.729948,380.938852 C718.082653,380.501232 718.421026,380.191036 718.77373,379.781688 C719.126434,379.372738 719.323884,379.160897 719.549599,378.681068 C719.789645,378.215575 719.62006,377.735746 719.450874,377.382942 C719.281687,377.030139 717.871269,373.587317 717.29285,372.190836 Z"
                                    id="Whatsapp">
                                </path>
                            </g>
                        </g>
                    </svg>
                    <!-- whatsapp-svg -->
                    <span>
                        <?php echo $footer_contacts['footer_whatsapp']; ?>
                    </span>
                </a>



                <span class="text-base font-bold mt-4">Siga-nos nas redes</span>
                <div class="w-full flex gap-4 items-center">
                    <a href="<?php echo $footer_contacts['footer_youtube']; ?>">
                        <!-- youtube-svg -->
                        <svg width="27" height="20" viewBox="0 0 27 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M26.3757 6.24467C26.3757 2.99786 23.7894 0.375 20.5879 0.375H5.78784C2.5863 0.375 0 2.99786 0 6.24467V13.2236C0 16.4704 2.5863 19.0932 5.78784 19.0932H20.5879C23.7894 19.0932 26.3757 16.4704 26.3757 13.2236V6.24467ZM17.6711 10.2656L11.0402 13.5933C10.7781 13.732 9.90085 13.5471 9.90085 13.2467V6.40643C9.90085 6.10601 10.7895 5.90959 11.0516 6.0598L17.4091 9.5608C17.6711 9.71101 17.956 10.1039 17.6825 10.2541L17.6711 10.2656Z"
                                fill="#010101" />
                        </svg>
                        <!-- youtube-svg -->
                    </a>

                    <a href="<?php echo $footer_contacts['footer_tiktok']; ?>">
                        <!-- tick-tock-svg -->
                        <svg width="22" height="26" viewBox="0 0 22 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M21.9343 10.7049C21.7248 10.7239 21.5154 10.7335 21.3059 10.7335C19.0115 10.7335 16.8598 9.57195 15.6031 7.64878V18.1405C15.6031 22.4248 12.128 25.8999 7.84374 25.8999C3.55945 25.8999 0.0939331 22.4248 0.0939331 18.1501C0.0939331 13.8753 3.56897 10.3907 7.85326 10.3907C8.01511 10.3907 8.17696 10.4098 8.32929 10.4193V14.2371C8.16744 14.218 8.01511 14.1895 7.85326 14.1895C5.66351 14.1895 3.89267 15.9603 3.89267 18.1501C3.89267 20.3398 5.66351 22.1106 7.85326 22.1106C10.043 22.1106 11.9662 20.3874 11.9662 18.1977L12.0043 0.375H15.6602C16.0029 3.6501 18.6497 6.21116 21.9343 6.45869V10.7049Z"
                                fill="#010101" />
                        </svg>
                        <!-- tick-tock-svg -->
                    </a>

                    <a href="<?php echo $footer_contacts['footer_facebook']; ?>">
                        <!-- facebook-svg -->
                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="1.08289" y="1.375" width="23.5249" height="23.5249" rx="1" stroke="#010101"
                                stroke-width="2" />
                            <path
                                d="M16.7946 25.4522V16.4187H19.6079L20.1413 12.6842H16.7946V10.2558C16.7946 9.23542 17.2645 8.23548 18.7632 8.23548H20.2874V5.05198C20.2874 5.05198 18.9029 4.80029 17.5884 4.80029C14.8323 4.80029 13.0287 6.58931 13.0287 9.82723V12.6774H9.9614V16.4119H13.0287V25.4454H16.8009L16.7946 25.4522Z"
                                fill="#010101" />
                        </svg>
                        <!-- facebook-svg -->
                    </a>

                    <a href="<?php echo $footer_contacts['footer_instagram']; ?>">
                        <!-- instagram-svg -->
                        <svg width="32px" height="32px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M2 6C2 3.79086 3.79086 2 6 2H18C20.2091 2 22 3.79086 22 6V18C22 20.2091 20.2091 22 18 22H6C3.79086 22 2 20.2091 2 18V6ZM6 4C4.89543 4 4 4.89543 4 6V18C4 19.1046 4.89543 20 6 20H18C19.1046 20 20 19.1046 20 18V6C20 4.89543 19.1046 4 18 4H6ZM12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9ZM7 12C7 9.23858 9.23858 7 12 7C14.7614 7 17 9.23858 17 12C17 14.7614 14.7614 17 12 17C9.23858 17 7 14.7614 7 12ZM17.5 8C18.3284 8 19 7.32843 19 6.5C19 5.67157 18.3284 5 17.5 5C16.6716 5 16 5.67157 16 6.5C16 7.32843 16.6716 8 17.5 8Z"
                                fill="#000000" />
                        </svg>
                        <!-- instagram-svg -->
                    </a>

                </div>
            </div>
        </div>
    </div>
    <!-- footer/info -->

    <!-- footer/end -->
    <div class="w-full max-w-5xl flex flex-col md:flex-row gap-4 justify-center items-center">
        <p class="text-sm  font-light text-center">
            A Loja Emana é operada pela iStoque Distribuidora e Logística LTDA CNPJ 37.264.663/0001-23 -Rua Kanebo, 175,
            Distrito Industrial, Jundiaí - SP, 13.213-090 © 2024 - Laticínios Bela Vista S.A. Todos os direitos
            reservados.​
        </p>
        <img class="w-[180px]" src="<?php g_asset('/piracanjuba.png') ?>" alt="Logo do Grupo Piracanjuba">
    </div>
    <!-- footer/end -->
</footer>