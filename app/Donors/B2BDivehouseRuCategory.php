<?php

namespace App\Donors;

use ParseIt\nokogiri;
use ParseIt\ParseItHelpers;

Class B2BDivehouseRuCategory extends B2BDivehouseRu {

    public function getSources($opt = [])
    {
        $categories = [];
        if (isset($opt['url']))
        {
            $url = $opt['url'];
        }
        else
        {
            $url = $this->source;
        }
        $content = $this->loadUrl($url, $opt);
        $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
        $content = iconv('windows-1251', 'UTF-8', $content);
        $content = ParseItHelpers::fixEncoding($content);
        $nokogiri = new nokogiri($content);
        $catalog_bloks = $nokogiri->get(".catalog-block")->toArray();
        foreach ($catalog_bloks as $catalog_blok)
        {
            $url = $this->fixUrl($catalog_blok['href']);
            $parent = @$opt['url'];
            $title = trim(str_replace('Подробнее', '', trim(@$catalog_blok['__ref']->nodeValue)));
            $hash = md5($parent.$url.$title);
            $categories[] = [
                'source' => $url,
                'hash' => $hash,
                'title' => $title,
                'parent' => $parent,
            ];
        }

        return $categories;
    }

//    public function getData($url, $source = [])
//    {
//        $data = false;
//
//        return $data;
//    }
}
