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
use Exception;

class ProductController extends Controller
{

    public static function retrivingProduct($id_product){
            /* Validate if a product id exceed the quantity on DB */

            if(!is_numeric($id_product)) return response()->json(['error' => 'not_numeric_id'], 500);

            if($id_product==0 || $id_product>Product::max('id_product')) return response()->json(['error' => 'page_not_found'], 404);
            /* End of Validate */

            $description_name = ProductLang::where('id_product',$id_product)->where('id_lang',2)->select('name','description','id_product')->get();

            

            if($description_name->isEmpty()){
                return response()->json(['error' => 'is_empty'], 404);
            }

            $productAttributes = ProductStock::findOrFail($id_product)->where('id_product',$id_product)->where('id_product_attribute','!=',0)->where('quantity','>',0)->select('id_product_attribute')->get();
            


            foreach ($description_name as $product) {
                $product->ProductPrice; //Price of product
                $product->ProductImages; //Images of product
                $product->ProductStockComplete; //verifica a quantidade no stock
            }


            $description_name = json_decode($description_name,true);
            
                foreach ($description_name[0]['product_images'] as $id_image) {
                    $idImage = (string) $id_image['id_image'];               //Retriving the cover image id,
                    $image = '/img/p/';                                   //Set a path,create a new
                    for ($i = 0; $i < strlen($idImage); $i++) {           //variable 'image',insert in 
                      $image .= $idImage[$i] . '/';                       //$products array and return
                    }
                    $image .= $idImage . '.jpg';
                    $images[] = array('image'=>$image);

                }

            unset($description_name[0]['product_images']);
            
            foreach ($productAttributes as $product) {
                foreach ($product->ProductAttributeName as $value) {
                    $attributes_names[] = $value;
                    $id_attribute[] = $value->pivot->id_attribute;
                }
            }
            if(isset($id_attribute)){
                $attributes = Attributes::select('id_attribute_group')->distinct()->find($id_attribute);
                foreach ($attributes as $value) {
                    $value->AttributesGroupLang;
                }
            }
            
            if(isset($attributes_names)){
                foreach ($attributes_names as $key => $value) {
                    $aux[$key] = $value->name;
                }
                
                $v=1;
                $i=0;
                if(count($attributes)==2){
                    while (isset($aux[$v])&&($key = array_search($aux[$v], $aux)) !== NULL )
                    {   
                        //return $aux[$key];
                        $color_sizes[]=array($description_name[0]['product_stock_complete'][$i]['id_product_attribute']=>$aux[$key-1]);
                        unset($aux[$key]);
                        $v+=2;
                        $i++;
                    }
                }else{
                    $color_sizes = $aux;
                }
            }
            if(isset($attributes_names)){
                $array = array('description' => $description_name,'images'=>$images,'attributes'=>$color_sizes, 'attributes_type'=>$attributes);
            }
            else{
                $array = array('description' => $description_name);
            }  
            return $array;
        }
    }
