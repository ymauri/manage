<?php
/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2019 Setasign - Jan Slabon (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */

namespace Manage\RestaurantBundle\Components\fpdi\PdfParser\Filter;

use Manage\RestaurantBundle\Components\fpdi\PdfParser\PdfParserException;

/**
 * Exception for filters
 *
 * @package setasign\Fpdi\PdfParser\Filter
 */
class FilterException extends PdfParserException
{
    const UNSUPPORTED_FILTER = 0x0201;

    const NOT_IMPLEMENTED = 0x0202;
}
