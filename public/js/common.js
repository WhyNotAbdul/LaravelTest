var currentPath = window.location.pathname;


document.addEventListener('DOMContentLoaded', function() {
    
    document.getElementById('quantity').addEventListener('input', function() {
        var errorContainer = document.getElementById('quantity-error');        
        // if (this.value.trim()) {
        //     this.classList.remove('mandatory-field');
        // }
        if (this.value.trim() && !isNaN(this.value.trim())) {
            this.classList.remove('mandatory-field');
            errorContainer.textContent = '';
            // document.getElementById('quantity-error').style.display = 'none';
        } else {
            this.classList.add('mandatory-field');
            // document.getElementById('quantity-error').style.display = 'block';
        }
    });

    document.getElementById('unit_cost').addEventListener('input', function() {
        // if (this.value.trim()) {
        //     this.classList.remove('mandatory-field');
        // }
        var errorContainer = document.getElementById('cost-error');        
        if (this.value.trim() && !isNaN(this.value.trim())) {
            this.classList.remove('mandatory-field');
            errorContainer.textContent = '';
            // document.getElementById('cost-error').style.display = 'none';
        } else {
            this.classList.add('mandatory-field');
            // document.getElementById('cost-error').style.display = 'block';
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
    // var product = document.getElementById('product').value;
    var productElement = document.getElementById('product');
    var product = productElement ? productElement.value : 1;

    console.log('product',product);
    // if (isNaN(quantity) || isNaN(unitCost)) {
    //     sellingPriceSpan.textContent = '-';
    //     // recordSaleBtn.disabled = true;
    //     // return;
    // }

    // var profitMargin = 0.25; // 25% for gold coffee
    var profitMargin = (product == 1) ? 0.25 : 0.15;
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
        var errorContainer = document.getElementById('quantity-error');        
        errorContainer.textContent = 'Please enter a valid quantity.'; // Display error message
    } else {
        quantityInput.classList.remove('mandatory-field');
    }

    var unitCostInput = document.getElementById('unit_cost');
    if (!unitCostInput.value.trim()) {
        unitCostInput.classList.add('mandatory-field');
        is_mandate_field_error =1;
        var errorContainer = document.getElementById('cost-error');        
        errorContainer.textContent = 'Please enter a valid cost.'; // Display error message
        
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
        product_id : product ? product : 1
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
            resetQuantityAndCost();
        },

        error: function(xhr, status, error) {
            console.error('Error recording sale:', error);
        }
    });
}

function fetchSalesData() {
    $.get('/getSalesData')
        .done(function(salesData) {
        console.log('Sales data:', salesData);
        updateTable(salesData);
      
    })
    .fail(function(xhr, status, error) {

    });
}
function fetchProductsData() {
    $.get('/getProductsData')
        .done(function(productsData) {
        console.log('productsData:', productsData);          
        if(productsData.length > 0){
            var select = document.getElementById('product');
            if(select){
                select.innerHTML = '';
                productsData.forEach(function(product) {
                    var productName = product.product_name.replace(/_/g, ' ').replace(/\b\w/g, function(char) {
                        return char.toUpperCase();
                    });
                    var option = document.createElement('option');
                    option.value = product.id; 
                    option.text = productName;
                    select.appendChild(option);
                });
            }
        }
    })
    .fail(function(xhr, status, error) {

    });
}

fetchSalesData();
fetchProductsData();
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
            var productName = (currentPath === "/arabic_sales") ? '<td>' + productName + '</td>' : '';
            var soldAt = (currentPath === "/arabic_sales") ? '<td>' + sale.sold_at + '</td>' : '';
            var row = document.createElement('tr');
             row.innerHTML = productName +
                '<td>' + sale.quantity + '</td>' +
                '<td>' + '£' + sale.unit_cost + '</td>' +
                '<td>' + '£' + sale.selling_price + '</td>' +
                soldAt;
            tableBody.appendChild(row);
        });

    }
}
function resetQuantityAndCost() {
    document.getElementById('quantity').value = '';
    document.getElementById('unit_cost').value = '';
    document.getElementById('selling_price').textContent = ''; 
}