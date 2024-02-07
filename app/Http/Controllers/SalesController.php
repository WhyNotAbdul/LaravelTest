<?php
namespace App\Http\Controllers;
use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function recordSale(Request $request)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);
        $arrSalesData = $request->all();

        $arrSalesData['product_name'] = empty($arrSalesData['product_name']) ? 'gold_coffee' : $arrSalesData['product_name'];

        $arrSalesData['sold_at'] = date('Y-m-d H:i:s');

        $salesRecord = Sale::create($arrSalesData);
        return response()->json(['message' => 'Sale recorded successfully'], 200);
    }
    public function getSalesData()
    {
        $salesData = Sale::all();
        return response()->json($salesData);
    }
}
