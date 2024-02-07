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
                                <option value="gold_coffee" selected>Gold Coffee</option>
                                <option value="arabic_coffee">Arabic Coffee</option>
                            </select>
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" oninput="calculateSellingPrice()" required>
                            <span id="quantity-error" class="input-error" style="display: none;">Only numbers allowed</span>
                            <label for="unit_cost">Unit Cost (£)</label>
                            <input type="number" id="unit_cost" name="unit_cost" oninput="calculateSellingPrice()" required>
                            <span id="cost-error" class="input-error" style="display: none;">Only numbers allowed</span>
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
                                    <th>Unit Cost (£)</th>
                                    <th>Selling Price (£)</th>
                                    <th>Sold At</th>
                                </tr>
                            </thead>
                            <tbody id="sales-table-body">
                                <!-- Sales data will be dynamically added here -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        document.getElementById('quantity').addEventListener('input', function() {
            // if (this.value.trim()) {
            //     this.classList.remove('mandatory-field');
            // }
            if (this.value.trim() && !isNaN(this.value.trim())) {
                this.classList.remove('mandatory-field');
                document.getElementById('quantity-error').style.display = 'none';
            } else {
                this.classList.add('mandatory-field');
                document.getElementById('quantity-error').style.display = 'block';
            }
        });

        document.getElementById('unit_cost').addEventListener('input', function() {
            // if (this.value.trim()) {
            //     this.classList.remove('mandatory-field');
            // }
            if (this.value.trim() && !isNaN(this.value.trim())) {
                this.classList.remove('mandatory-field');
                document.getElementById('cost-error').style.display = 'none';
            } else {
                this.classList.add('mandatory-field');
                document.getElementById('cost-error').style.display = 'block';
            }
        });

    });


    
    // var recordSaleBtn = document.getElementById('record_sale_btn');
    // recordSaleBtn.disabled = true;

     function calculateSellingPrice() {
        var quantity = parseFloat(document.getElementById('quantity').value);
        var unitCost = parseFloat(document.getElementById('unit_cost').value);
        var sellingPriceSpan = document.getElementById('selling_price');
        var recordSaleBtn = document.getElementById('record_sale_btn');
        var product = document.getElementById('product').value;

        // if (isNaN(quantity) || isNaN(unitCost)) {
        //     sellingPriceSpan.textContent = '-';
        //     // recordSaleBtn.disabled = true;
        //     // return;
        // }

        // var profitMargin = 0.25; // 25% for gold coffee
        var profitMargin = (product === 'gold_coffee') ? 0.25 : 0.15;
        var shippingCost = 10.00;
        var totalCost = quantity * unitCost;
        var sellingPrice = (totalCost / (1 - profitMargin)) + shippingCost;
        
        // sellingPriceSpan.textContent = '£' + sellingPrice.toFixed(2);
        if (!isNaN(quantity) && !isNaN(unitCost)) {
            // Calculate selling price
            // var sellingPrice = (quantity * unitCost) / (1 - (document.getElementById('product').value === 'arabic_coffee' ? 0.15 : 0.25));
            document.getElementById('selling_price').textContent = sellingPrice.toFixed(2); // Display selling price
        } else {

            document.getElementById('selling_price').textContent = ''; // Clear selling price
        }
        // Enable the "Record Sale" button
        // recordSaleBtn.disabled = false;
    
    }

    
    function recordSale(product='') {
        var is_mandate_field_error = 0;
        
        var quantityInput = document.getElementById('quantity');
        if (!quantityInput.value.trim()) {
            quantityInput.classList.add('mandatory-field');
            is_mandate_field_error =1;
        } else {
            quantityInput.classList.remove('mandatory-field');
        }

        var unitCostInput = document.getElementById('unit_cost');
        if (!unitCostInput.value.trim()) {
            unitCostInput.classList.add('mandatory-field');
            is_mandate_field_error =1;
            
        } else {
            unitCostInput.classList.remove('mandatory-field');
        }
        
        if(is_mandate_field_error){
            return false;
        }
        var quantity = parseFloat(quantityInput.value);
        var unitCost = parseFloat(unitCostInput.value);
        var sellingPrice = parseFloat(document.getElementById('selling_price').textContent.replace('£', ''));
        
        var data = {
            quantity: quantity,
            unit_cost: unitCost,
            selling_price: sellingPrice,
            product_name : product
        };
        console.log('data',data);
        $.ajax({
            url: '/recordSale', 
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            success: function(response) {
                alert('Sale recorded successfully!');
                fetchSalesData();
            },

            error: function(xhr, status, error) {
                console.error('Error recording sale:', error);
            }
        });
    }

    function fetchSalesData() {
        $.get('/getSalesData')
            .done(function(salesData) {
            // Handle successful response
            console.log('Sales data:', salesData);
            updateTable(salesData);
          
        })
        .fail(function(xhr, status, error) {
    
        });
    }
    
    fetchSalesData();

    function updateTable(salesData) {
        var tableBody = document.getElementById('sales-table-body');
        tableBody.innerHTML = ''; // Clear existing rows
        if (salesData.length === 0) {
         
            tableBody.innerHTML = '<tr><td colspan="5">No data found</td></tr>';
        }else{
            salesData.forEach(function(sale) {
                var productName = sale.product_name.replace(/_/g, ' ').replace(/\b\w/g, function(char) {
                    return char.toUpperCase();
                });
                var row = document.createElement('tr');
                row.innerHTML = '<td>' + productName + '</td>' +
                                '<td>' + sale.quantity + '</td>' +
                                '<td>' + sale.unit_cost + '</td>' +
                                '<td>' + sale.selling_price + '</td>' +
                                '<td>' + sale.sold_at + '</td>';
                tableBody.appendChild(row);
            });

        }
    }
    function resetQuantityAndCost() {
        document.getElementById('quantity').value = '';
        document.getElementById('unit_cost').value = '';
        document.getElementById('selling_price').textContent = ''; 
    }

    function checkInput(inputId, errorMessageId) {
        var input = document.getElementById(inputId);
        var errorMessage = document.getElementById(errorMessageId);
        var inputValue = input.value;

        if (inputValue !== '' && !/^\d+$/.test(inputValue)) {
            errorMessage.style.display = 'inline';
        } else {
            errorMessage.style.display = 'none';
        }
    }


</script>