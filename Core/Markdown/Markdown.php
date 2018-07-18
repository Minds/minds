<?php
/**
 * Parsedown extension that adds classes to links
 * Credits to https://github.com/erusev/parsedown-extra
 */
namespace Minds\Core\Markdown;

class Markdown extends \Parsedown
{
    protected $regexAttribute = '(?:[#.][-\w]+[ ]*)';

    protected function parseAttributeData($attributeString)
    {
        $Data = array();
        $attributes = preg_split('/[ ]+/', $attributeString, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($attributes as $attribute) {
            if ($attribute[0] === '#') {
                $Data['id'] = substr($attribute, 1);
            } else # "."
            {
                $classes [] = substr($attribute, 1);
            }
        }
        if (isset($classes)) {
            $Data['class'] = implode(' ', $classes);
        }
        return $Data;
    }

    protected function inlineLink($Excerpt)
    {
        $Link = parent::inlineLink($Excerpt);
        $remainder = substr($Excerpt['text'], $Link['extent']);
        if (preg_match('/^[ ]*{(' . $this->regexAttribute . '+)}/', $remainder, $matches)) {
            $Link['element']['attributes'] += $this->parseAttributeData($matches[1]);
            $Link['extent'] += strlen($matches[0]);
        }
        return $Link;
    }
}