<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class BartecDe_Products extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'bartec.de';
    public $project_link = 'https://www.bartec.de';
    public $source = 'https://www.bartec.de/en/products/';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'BartecDe_Products';

    function __construct()
    {
        $this->cookieFile = __DIR__.'/cookie/'.class_basename(get_class($this)).'/'.class_basename(get_class($this)).'.txt';
    }

    public function getSources($opt = [])
    {
        $opt['useContentCacheOnDate'] = $this->cache;
        $opt['saveContentCache'] = $this->cache;
        $sources = false;
        if ( isset($opt['url']) )
        {
            $content = $this->loadUrl($opt['url'], $opt);
            if ( !$content )
            {
                return $sources;
            }
            $nokogiri = new nokogiri($content);
            $list = $nokogiri->get(".search-list li")->toArray();
            $names = $nokogiri->get(".search-list li .search-beschreibung strong")->toArray();
            foreach ($list as $k => $li)
            {
                $link = $this->fixUrl(@$li['a'][0]['href']);
                $name = $names[$k]['__ref']->nodeValue;
                $image = $this->fixUrl($li['a'][0]['img']['src']);
                $hash = md5($link);
                $sources[$hash]= [
                    'hash' => $hash,
                    'name' => $name,
                    'image' => $image,
                    'source' => $link,
                    'donor_class_name' => $this->donor,
                    'version' => $this->version_id,
                ];
            }
        }

        return $sources;
    }

    public function getData($url, $source = [])
    {
        $data = false;
        $source['useContentCacheOnDate'] = $this->cache;
        $source['saveContentCache'] = $this->cache;
        $source['referer'] = $url;
        $content = $this->loadUrl($url, $source);
        if ( !$content )
        {
            return false;
        }
//        $content = ParseItHelpers::fixEncoding($content);
//        $content = ParseItHelpers::fixHeader($content);
        $nokogiri = new nokogiri($content);
        $name = trim(@$nokogiri->get("h1.product-detail")->toArray()[0]['__ref']->nodeValue);
        $image = $this->fixUrl($nokogiri->get("img.product-picture")->toArray()[0]['src']);
        if ( preg_match("%<h6>Description</h6>(.*?)</div>%uis", $content, $match) )
        {
            $desc = trim($match[1]);
        }
        $features = $nokogiri->get(".features-product-detail li")->toArray();
        foreach ($features as $feature)
        {
            $fields['features'][] = $feature['__ref']->nodeValue;
        }
        if ( preg_match("%<div class=\"exprotection-product-detail\">(.*?)</div>%uis", $content, $match) )
        {
            $Marking = trim($match[1]);
            preg_match_all("%src='(.*?)'%uis", $Marking, $matches);
            foreach ( $matches[1] as $match )
            {
                $Marking = str_replace($match, $this->fixUrl($match), $Marking );
            }
            $fields['marking'][] = $Marking;
        }
        $TechData = $nokogiri->get(".ProdCatDetailsTechData tr")->toArray();
        foreach ($TechData as $row)
        {
            $title = @$row['td'][0]['__ref']->nodeValue;
            $value = is_array(@$row['td'][1]['#text']) ? trim(implode('<br>', @$row['td'][1]['#text'])) : trim(@$row['td'][1]['#text']);
            $fields['technical data'][$title] = $value;
        }
        $product_detail = $nokogiri->get(".product-detail")->toArray();
        if ( isset($product_detail[count($product_detail)-1]['p'][1]['a']) )
        {
//            print_r($product_detail[count($product_detail)-1]['p']);die();
            foreach ($product_detail[count($product_detail)-1]['p'] as $k => $p)
            {
                if($k % 2 === 0)
                {
                    $title = @$p['#text'];
                }
                else
                {
                    unset($Documentation_rus, $Documentation_eng);
                    if ( isset($p['a'][0]) )
                    {
                        foreach ( $p['a'] as $a )
                        {
                            if ( preg_match("%lng_3%uis", @$a['href']) )
                            {
                                $Documentation_rus = $this->fixUrl(@$a['href']);
                            }
                            elseif ( preg_match("%lng_0%uis", @$a['href']) )
                            {
                                $Documentation_eng = $this->fixUrl(@$a['href']);
                            }
                        }
                        $fields['documentation'][] = ['title' => $title, 'link' => isset($Documentation_rus) ? $Documentation_rus : @$Documentation_eng];
                    }
                    else
                    {
                        if ( preg_match("%lng_3%uis", @$p['a']['href']) )
                        {
                            $Documentation_rus = $this->fixUrl(@$p['a']['href']);
                        }
                        elseif ( preg_match("%lng_0%uis", @$p['a']['href']) )
                        {
                            $Documentation_eng = $this->fixUrl(@$p['a']['href']);
                        }
                        $fields['documentation'][] = ['title' => $title, 'link' => isset($Documentation_rus) ? $Documentation_rus : @$Documentation_eng];
                    }
                    unset($title);
                }
            }
        }
        $category = $nokogiri->get(".accordion .focus")->toArray();
        if (isset($category[0]['href']))
        {
            $cat = $this->fixUrl($category[0]['href']);
            $fields['category'] = ['name' =>  @$category[0]['__ref']->nodeValue, 'link' => $this->fixUrl($category[0]['href'])];
        }
        $hash = md5($url);
        $data = [
            'donor_class_name' => $this->donor,
            'version' => $this->version_id,
            'hash' => $hash,
            'name' => $name,
            'image' => @$image,
            'desc' => @$desc,
            'source' => $url,
            'category' => @$cat,
            'serialize' => $fields,
        ];

        return $data;
    }
}
