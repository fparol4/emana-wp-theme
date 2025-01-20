<?php
// $products = [
//     [
//         'name' => "CURCUMA",
//         'image' => 'http://localhost:8080/wp-content/uploads/2025/01/curcuma.png',
//         'url' => "/produto",
//         'price' => 74.99,
//         'price_with_discount' => 69.20,
//         'installments' => 2,
//         'with_installments' => 'Ou 2x de R$ 34.60 sem juros'
//     ]
// ];

$products = cmb2_get_option('cmb_theme_options', 'products_group');
$products_mapped = []; 
foreach ($products as $product) {
    $installment_price = round((float) $product['product_price'] / (float) $product['product_installments'], 2); 
    $with_installments = 'Ou ' . $product['product_installments'] . 'x de R$ ' . $installment_price . ' sem juros'; 
    
    $product = array(
        'name' => $product['product_name'],
        'image' => $product['product_image'],
        'url' => $product['product_url'],
        'price' => $product['product_price'],
        'price_with_discount' => $product['product_price_with_discount'],
        'installments' => $product['installments'],
        'with_installments' => $with_installments, 
    ); 

    $products_mapped[] = $product; 
}
// var_dump($products); 
?>

<div id="products-slider" class="swiper products-slider w-full flex p-4 gap-4 m-0">
    <div class="swiper-wrapper md:p-6">
        <?php foreach ($products_mapped as $product): ?>
            <div class="swiper-slide product">
                <div class="w-full">
                    <div class="h-64">
                        <a href="<?php echo $product['url'] ?>">
                            <img class="w-full h-full object-cover" src="<?php echo $product['image']; ?>">
                        </a>
                    </div>
                    <div class="h-40 flex flex-col justify-between items-center gap-3 mt-4 swiper-no-swiping">
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