<?php

namespace Mic2100\CartTests;

use Mic2100\Cart\Basket;
use Mic2100\Cart\Contracts\OfferInterface;
use Mic2100\Cart\Item;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    /**
     * @param string $productCode
     * @param string $productName
     * @param float $price
     * @param int $quantity
     * @return void
     *
     * @dataProvider dataItems
     */
    public function testAddItem(string $productCode, string $productName, float $price, int $quantity)
    {
        $item = new Item($productCode, $productName, $price, $quantity);
        $basket = new Basket();
        $basket->addItem($item);

        $items = $basket->getItems();

        $this->assertNotEmpty($items);
        $this->assertSame($productCode, $items[$productCode]->getProductCode());
        $this->assertSame($productName, $items[$productCode]->getProductName());
        $this->assertSame($price, $items[$productCode]->getPrice());
        $this->assertSame($quantity, $items[$productCode]->getQuantity());
    }

    /**
     * @return array[]
     */
    public function dataItems(): array
    {
        return [
            ['P001', 'Photography', 200, 1],
            ['P002', 'Floorplan', 100, 1],
            ['P003', 'Gas Certificate', 83.50, 1],
            ['P004', 'EICR Certificate', 51.00, 1],
        ];
    }

    /**
     * @param string $productCode
     * @param string $productName
     * @param float $price
     * @param int $quantity
     * @return void
     *
     * @dataProvider dataItems
     */
    public function testRemoveItem(string $productCode, string $productName, float $price, int $quantity)
    {
        $item = new Item($productCode, $productName, $price, $quantity);
        $basket = new Basket();
        $basket->addItem($item);

        $this->assertNotEmpty($basket->getItems());

        $basket->removeItem($productCode);

        $this->assertEmpty($basket->getItems());
    }

    /**
     * @param Item[] $items
     * @return void
     *
     * @dataProvider dataItemObjects
     */
    public function testGetBasketTotalsWithoutDiscount(array $items)
    {
        $basket = new Basket();
        $gross = 0;
        foreach ($items as $item) {
            $gross += $item->getPrice() * $item->getQuantity();
            $basket->addItem($item);
        }
        $net = $gross;

        $this->assertCount(count($items), $basket->getItems());

        $totals = $basket->getBasketTotals();

        $this->assertSame($gross, $totals->getGross());
        $this->assertSame($net, $totals->getNet());
        $this->assertSame(0.0, $totals->getDiscount());
    }

    /**
     * @param Item[] $items
     * @return void
     *
     * @dataProvider dataItemObjects
     */
    public function testGetBasketTotalsWithDiscount(array $items)
    {
        $offer = [new class implements OfferInterface {
            public function calculatePrice(float $price): float
            {
                //calculate 25% off price
                return (float)number_format($price * 0.75, 2, '.', '');
            }
        }];
        $basket = new Basket();
        $gross = 0;

        foreach ($items as $item) {
            $item->setSpecialOffers($offer);

            $gross += $item->getPrice() * $item->getQuantity();
            $basket->addItem($item);
        }
        $net = $offer[0]->calculatePrice($gross);

        $this->assertCount(count($items), $basket->getItems());

        $totals = $basket->getBasketTotals();
        $discount = $gross - $net;

        $this->assertSame($gross, $totals->getGross());
        $this->assertSame($net, $totals->getNet());
        $this->assertSame($discount, $totals->getDiscount());
    }

    /**
     * @return array[]
     */
    public function dataItemObjects(): array
    {
        return [
            [
                [
                    new Item('P001', 'Photography', 200, 1),
                    new Item('P002', 'Floorplan', 100, 1),
                    new Item('P003', 'Gas Certificate', 83.50, 1),
                    new Item('P004', 'EICR Certificate', 51.00, 1),
                ],
            ],
            [
                [
                    new Item('P001', 'Photography', 200, 20),
                    new Item('P002', 'Floorplan', 100, 10),
                ],
            ],
        ];
    }
}