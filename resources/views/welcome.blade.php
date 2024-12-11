<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    {{-- link of the instructed framework styling to use twitter bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>


<div class="container mt-5 antialiased">
    <h1>Product Form</h1>
    <form id="productForm">
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity in Stock</label>
            <input type="number" class="form-control" id="quantity" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price per Item</label>
            <input type="number" class="form-control" id="price" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <hr>

    <h2>Submitted Products</h2>
    <table class="table table-bordered" id="productTable">
        <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity in Stock</th>
            <th>Price per Item</th>
            <th>Date Submitted</th>
            <th>Total Value</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <!-- where data will be display using javascript -->
        </tbody>
    </table>

    <h4>Total Value: <span id="totalValue">0</span></h4>
</div>

{{-- script linkk for the bootstrap and axios data handling --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>


<script>
    $(document).ready(function () {

        function fetchProducts() {
            axios.get('/products').then(function (response) {
                const products = response.data;
                let totalValue = 0;
                const tableBody = $('#productTable tbody');

                tableBody.empty();

                products.forEach(function (product, index) {
                    totalValue += product.total_value;

                    const row = `
                        <tr>
                            <td>${product.product_name}</td>
                            <td>${product.quantity}</td>
                            <td>${product.price}</td>
                            <td>${new Date(product.submitted_at).toLocaleString()}</td>
                            <td>${product.total_value}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${index}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${index}">Delete</button>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });

                $('#totalValue').text(totalValue);
            }).catch(function (error) {
                console.log(error);
            });
        }

        fetchProducts();

        // Handle form submission
        $('#productForm').submit(function (e) {
            e.preventDefault();

            const productName = $('#productName').val();
            const quantity = $('#quantity').val();
            const price = $('#price').val();
            const totalValue = quantity * price;

            const index = $('#productForm button[type="submit"]').data('id');

            if (index === undefined) {
                // Create new product
                axios.post('/products', {
                    productName,
                    quantity,
                    price,
                    totalValue,
                }).then(function () {
                    fetchProducts();
                    $('#productForm')[0].reset();
                }).catch(function (error) {
                    console.log(error);
                });
            } else {
                // Update existing product
                axios.put(`/products/${index}`, {
                    productName,
                    quantity,
                    price,
                    totalValue,
                }).then(function () {
                    fetchProducts();
                    $('#productForm')[0].reset();
                    $('#productForm button[type="submit"]').text('Submit').removeData('id');
                }).catch(function (error) {
                    console.log(error);
                });
            }
        });


    });
</script>


</html>
