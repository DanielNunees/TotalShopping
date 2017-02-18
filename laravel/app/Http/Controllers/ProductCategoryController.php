<?php

namespace App\Http\Controllers;

use App\Models\Products\ProductCategories;
use App\Models\CategoryLang;
use App\Http\Controllers\ProductController;

class ProductCategoryController extends Controller
{
    public static function getCategoriesNames(){
    	return CategoryLang::getCategories();

    }

    public static function getProductsFromCategory($category){
    	$id_product = ProductCategories::getProducts($category);

    	foreach ($id_product as $key => $value) {
    		$products[] = ProductController::retrivingProduct($value['id_product']);
    	}

    	return $products;
    }
}
