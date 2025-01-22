<?php
$products_mapped = map_products2slider($args); 
?>

<?php if (!empty($products_mapped)): ?>
    <div class="flex md:hidden w-full">
        <div id="products-slider" class="swiper products-slider w-full flex gap-4 m-0 py-4">
            <div class="swiper-wrapper md:p-6">
                <?php foreach ($products_mapped as $product): ?>
                    <div class="swiper-slide product">
                        <div class="w-full">
                            <div class="h-64">
                                <a href="<?php echo $product['url'] ?>">
                                    <img alt="Imagem do Produto" class="w-full h-full object-cover" src="<?php echo $product['image']; ?>">
                                </a>
                            </div>
                            <div class="min-h-[200px] flex flex-col justify-between items-center gap-3 mt-4 swiper-no-swiping">
                                <span id="product-title" class="text-xl"><?php echo $product['name']; ?></span>
                                <div class="flex flex-col justify-center items-center">
                                    <?php if (empty($product['price_with_discount'])): ?>
                                        <span>R$ <?php echo $product['price']; ?></span>
                                    <?php else: ?>
                                        <span class="text-sm">De R$ <?php echo $product['price']; ?></span>
                                        <span>Para R$ <?php echo $product['price_with_discount']; ?></span>
                                    <?php endif; ?>
                                    <span class="text-xs"><?php echo $product['with_installments']; ?></span>
                                </div>
                                <a href="<?php echo $product['url'] ?>">
                                    <button class="bg-primary-900 uppercase text-xl p-2 rounded-xl">Comprar</button>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-scrollbar bottom-[-2px]"></div>
        </div>
    </div>
<?php endif; ?>