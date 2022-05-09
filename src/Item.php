<?php

namespace Mic2100\Cart;

class Item
{
    /**
     * @param string $productCode
     * @param string $productName
     * @param float $price
     * @param int $quantity
     * @param array $specialOffers
     */
    public function __construct(
        private string $productCode,
        private string $productName,
        private float $price,
        private int $quantity,
        private array $specialOffers = [],
    ) {
    }

    /**
     * @return string
     */
    public function getProductCode(): string
    {
        return $this->productCode;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return array
     */
    public function getSpecialOffers(): array
    {
        return $this->specialOffers;
    }

    /**
     * @param array $specialOffers
     * @return void
     */
    public function setSpecialOffers(array $specialOffers)
    {
        $this->specialOffers = $specialOffers;
    }

    /**
     * @return ItemTotal
     */
    public function getItemTotals(): ItemTotal
    {
        return new ItemTotal($this->getPrice() * $this->getQuantity(), $this->getSpecialOffers());
    }
}
