<!-- contact -->
<section class="bg-primary-600 w-full flex flex-col py-12">
    <?php
    if ($args['_with_load'])
        get_template_part('parts/components/btn_load-more');
    ?>

    <!-- contact/email -->
    <div class="flex-grow flex flex-col md:flex-row justify-center items-center gap-8 md:gap-24">
        <div class="flex flex-col justify-center text-center md:text-start">
            <h3 class="uppercase font-bold text-3xl text-slate-800">
                <span class="font-extrabold">Emane</span> com a gente
            </h3>
            <p class="text-xl font-extralight max-w-[24rem]">Cadastre-se e receba nossas novidades e promoções em
                primeira
                mão!</p>
        </div>

        <div class="w-4/5 md:w-[32rem]">
            <input class="bg-primary-300 w-full h-12 p-2 pl-6 rounded-full placeholder-gray-700 focus:outline-none "
                type="text" name="name" placeholder='Digite seu e-mail aqui...' />
        </div>
    </div>
    <!-- contact/email -->

</section>