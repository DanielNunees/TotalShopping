<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Products\ProductStock;
use App\Models\Products\ProductLang;
use Illuminate\Http\attributes_names;
use App\Models\Products\Product;
use App\Models\Products\ProductImages;
use App\Models\Attributes\AttributesLang;
use App\Models\Products\ProductAttributeCombination;
use App\Exceptions\Handler;
use App\Tools\Tools;
use Exception;

class ProductController extends Controller
{  
    public static function retrivingProduct($id_product,$id_product_attribute=null){
        /* Validate if a product id exceed the quantity on DB */
        
        ProductController::getError($id_product);
        
        return ProductController::getProduct($id_product,$id_product_attribute);
    }

    public static function getProduct($id_product,$id_product_attribute){
        $description_name = ProductLang::ProductResume($id_product)->unique('id_product');
        $images = ProductController::productImages($description_name->pluck('id_product')->toArray());
        $productAttributes = ProductController::productAttributes($description_name->pluck('id_product')->toArray())->toArray();

        foreach ($productAttributes as $key => $value) {
            if(is_null($id_product_attribute)){
                $attributes[$value['id_product']][] = ['id_product_attribute'=>$value['id_product_attribute'],
                                                      'name'=>$value['product_attribute_name'][0]['name'],
                                                      'quantity'=>$value['quantity']];
                $final = $description_name->where('id_product',$value['id_product'])->values();
                $final[0]->attributes = ($attributes[$value['id_product']]);
            }
            if(isset($id_product_attribute)&&in_array($value['id_product_attribute'], $id_product_attribute)){
                $attributes[$value['id_product']][] = ['id_product_attribute'=>$value['id_product_attribute'],
                                                      'name'=>$value['product_attribute_name'][0]['name'],
                                                      'quantity'=>$value['quantity']];
                $final = $description_name->where('id_product',$value['id_product'])->values();
                $final[0]->attributes = ($attributes[$value['id_product']]);
            }
        }

        foreach ($images as $key => $value) {
            $image[$value['id_product']][] = $value['image'];
            $final = $description_name->where('id_product',$value['id_product'])->values();
            $final[0]->image = ($image[$value['id_product']]);
        }

        foreach ($description_name as $product) {
            $product->ProductPrice->price;
        }

        if(is_array($id_product))
            return $description_name;
        return $description_name[0];

    }

    public static function productDescription($id_product){  //$id_product pode ser um valor ou um object
        $description_name = ProductLang::ProductResume($id_product);
        
        return $description_name; //retorna o nome e a descricao do produto.
    }

    public static function productAttributes($id_product){  //$id_product pode ser um valor ou um object
        $productAttributes = ProductStock::RetrivingAttributes($id_product);
        foreach ($productAttributes as $key => $product) {
            $product->ProductAttributeName;
        }
             
        return $productAttributes->unique('id_product_attribute');
    }

    public static function productImages($id_product){
        $productImages = ProductImages::ProductImages($id_product);
        $id_image = $productImages->pluck('id_image');
        $product_id =  $productImages->pluck('id_product');

        foreach ($id_image as $key => $id_image) {
            $idImage = (string) $id_image;            //Retriving the cover image id,
            $image = '/img/p/';                                   //Set a path,create a new
            for ($i = 0; $i < strlen($idImage); $i++) {           //variable 'image',insert in 
              $image .= $idImage[$i] . '/';                       //$products array and return
            }
            $image .= $idImage . '.jpg';
            $images[] = array('image'=>$image,'id_product'=>$product_id[$key]);
        }
        return $images;
    }

    public static function productQuantityInStock($id_product){
        return ProductStock::ProductQuantity($id_product);
    }

    public static function productUpdateStock($id_product,$id_product_attribute,$quantity){
        ProductStock::updateQuantity($id_product,$id_product_attribute,$quantity);
    }

    public static function productIdShop($id_product){
        return Product::getShopId($id_product);
    }

    public static function getError($id_product){
        if(!is_array($id_product)){
            if(!is_numeric($id_product))
                throw new Exception('Product id needs to be numeric'); 
            
            if($id_product>Product::max('id_product'))
                throw new Exception('Id Exceeded Maximum');
            
            if($id_product<=0 )
                throw new Exception("Product Id Not Allowed", 400);                        
        }
        else{
            foreach ($id_product as $key => $value) {
               if(!is_numeric($value) || $value<=0)
                throw new Exception('Id needs to be greater than zero');
            }
        }
    }



    }
