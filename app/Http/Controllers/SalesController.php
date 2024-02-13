<?php
namespace App\Http\Controllers;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function recordSale(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
            'product_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }
        $arrSalesData = $request->all();

        $arrSalesData['product_id'] = empty($arrSalesData['product_id']) ? 1 : $arrSalesData['product_id'];

        $arrSalesData['sold_at'] = date('Y-m-d H:i:s');
        // dd($arrSalesData);
        $salesRecord = Sale::create($arrSalesData);
        return response()->json(['message' => 'Sale recorded successfully'], 200);
    }
    
    public function getSalesData()
    {
        $salesData = Sale::leftJoin('products', 'sales.product_id', '=', 'products.id')
         ->select('sales.*', 'products.product_name')->get();
        return response()->json($salesData);
    }
    
    public function getProductsData()
    {
        $products = Product::select('id', 'product_name')->get();
        return response()->json($products);    
    }
    
}
