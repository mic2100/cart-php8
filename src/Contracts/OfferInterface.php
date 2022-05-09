<?php

namespace Mic2100\Cart\Contracts;

interface OfferInterface
{
    /**
     * Calculates the new price when including the offer
     *
     * @param float $price
     * @return float
     */
    public function calculatePrice(float $price): float;
}