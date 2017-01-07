<?php
namespace  App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Products\ProductStock;
use App\Models\Products\ProductLang;
use Illuminate\Http\attributes_names;
use App\Models\Products\Product;
use App\Models\Products\ProductImages;
use App\Models\Attributes\Attributes;
use App\Exceptions\Handler;
use App\Tools\Tools;
use Exception;

class ProductController extends Controller
{

    public static function retrivingProduct($id_product){
            
            /* Validate if a product id exceed the quantity on DB */
            if(!is_array($id_product)){
                if(!is_numeric($id_product)) return response()->json(['error' => 'not_numeric_id'], 500);

                if($id_product==0 || $id_product>Product::max('id_product')) return response()->json(['error' => 'page_not_found'], 404);
            }
            
            /* End of Validate */

            /*decricao do produto*/
            $description = ProductController::productDescription($id_product);
            $attributes = ProductController::productAttributes($id_product);
            $images = ProductController::productImages($id_product);
            foreach ($description as $product) {
                $product->ProductPrice->price;
            }
            $description = json_decode($description,true);

            return  array('description' => $description,'images'=>$images,'attributes'=>$attributes);
        }

        public static function productDescription($id_product){  //$id_product pode ser um valor ou um object

            $description_name = ProductLang::ProductResume($id_product);
            if($description_name->isEmpty()){
                return response()->json(['error' => 'is_empty'], 404);
            }
            return $description_name; //retorna o nome e a descricao do produto.
        }

        public static function productAttributes($id_product){  //$id_product pode ser um valor ou um object
            $productAttributes = ProductStock::RetrivingAttributes($id_product);
            
            $attributes = [];
            if(is_array($id_product)){
                foreach ($productAttributes as $product) {
                    foreach ($product->ProductAttributeName as $value) {
                        $attributes[] = array('id_product_attribute'=>$value->pivot->id_product_attribute,'name'=>$value->name,'id_product'=>$product->id_product);
                    }
                }
                return $attributes;
            }

            foreach ($productAttributes as $product) {
                foreach ($product->ProductAttributeName as $value) {
                    $attributes[] = array('id_product_attribute'=>$value->pivot->id_product_attribute,'name'=>$value->name);
                }
            }
            return Tools::unique_multidim_array($attributes,'id_product_attribute');
            return $attributes;
        }

        public static function productImages($id_product){
            $productImages = ProductImages::ProductImages($id_product);
            foreach ($productImages as $id_image) {
                $idImage = (string) $id_image['id_image'];            //Retriving the cover image id,
                $image = '/img/p/';                                   //Set a path,create a new
                for ($i = 0; $i < strlen($idImage); $i++) {           //variable 'image',insert in 
                  $image .= $idImage[$i] . '/';                       //$products array and return
                }
                $image .= $idImage . '.jpg';
                $images[] = array('id_product'=>$id_image['id_product'],'image'=>$image);

            }
            return $images;
        }

        public static function productQuantityInStock($id_product){
            return ProductStock::ProductQuantity($id_product);
        }

        public static function productUpdateStock($id_product,$id_product_attribute,$quantity){
            ProductStock::updateQuantity($id_product,$id_product_attribute,$quantity);
        }



    }
