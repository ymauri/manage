<?php
/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2019 Setasign - Jan Slabon (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */

namespace Manage\RestaurantBundle\Components\fpdi\PdfParser\Type;

use Manage\RestaurantBundle\Components\fpdi\PdfParser\PdfParserException;

/**
 * Exception class for pdf type classes
 *
 * @package setasign\Fpdi\PdfParser\Type
 */
class PdfTypeException extends PdfParserException
{
    /**
     * @var int
     */
    const NO_NEWLINE_AFTER_STREAM_KEYWORD = 0x0601;
}
