<?php

namespace App\Http\Controllers;

use App\Donors\B2BDivehouseRuCategory;
use App\Donors\B2BDivehouseRuProduct;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\CategoryToProduct;
use App\Models\Product;
use App\Models\Source;
use App\Models\Specification;
use App\Models\SpecificationToProduct;
use App\ParseIt;
use Illuminate\Http\Request;
Use Validator;

class ParseitController extends Controller
{
    public function start(Request $request)
    {
        $donor = new B2BDivehouseRuCategory();
        $opt['cookieFile'] = $donor->cookieFile;
//        $opt['url'] = 'http://b2b.divehouse.ru/catalog/lodochnye_motory_grebnye_vinty_raskhodniki_i_zapchasti/';
        $categories = $donor->getSources($opt);
        print_r(count($categories));
        foreach ( $categories as $category )
        {
            $validator = Validator::make($category, Category::rules());
            if ($validator->fails())
            {
                $message = $validator->errors()->first();
                LoggerController::logToFile($message, 'info', $category, true);
            }
            else
            {
                Category::saveOrUpdate([
                    'title' => $category['title'],
                    'source' => $category['source'],
                    'hash' => $category['hash'],
                    'parent_id' => 0,
                ]);
            }
        }
    }

    public function product_sources(Request $request)
    {
        $donor_product = new B2BDivehouseRuProduct();
        $opt['cookieFile'] = $donor_product->cookieFile;
        $opt['url'] = 'http://b2b.divehouse.ru/catalog/reklamnye_materialy_jetpilot/';
        $product_sources = $donor_product->getSources($opt);
        foreach ( $product_sources as $source )
        {
            $validator = Validator::make($source, Source::rules());
            if ($validator->fails())
            {
                $message = $validator->errors()->first();
                LoggerController::logToFile($message, 'info', $source, true);
            }
            else
            {
                Source::saveOrUpdate([
                    'source' => $source['source'],
                    'hash' => $source['hash'],
                ]);
            }
        }
    }

    public function product_data(Request $request)
    {
        $donor_product = new B2BDivehouseRuProduct();
        $opt['cookieFile'] = $donor_product->cookieFile;
        $opt['url'] = 'http://b2b.divehouse.ru/catalog/dopolnitelnoe_oborudovanie_coltri/vozdushnyy_resiver_2ballona_50l_350bar_na_rame_s_ventilyami_i_soedineniem_coltri/';
        $product = $donor_product->getData($opt['url'], $opt);
        $validator = Validator::make($product, Product::rules());
        if ($validator->fails())
        {
            $message = $validator->errors()->first();
            LoggerController::logToFile($message, 'info', $product, true);
        }
        else
        {
            $pid = Product::saveOrUpdate($product);
            if ( isset($product['specific']) && is_array($product['specific']) )
            {
                foreach ( $product['specific'] as $title => $value )
                {
                    $specific = Specification::firstOrCreate(['title' => $title]);
                    SpecificationToProduct::firstOrCreate(['product_id' => $pid, 'specific_id' => $specific->id])->update(['value' => $value]);
                }
            }
            if ( isset($product['attr']) && is_array($product['attr']) )
            {
                foreach ( $product['attr'] as $attr )
                {
                    $validator = Validator::make($attr, Attribute::rules());
                    if ($validator->fails())
                    {
                        $message = $validator->errors()->first();
                        LoggerController::logToFile($message, 'info', $product, true);
                    }
                    else
                    {
                        Attribute::firstOrCreate(['product_id' => $pid, 'title' => $attr['title']])->update(['price' => $attr['price'], 'instock' => $attr['instock']]);
                    }
                }
            }
            if ($category = Category::whereSource($product['category'])->get()->first())
            {
                CategoryToProduct::firstOrCreate(['product_id' => $pid, 'category_id' => $category->id]);
            }
        }
        print_r(@$product);die('da');
    }

