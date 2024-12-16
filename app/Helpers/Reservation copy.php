<?php

namespace App\Helpers;

class Reservation
{
    public static function getState($initial_amount, $amount_due)
    {
        $state = 'pending';
        if($amount_due == 0 ){
            $state = 'totally paid';
        }
        $half_amount = $initial_amount/2;
        if(
            $amount_due < $initial_amount &&
            $amount_due > $half_amount
        ){
            $state = 'partially paid';
        }
        if(
            $amount_due <= $half_amount &&
            $amount_due > 0
        ){
            $state = 'confirmed';
        }
        if($amount_due == $initial_amount ){
            $state = 'pending';
        }
        return $state;
    }
}