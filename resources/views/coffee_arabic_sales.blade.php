<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New ☕️ Sales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- @todo -->
                    <form>
                        <div class="input-row">
                            <label for="product">Product</label>
                            <select id="product" name="product" onchange="resetQuantityAndCost()">
                                
                            </select>

                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" oninput="calculateSellingPrice()" required>
                            <span id="quantity-error" class="input-error"></span>
                            <label for="unit_cost">Unit Cost (£)</label>
                            <input type="number" id="unit_cost" name="unit_cost" oninput="calculateSellingPrice()" required>
                            <span id="cost-error" class="input-error"></span>
                            
                            <label for="selling_price">Selling Price</label>
                            <span id="selling_price"></span>
                            <div class="button-container">
                                <button id="record_sale_btn" type="button" onclick="recordSale(document.getElementById('product').value)">Record Sale</button>
                            </div>
                        </div> 
                    </form>

                    <div id="sales-table-container">
                        <table id="sales-table" border="1">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Cost</th>
                                    <th>Selling Price</th>
                                    <th>Sold At</th>
                                </tr>
                            </thead>
                            <tbody id="sales-table-body">
                              
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="{{ asset('js/common.js') }}"></script>