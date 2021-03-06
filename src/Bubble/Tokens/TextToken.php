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

namespace Bubble\Tokens;

use Bubble\Parser\AttributesList;
use Bubble\Attributes\ElementAttribute;
use Bubble\Attributes\ValueAttribute;
use Bubble\Attributes\GenericAttribute;

/**
 * Text Token
 *
 * Parse and render texts.
 *
 * @category Tokens
 * @package  Bubble
 * @author   Axel Nana <ax.lnana@outlook.com>
 * @license  LGPL-3.0 <https://opensource.org/licenses/LGPL-3.0>
 * @link     http://bubble.na2axl.tk/docs/api/Bubble/Tokens/TextToken
 */
class TextToken extends BaseToken
{
    /**
     * Token name.
     */
    public const NAME = "text";

    /**
     * Token type.
     */
    public const TYPE = PRE_PARSE_TOKEN;

    protected function _parseAttributes()
    {
        if ($this->_element->hasAttributes()) {
            foreach ($this->_element->attributes as $attr) {
                switch ($attr->nodeName) {
                    case "element":
                        $this->_attributes->add(new ElementAttribute($attr, $this->_document));
                        break;

                    case "value":
                        $this->_attributes->add(new ValueAttribute($attr, $this->_document));
                        break;

                    default:
                        $this->_attributes->add(new GenericAttribute($attr, $this->_document));
                        break;
                }
            }
        }
    }

    /**
     * Gets the type of this token.
     *
     * @return integer
     */
    public function getType(): int
    {
        return self::TYPE;
    }

    /**
     * Gets the name of this token.
     *
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * Gets the path to this token
     * in the DOM template.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->_path;
    }

    /**
     * Gets the list of attributes in
     * this token.
     *
     * @return AttributesList
     */
    public function getAttributes(): AttributesList
    {
        return $this->_attributes;
    }

    /**
     * Parses the token.
     *
     * @return void
     */
    public function parse()
    {
        $this->_attributes->parse();
    }

    /**
     * Render the token.
     *
     * @return \DOMNode
     */
    public function render(): \DOMNode
    {
        $attributesBuffer = array();
        $wrapper = null;
        $value = $this->_element->nodeValue;

        foreach ($this->_attributes as $attr) {
            if ($attr instanceof ElementAttribute) {
                $wrapper = $attr->getValue();
            } elseif ($attr instanceof ValueAttribute) {
                $value = $attr->getValue();
            } else {
                array_push($attributesBuffer, $attr);
            }
        }

        $domElement = null;

        if ($wrapper === null && count($attributesBuffer) === 0) {
            $domElement = $this->_document->createTextNode($value);
        } else {
            $wrapper = $wrapper === null ? "span" : $wrapper;
            $domElement = $this->_document->createElement($wrapper, $value);

            if (count($attributesBuffer) > 0) {
                foreach ($attributesBuffer as $attr) {
                    $attribute = $attr->render();
                    $domElement->appendChild($attribute);
                }
            }
        }

        return $domElement;
    }
}
