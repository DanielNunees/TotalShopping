<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Products\ProductLang;
use App\Http\Controllers\ProductController;

class homeController extends Controller
{
    public function index(){
     return ProductController::retrivingProduct([],true);
    }
}