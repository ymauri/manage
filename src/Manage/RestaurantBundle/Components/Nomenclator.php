<?php

namespace Manage\RestaurantBundle\Controller;



/*
Clase para controlar los nomencladores de las Reglas.
Las claves de estos arrays representan segmentos de codigo PHP que se enevutan en RuleController->executeRule()
*/
use Doctrine\ORM\EntityManager;

class Nomenclator {

    //Objetivo de  la regla. Es el nombre del metodo que se ejecuta asiciado a la regla gestionada.
	const RULE_GOAL = "";
    /*const RULE_GOAL  = array(
        'changepricenow'        => 'Modify Listing Price'
    );*/
    //Condicion de la regla. Es la condicion que debe tomarse en cuenta para aplicar la regla.
	const RULE_CONDITION = "";
    /*const RULE_CONDITION = array(
        'listing_available_more'   => 'More than X listings availables ',
        'listing_available_less'   => 'Less than X listings availables ',
    );*/

    //Resultado de la regla. Es la accion que se lleva a cabo si se cumple la condicion de la regla.
	const RULE_ACTION ="";
    /*const RULE_ACTION = array(
        'listing_update_price'   => 'Update Listing Price',
    );*/

    const LISTING_AVAILABLE = 'available';
    const LISTING_CANCELED = 'canceled';
    const LISTING_UNAVAILABLE = 'unavailable';
    const LISTING_RESERVED = 'reserved';
    const LISTING_BOOKED = 'booked';
    const LISTING_STUDIO = 'Studio';
    const LISTING_APARTMENT = 'Apartment';
    const LISTING_SEAVIEW = 'Sea View';
    const LISTING_SOUTH = 'South';
    const LISTING_WEST = 'West';
    const LISTING_CLEAN = 'clean';
    const LISTING_DIRTY = 'dirty';
    const LISTING_CHECKEDOUT = 'checkedOut';
    const LISTING_WORKING = 'working';
    const LISTING_LONGSTAY = 'longStay';

    const  PLANNING_WEEKLY = 'weekly';
    const  PLANNING_MONTHLY = 'monthly';
    const  PLANNING_QUATERLY = 'quarterly';
    const  PLANNING_BIANNUAL = 'biannual';
    const  PLANNING_YEARLY = 'yearly';
}
