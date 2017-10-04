<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class BartecDe_Categories extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'bartec.de';
    public $project_link = 'https://www.bartec.de';
    public $source = 'https://www.bartec.de/en/products/';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'BartecDe_Categories';

    function __construct()
    {
        $this->cookieFile = __DIR__.'/cookie/'.class_basename(get_class($this)).'/'.class_basename(get_class($this)).'.txt';
    }

    public function getSources($opt = [])
    {
        $opt['useContentCacheOnDate'] = $this->cache;
        $opt['saveContentCache'] = $this->cache;
        $sources = false;
        $url = isset($opt['url']) ? $opt['url'] : $this->source;
        $content = $this->loadUrl($url, $opt);
        if ( !$content )
        {
            return $sources;
        }
        $nokogiri = new nokogiri($content);
        $cats = $nokogiri->get(".product-overview li a")->toArray();
        $images = $nokogiri->get(".product-overview li a img")->toArray();
        foreach ($cats as $k => $cat)
        {
            $link = $this->fixUrl(@$cat['href']);
            $name = trim($cat['__ref']->nodeValue);
            $image = $this->fixUrl($images[$k]['src']);
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
        if ( preg_match("%<a[^>]*class=\"[^\"]*focus[^>]*>[^<]*</a>[^<]*(<ul>.*?</ul>)%uis", $content, $match) )
        {
            $content = $match[1];
            $content = ParseItHelpers::fixEncoding($content);
            $content = ParseItHelpers::fixHeader($content);
            $menu = new nokogiri($content);
            $cats = $menu->get("li a")->toArray();
            foreach ( $cats as $cat )
            {
                $link = $this->fixUrl(@$cat['href']);
                $name = trim($cat['__ref']->nodeValue);
                $image = '';
                $hash = md5($link);
                if ( !isset($sources[$hash]) )
                {
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
        }

        if ( $url === $this->source )
        {
            $cats = $nokogiri->get("a.cat_sub_head")->toArray();
            foreach ( $cats as $cat )
            {
                $link = $this->fixUrl(@$cat['href']);
                $name = trim($cat['__ref']->nodeValue);
                $image = '';
                $hash = md5($link);
                if ( !isset($sources[$hash]) )
                {
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
        if ( !isset($nokogiri->get(".print-content h1")->toArray()[0]) )
        {
            return false;
        }
        $name = trim($nokogiri->get(".print-content h1")->toArray()[0]['__ref']->nodeValue);
        $image = $this->fixUrl($nokogiri->get("section.keyvisual img")->toArray()[0]['src']);
        $active = $nokogiri->get("li a.active")->toArray();
        $fields['parent'] = [];
        if ( isset($active[count($active)-1]) )
        {
            $parent = $this->fixUrl($active[count($active)-1]['href']);
            $fields['parent'] = [
                'url' => $this->fixUrl($active[count($active)-1]['href']),
                'title' => trim($active[count($active)-1]['__ref']->nodeValue)
            ];
        }
        $hash = md5($url);
        $data = [
            'donor_class_name' => $this->donor,
            'version' => $this->version_id,
            'hash' => $hash,
            'name' => $name,
            'image' => @$image,
            'desc' => '',
            'source' => $url,
            'category' => @$parent,
            'serialize' => @$fields,
        ];

        return $data;
    }
}
