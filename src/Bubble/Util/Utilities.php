<?php

/**
 * Bubble - A PHP template engine
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category  Library
 * @package   Bubble
 * @author    Axel Nana <ax.lnana@outlook.com>
 * @copyright 2018 Aliens Group, Inc.
 * @license   LGPL-3.0 <https://opensource.org/licenses/LGPL-3.0>
 * @version   GIT: 0.0.1
 * @link      http://bubble.na2axl.tk
 */

namespace Bubble\Util;

use Bubble\Tokens\TextToken;
use Bubble\Tokens\InputLabelToken;

/**
 * Utilities
 *
 * A set of commonly used methods.
 *
 * @category Util
 * @package  Bubble
 * @author   Axel Nana <ax.lnana@outlook.com>
 * @license  LGPL-3.0 <https://opensource.org/licenses/LGPL-3.0>
 * @link     http://bubble.na2axl.tk/docs/api/Bubble/Util/Utilities
 */
class Utilities
{
    public static function toString($value)
    {
        if (is_bool($value)) {
            $value = $value ? "true" : "false";
        }

        return strval($value);
    }

    public static function innerHTML(\DOMNode $element): string
    {
        $innerHTML = "";
        $children  = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveXML($child);
        }

        return $innerHTML;
    }

    public static function appendHTML(\DOMNode $parent, string $html)
    {
        $tmpDoc = new \DOMDocument();
        $tmpDoc->loadXML("<wrapper>{$html}</wrapper>");
        foreach ($tmpDoc->documentElement->childNodes as $node) {
            $node = $parent->ownerDocument->importNode($node, true);
            $parent->appendChild($node);
        }
    }

    public static function insertHTMLBefore(string $html, \DOMNode $refNode)
    {
        $tmpDoc = new \DOMDocument();
        $tmpDoc->loadXML("<wrapper>{$html}</wrapper>");
        foreach ($tmpDoc->documentElement->childNodes as $node) {
            $node = $refNode->ownerDocument->importNode($node, true);
            $refNode->parentNode->insertBefore($node, $refNode);
        }
    }
}
