<?php
namespace Manage\RestaurantBundle\Twig\Extension;
/**
 * Class JsonDecode
 * @package Manage\RestaurantBundle\Twig\Extension
 * @author Kegan VanSickle <keganv@keganv.com>
 */
class JsonDecode extends \Twig_Extension
{
    public function getName()
    {
        return 'twig.json_decode';
    }

    public function getFilters()
    {
        return array(
            'json_decode'   => new \Twig_Filter_Method($this, 'jsonDecode')
        );
    }

    public function jsonDecode($string)
    {
        return json_decode($string);
    }
}
