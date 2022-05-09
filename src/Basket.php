<?php

namespace Mic2100\Cart;

class Basket
{
    /**
     * @var Item[]
     */
    private array $items = [];

    /**
     * @param Item $item
     * @return void
     */
    public function addItem(Item $item): void
    {
        $this->items[$item->getProductCode()] = $item;
    }

    /**
     * @param string $productCode
     * @return void
     */
    public function removeItem(string $productCode): void
    {
        unset($this->items[$productCode]);
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return BasketTotal
     */
    public function getBasketTotals(): BasketTotal
    {
        return new BasketTotal($this->getItems());
    }
}