<!-- resources/views/create_shopify_product.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Shopify Product</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Create Product</h1>

        <form method="POST" action="/create-shopify-product" enctype="multipart/form-data" class="mt-4">
            @csrf

            <div class="mb-3">
                <label for="productName" class="form-label">Product Name:</label>
                <input type="text" class="form-control" name="productName" id="productName" required value="{{ old('productName') }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" name="description" id="description" rows="3" required>{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image:</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" class="form-control" name="price" id="price" step="0.01" min="0" required value="{{ old('price') }}">
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" class="form-control" name="quantity" id="quantity" min="0" required value="{{ old('quantity') }}">
            </div>


             @if(session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
             @endif

            @if(session('error'))
               <div class="alert alert-danger" role="alert"> {{ session('error') }}</div>
            @endif


            <button type="submit" class="btn btn-primary">Create Product</button>
        </form>

    </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT8jSL05g6Hl6Wf4MdxueMV/kyq4J8WOTpZlTu2Yuhz7rxXz" crossorigin="anonymous"></script>
</body>
</html>