# Basic Cart

To get up and running type the following into a terminal when you have docker running
```
docker compose up -d

docker exec -it cart_php_1 bash

composer install
```



Create a new basket and add a new product to the basket.
```
$basket = new Basket();
$item = new Item($productCode, $productName, $price, $quantity);
$basket->addItem($item);
```

Offers will follow the offer interface which will allow multiple to be attached.
Many can be attached to a product at a time; these will be iterated over to modify the discount amount.
```
new class implements OfferInterface {
    public function calculatePrice(float $price): float
    {
        //calculate 25% off price
        return (float)number_format($price * 0.75, 2, '.', '');
    }
}
```

To see the unit tests run the following:
`php vendor/bin/phpunit`