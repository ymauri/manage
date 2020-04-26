<?php
namespace Manage\RestaurantBundle\Nomenclator;

use ReflectionClass;

class PaymentMethod {
    const PIN = 1;
    const CREDIT_CARD = 2;
    const CONTANT = 3;
    const STRIPE_GUESTY = 4;
    const STRIPE_INVOICE = 5;
    const BANK = 6;
    const AIRBNB = 7;

    public static function all() {
        $thisClass = new ReflectionClass(__CLASS__);
        return $thisClass->getConstants();
    }

    public static function allAssociative() {
        return [ 
            1 => 'Pin', 
            2 => 'Credit Card', 
            3 => 'Contant', 
            4 => 'Stripe Via Guesty (VCC)', 
            5 => 'Stripe Via Invoice (Betaallink)',
            6 => 'Bank (Aanbetaling)', 'Airbnb'
        ];
    }
    
}