<?php

namespace App\Donors;

use ParseIt\_String;
use ParseIt\nokogiri;
use ParseIt\ParseItHelpers;

Class B2BDivehouseRuProduct extends B2BDivehouseRu {

    public function getSources($opt = [])
    {
        $sources = [];
        $url = @$opt['url'];
        $content = $this->loadUrl($url, $opt);
        $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
        $content = iconv('windows-1251', 'UTF-8', $content);
        $content = ParseItHelpers::fixEncoding($content);
        $nokogiri = new nokogiri($content);
        $products = $nokogiri->get(".item_title")->toArray();
        foreach ($products as $product)
        {
            if (!isset($product['href']))
            {
                continue;
            }
            $url = $this->fixUrl($product['href']);
            $hash = md5($url);
            $sources[] = [
                'source' => $url,
                'hash' => $hash,
            ];
        }
        if ( $next_page = @$nokogiri->get("#navigation_1_next_page")->toArray()[0]['href'] )
        {
            $next_page = $this->fixUrl($next_page);
            if ( $next_page === $url )
            {
                return $sources;
            }
            $opt['url'] = $next_page;
            foreach ( $this->getSources($opt) as $source )
            {
                $sources[] = $source;
            }
        }

        return $sources;
    }

    public function getData($url, $source = [])
    {
        $product = false;
        $content = $this->loadUrl($url, $source);
        $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
        $content = iconv('windows-1251', 'UTF-8', $content);
        $content = ParseItHelpers::fixEncoding($content);
        $nokogiri = new nokogiri($content);
        $title = trim(@$nokogiri->get(".item_title")->toArray()[0]['__ref']->nodeValue);
        $desc = trim(@$nokogiri->get("span[itemprop=description]")->toArray()[0]['__ref']->nodeValue);
        $lists = $nokogiri->get("ul.options li")->toArray();
        foreach ( $lists as $list )
        {
            $exp = explode(':', trim($list['__ref']->nodeValue));
            if (count($exp) === 2)
            {
                $options[trim($exp[0])] = trim($exp[1]);
            }
        }
        $sku = @$options['Артикул'];
        if ( preg_match("%Ед\.Изм\.\:([^\<]+)<%uis", $content, $match) )
        {
            $unit = trim($match[1]);
        }
        if ($item_price = @$nokogiri->get("div.item_price")->toArray())
        {
            $price = trim(last($item_price)['__ref']->nodeValue);
            $price = preg_replace("%\s+%uis", '', $price);
            $price = _String::parseNumber($price);
        }
        $instock = trim(@$nokogiri->get("td.iteminfo .quantity_int")->toArray()[0]['__ref']->nodeValue);
        $hash = md5($sku.$title);
        foreach ($nokogiri->get("a.catalog-detail-images")->toArray() as $img)
        {
            $images[] = $this->fixUrl($img['href']);
        }
        $category = $this->fixUrl(last($nokogiri->get("#breadcrumb a")->toArray())['href']);
        if ( preg_match("%arSKU\s+\=[^\[]+(.*?\]),%uis", $content, $match) )
        {
            $arSKU = trim($match[1]);
            $arSKU = str_replace('\'', '"', $arSKU);
            $arSKU = json_decode($arSKU);
            if (is_array($arSKU) || is_object($arSKU))
            {
                foreach ($arSKU as $item)
                {
                    $p = trim(_String::parseNumber(str_replace(' ', '', $item->PRICE)));
                    $DISCOUNT_PRICE = trim(_String::parseNumber(str_replace(' ', '', $item->DISCOUNT_PRICE)));
                    $attr[] = [
                        'title' => $item->{0},
                        'price' => !empty($DISCOUNT_PRICE) ? $DISCOUNT_PRICE : $p,
                        'instock' => $item->CATALOG_QUANTITY,
                    ];
                }
            }
        }
        $product = [
            'source' => $url,
            'title' => $title,
            'desc' => $desc,
            'sku' => $sku,
            'unit' => @$unit,
            'price' => @$price,
            'instock' => $instock,
            'hash' => $hash,
            'images' => @$images,
            'specific' => @$options,
            'attr' => @$attr,
            'category' => $category,
        ];

        return $product;
    }
}
