<?php

namespace Mic2100\Cart;

class BasketTotal
{
    /**
     * @var float
     */
    private float $gross;

    /**
     * @var float
     */
    private float $discount;

    /**
     * @var float
     */
    private float $net;

    /**
     * @param array $items
     */
    public function __construct(private array $items = [])
    {
        $this->gross = 0;
        $this->discount = 0;
        $this->net = 0;

        $this->calculatePrice();
    }

    /**
     * @return void
     */
    private function calculatePrice(): void
    {
        /** @var Item $item */
        foreach ($this->items as $item) {
            $totals = $item->getItemTotals();
            $this->net += $totals->getNet();
            $this->gross += $totals->getGross();
            $this->discount += $totals->getDiscount();
        }

        $this->gross = (float)number_format($this->gross, 2, '.', '');
        $this->discount = (float)number_format($this->discount, 2, '.', '');
        $this->net = (float)number_format($this->net, 2, '.', '');
    }

    /**
     * @return float
     */
    public function getGross(): float
    {
        return $this->gross;
    }

    /**
     * @return float
     */
    public function getNet(): float
    {
        return $this->net;
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }
}