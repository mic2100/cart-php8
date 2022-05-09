<?php

namespace Mic2100\CartTests;

use Mic2100\Cart\Contracts\OfferInterface;
use Mic2100\Cart\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
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
    public function testCreateItem(string $productCode, string $productName, float $price, int $quantity)
    {
        $item = new Item($productCode, $productName, $price, $quantity);

        $this->assertSame($productCode, $item->getProductCode());
        $this->assertSame($productName, $item->getProductName());
        $this->assertSame($price, $item->getPrice());
        $this->assertSame($quantity, $item->getQuantity());
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
     * @param Item[] $items
     * @return void
     *
     * @dataProvider dataItemObjects
     */
    public function testItemTotalsWithoutDiscount(array $items)
    {
        foreach ($items as $item) {
            $gross = $item->getPrice() * $item->getQuantity();

            $totals = $item->getItemTotals();

            $this->assertSame($gross, $totals->getGross());
            $this->assertSame($gross, $totals->getNet());
            $this->assertSame(0.0, $totals->getDiscount());
        }


    }

    /**
     * @param Item[] $items
     * @return void
     *
     * @dataProvider dataItemObjects
     */
    public function testItemTotalsWithDiscount(array $items)
    {
        $offer = [new class implements OfferInterface {
            public function calculatePrice(float $price): float
            {
                //calculate 25% off price
                return (float)number_format($price * 0.75, 2, '.', '');
            }
        }];

        foreach ($items as $item) {
            $item->setSpecialOffers($offer);

            $gross = $item->getPrice() * $item->getQuantity();
            $net = $offer[0]->calculatePrice($gross);
            $discount = $gross - $net;

            $totals = $item->getItemTotals();

            $this->assertSame($gross, $totals->getGross());
            $this->assertSame($net, $totals->getNet());
            $this->assertSame($discount, $totals->getDiscount());
        }
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