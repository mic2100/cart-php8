<?php

namespace Mic2100\Cart;

use Mic2100\Cart\Contracts\OfferInterface;

class ItemTotal
{
    /**
     * @var float
     */
    private float $discount;

    /**
     * @var float
     */
    private float $net;

    /**
     * @param float $gross
     * @param array $offers
     */
    public function __construct(private float $gross, private array $offers = [])
    {
        $this->calculatePrice();
    }

    /**
     * @return void
     */
    private function calculatePrice(): void
    {
        if (count($this->offers) === 0) {
            $net = $this->gross;
        } else {
            $net = 0;
            /** @var OfferInterface $offer */
            foreach ($this->offers as $offer) {
                $net += $offer->calculatePrice($this->gross);
            }
        }

        $this->net = $net;
        $this->discount = $this->gross - $this->net;
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