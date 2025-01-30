<!-- contact -->
<section id="contact-section" class="bg-primary-600 w-full flex flex-col relative">
    <?php
    if ($args['_with_load'])
        get_template_part('parts/components/btn_load-more');
    ?>

    <div class="w-full h-full py-12 relative overflow-hidden">
        <div class="invisible md:visible absolute right-0 -bottom-[24px] flex justify-end">
            <img class="w-[90%]" src="<?php g_asset('/emana-contact-vector.png') ?>" alt="emana vector">
        </div>

        <!-- contact/email -->
        <div class="flex-grow flex flex-col md:flex-row justify-center items-center gap-8 md:gap-24 transition-all sticky">
            <div class="flex flex-col justify-center text-center md:text-start">
                <h3 class="uppercase font-bold text-3xl text-slate-800">
                    <span class="font-extrabold">Emane</span> com a gente
                </h3>
                <p class="text-xl font-extralight max-w-[24rem]">Cadastre-se e receba nossas novidades e promoções em
                    primeira
                    mão!</p>
            </div>

            <form id="_contact_form" method="post" class="w-4/5 md:w-[32rem] flex flex-col gap-4">
                <input id="_contact_email_inpt" type="text" name="email" placeholder='Digite seu e-mail aqui...' />

                <div id="_contact_hidden_wrapper" class="text">
                    <div id="_contact_hidden_form" class="flex flex-col gap-4">
                        <input type="text" name="first_name" placeholder='Seu primeiro nome...' />
                        <input type="text" name="last_name" placeholder='Seu último nome...' />
                        <input id="_contact_birth" type="text" name="birth" placeholder='Sua data de nascimento...' />
                        <input id="_contact_phone" type="text" name="phone" placeholder='Seu celular...' />
                        <div class="w-full flex gap-2">
                            <input required type="checkbox" name="age_confirm" value="false" />
                            <label for="age_confirm">* Ao enviar esse formulário, você confirma ter 18 anos ou
                                mais.</label>
                        </div>
                        <div class="w-full flex gap-2">
                            <input required type="checkbox" name="data_collect" value="false" />
                            <label for="data_collect">* Estou de acordo com a coleta e uso dos dados fornecidos para as
                                finalidades aqui descritas.</label>
                        </div>
                        <span id="_terms_alert" class="text-red-800 text-xs hidden">
                            Você deve aceitar os nossos termos e condições para se cadastrar.
                        </span>
                        <button id="_contact_form_btn" type="button" onclick="_handle_form_submit()"
                            class="w-full p-2 bg-primary-900 disabled:bg-gray-400">Enviar</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- contact/email -->
    </div>

</section>

<?php get_template_part('parts/components/nav'); ?>