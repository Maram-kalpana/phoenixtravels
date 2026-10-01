<?php

namespace App\Helpers;

class PercentageHelper
{
    /**
     * Calculate cutoff amount (percentage deduction)
     * Example: getCutoffAmount(1000, 10) = 100
     */
    public static function getCutoffAmount($amount, $percentage)
    {
        if ($amount <= 0 || $percentage <= 0) {
            return 0;
        }
        return ($percentage / 100) * $amount;
    }

    /**
     * Calculate amount after cutoff (original - cutoff)
     * Example: getAmountAfterCutoff(1000, 10) = 900
     */
    public static function getAmountAfterCutoff($amount, $percentage)
    {
        $cutoff = self::getCutoffAmount($amount, $percentage);
        return $amount - $cutoff;
    }

    /**
     * Calculate cutoff amount with maximum limit
     * Example: getCutoffAmountWithLimit(1000, 10, 50) = 50 (limited to 50)
     */
    public static function getCutoffAmountWithLimit($amount, $percentage, $maxCutoff = null)
    {
        $cutoff = self::getCutoffAmount($amount, $percentage);
        
        if ($maxCutoff && $cutoff > $maxCutoff) {
            return $maxCutoff;
        }
        
        return $cutoff;
    }

    /**
     * Calculate cutoff amount with minimum cutoff guarantee
     * Example: getCutoffAmountWithMin(1000, 5, 100) = 100 (minimum 100)
     */
    public static function getCutoffAmountWithMin($amount, $percentage, $minCutoff = null)
    {
        $cutoff = self::getCutoffAmount($amount, $percentage);
        
        if ($minCutoff && $cutoff < $minCutoff) {
            return $minCutoff;
        }
        
        return $cutoff;
    }
}