    public function sources(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donor = new B2BDivehouseRuCategory();
        $donor_product = new B2BDivehouseRuProduct();
        $opt['cookieFile'] = $donor->cookieFile;
        do
        {
            $next_cat = Category::where(['parseit' => 0])->get()->first();
            if ($next_cat)
            {
                $next_cat->update(['parseit' => 1]);
                $opt['url'] = $next_cat->source;
                if ($categories = $donor->getSources($opt))
                {
                    foreach ($categories as $category)
                    {
                        $validator = Validator::make($category, Category::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $category, true);
                        }
                        else
                        {
                            Category::saveOrUpdate([
                                'title' => $category['title'],
                                'source' => $category['source'],
                                'hash' => $category['hash'],
                                'parent_id' => !empty($next_cat) ? $next_cat->id : 0,
                            ]);
                        }
                    }
                }
                if ($product_sources = $donor_product->getSources($opt))
                {
                    foreach ( $product_sources as $source )
                    {
                        $validator = Validator::make($source, Source::rules());
                        if ($validator->fails())
                        {
                            $message = $validator->errors()->first();
                            LoggerController::logToFile($message, 'info', $source, true);
                        }
                        else
                        {
                            Source::saveOrUpdate([
                                'source' => $source['source'],
                                'hash' => $source['hash'],
                            ]);
                        }
                    }
                }
            }
            else
            {
                die('Done');
            }
            if ($start < time() - ($exec_time - 30))
            {
                die('End exec time');
            }
        }
        while( true );
    }

    public function products(Request $request)
    {
        $exec_time = env('RUN_TIME', 0);
        $start = time();
        @set_time_limit($exec_time);
        $donor_product = new B2BDivehouseRuProduct();
        $opt['cookieFile'] = $donor_product->cookieFile;
        do
        {
            $next_source = Source::where(['parseit' => 0, 'available' => 1])->get()->first();
            if ($next_source)
            {
                $next_source->update(['parseit' => 1]);
                $opt['url'] = $next_source->source;
                if ($product = $donor_product->getData($opt['url'], $opt))
                {
                    $validator = Validator::make($product, Product::rules());
                    if ($validator->fails())
                    {
                        $next_source->update(['available' => 0]);
                        Product::whereId($next_source->product_id)->update(['instock' => 0]);
                        $message = $validator->errors()->first();
                        LoggerController::logToFile($message, 'info', $product, true);
                    }
                    else
                    {
                        $pid = Product::saveOrUpdate($product);
                        Source::whereId($next_source->id)->update(['product_id' => $pid]);
                        if ( isset($product['specific']) && is_array($product['specific']) )
                        {
                            foreach ( $product['specific'] as $title => $value )
                            {
                                $specific = Specification::firstOrCreate(['title' => $title]);
                                SpecificationToProduct::firstOrCreate(['product_id' => $pid, 'specific_id' => $specific->id])->update(['value' => $value]);
                            }
                        }
                        if ( isset($product['attr']) && is_array($product['attr']) )
                        {
                            foreach ( $product['attr'] as $attr )
                            {
                                $validator = Validator::make($attr, Attribute::rules());
                                if ($validator->fails())
                                {
                                    $message = $validator->errors()->first();
                                    LoggerController::logToFile($message, 'info', $product, true);
                                }
                                else
                                {
                                    Attribute::firstOrCreate(['product_id' => $pid, 'title' => $attr['title']])->update(['price' => $attr['price'], 'instock' => $attr['instock']]);
                                }
                            }
                        }
                        if ($category = Category::whereSource($product['category'])->get()->first())
                        {
                            CategoryToProduct::firstOrCreate(['product_id' => $pid, 'category_id' => $category->id]);
                        }
                    }
                }
            }
            else
            {
                die('Done');
            }
            if ($start < time() - ($exec_time - 30))
            {
                die('End exec time');
            }
        }
        while( true );
    }
}