<?php

namespace Mic2100\Cart\Offers;

use Mic2100\Cart\Contracts\OfferInterface;

class TwelveMonthContract implements OfferInterface
{
    private const MULTIPLIER = 0.9;

    /**
     * {@inheritDoc}
     *
     * @param float $price
     * @return float
     */
    public function calculatePrice(float $price): float
    {
        return (float)number_format($price * self::MULTIPLIER, 2, '.', '');

        //return BC math version if precision is required
        //return (float)bcmul((string)$price, (string)self::MULTIPLIER, 2);
    }
}