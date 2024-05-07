<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use App\Models\Product\Category;
use App\Models\Product\Order;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AdminsController extends Controller
{

    public function viewLogin() {

        return view('admins.login');

    }

    public function checkLogin(Request $request) {

        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {

            return redirect() -> route('admins.dashboard');
        }
        return redirect()->back()->with(['error' => 'error logging in']);

    }

    public function index() {

        $productsCount = Product::select()->count();
        $ordersCount = Order::select()->count();
        $categoriesCount = Category::select()->count();
        $adminsCount = Admin::select()->count();

        return view('admins.index', compact('productsCount', 'ordersCount', 'categoriesCount', 'adminsCount'));

    }

    public function displayAdmins() {

        $allAdmins = Admin::all();

        return view('admins.alladmins', compact('allAdmins'));

    }

    public function createAdmins() {

        return view('admins.createadmins');

    }

    public function storeAdmins(Request $request) {

        Request()->validate([

            "name" => "required|max:40",
            "email" => "required|max:40",
            "password" => "required|max:40"

        ]);

        $storeAdmins = Admin::create([

            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),

        ]);

        if ($storeAdmins) {

            return Redirect::route('admins.all')->with(['success' => "Admin Created Succesffuly"]);

        }

    }

    public function displayCategories() {

        $allCategories = Category::select()->orderBy('id', 'desc')->get();

        return view('admins.allcategories', compact('allCategories'));

    }

    public function createCategories() {

        return view('admins.createcategories');

    }

    public function storeCategories(Request $request) {

        Request()->validate([

            "name" => "required|max:40",
            "email" => "required|max:40",
            "password" => "required|max:40" 

        ]);

        $destinationPath = 'assets/img/';
        $myimage = $request->image->getClientOriginalName();
        $request->image->move(public_path($destinationPath), $myimage);

        $storeCategories = Category::create([

            "icon" => $request->icon,
            "name" => $request->name,
            "image" => $myimage,

        ]);

        if ($storeCategories) {

            return Redirect::route('categories.all')->with(['success' => "Category Created Succesffuly"]);

        }

    }

    public function editCategories($id) {

        $category = Category::find($id);

        return view('admins.editcategories', compact('category'));

    }

    public function updateCategories(Request $request, $id) {

        $category = Category::find($id);

        $category->update($request->all());

        if ($category) {

            return Redirect::route('categories.all')->with(['update' => "Category Updated Succesffully"]);

        }

    }

    public function deleteCategories($id) {

        $category = Category::find($id);

        if(File::exists(public_path('assets/img/' . $category->image))){
            File::delete(public_path('assets/img/' . $category->image));
        }else{
            //dd('File does not exists.');
        }

        $category->delete();

        if ($category) {

            return Redirect::route('categories.all')->with(['delete' => "Category Deleted Succesffully"]);

        }

    }

    public function displayProducts() {

        $allProducts = Product::select()->orderBy('id', 'desc')->get();

        return view('admins.allproducts', compact('allProducts'));

    }

    public function createProducts() {

        $allCategories = Category::all();

        return view('admins.createproducts', compact('allCategories'));

    }

    public function storeProducts(Request $request) {

        // Request()->validate([

        //     "name" => "required|max:40",
        //     "email" => "required|max:40",
        //     "password" => "required|max:40",

        // ]);

        $destinationPath = 'assets/img/';
        $myimage = $request->image->getClientOriginalName();
        $request->image->move(public_path($destinationPath), $myimage);

        $storeProducts = Product::Create([

            "price" => $request->price,
            "name" => $request->name,
            "description" => $request->description,
            "category_id" => $request->category_id,
            "exp_date" => $request->exp_date,
            "image" => $myimage,

        ]);

        if ($storeProducts) {

            return Redirect::route('products.all')->with(['success' => "Product Created Succesffully"]);

        }

    }

    public function deleteProducts($id) {

        $product = Product::find($id);

        if(File::exists(public_path('assets/img/' . $product->image))){
            File::delete(public_path('assets/img/' . $product->image));
        }else{
            //dd('File does not exists.');
        }

        $product->delete();

        if ($product) {

            return Redirect::route('products.all')->with(['delete' => "Product Deleted Succesffully"]);

        }

    }

    public function allOrders() {

        $allOrders = Order::select()->orderBy('id', 'desc')->get();


        return view('admins.allorders', compact('allOrders'));

    }

    public function editOrders($id) {

        $order = Order::find($id);

        return view('admins.editorders', compact('order'));

    }

    public function updateOrders(Request $request, $id) {

        $order = Order::find($id);

        $order->update($request->all());

        if ($order) {

            return Redirect::route('orders.all')->with(['update' => "Order Status Updated Succesffully"]);

        }

    }

}
