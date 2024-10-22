<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShopifyProductController extends Controller
{
    public function index()
    {
        return view('create_shopify_product'); // Create this view file
    }

    // ... other code
    
    public function store(Request $request)
    {
        // ... (validation and other logic)
        $shopifyDomain = config('shopify.domain'); // Your Shopify store domain
        $accessToken = config('shopify.access_token'); // Your Shopify private app access token
    
        $validated = $request->validate([
            'productName' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image', // Or 'required|url' if using image URL
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);
        $productData = [ // Prepare the product data (without the image initially)
            "product" => [
                "title" => $validated['productName'],
                "body_html" => $validated['description'],
                "variants" => [ // Price and quantity go in the variants array
                    [
                        "price" => $validated['price'],
                        "inventory_quantity" => $validated['quantity'],
                    ],
                ],
            ]
        ];
        // dd($shopifyDomain);
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
            'Content-Type' => 'application/json',
        ])->post("https://{$shopifyDomain}/admin/api/2024-01/products.json", $productData);
    
        if ($response->successful()) {
            // dd($response);
            $product = $response->json()['product'];
            $productId = $product['id']; // Get the newly created product ID!!!
    
            if ($request->hasFile('image')) {
                $imageUrl = $this->uploadImage($request->file('image'), $shopifyDomain, $accessToken, $productId); // Pass $productId to uploadImage
                // Update the product with the image URL
                $updateResponse = Http::withHeaders([
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/json',
                ])->put("https://{$shopifyDomain}/admin/api/2024-01/products/{$productId}.json", [
                    "product" => ["images" => [["src" => $imageUrl]]]
                ]);
    
                if (!$updateResponse->successful()) {
                    // Handle image update error.  Consider deleting the product if image upload fails.
                    return back()->with('error', 'Product created, but image upload failed: ' . $updateResponse->body());
                }
            }
    
    
    
            return redirect()->back()->with('success', 'Product created successfully!'); // Redirect after successful image upload
        } else {
            return redirect()->back()->with('error', 'Error creating product: ' . $response->body());
        }
    }
    
    
    private function uploadImage($imageFile, $shopifyDomain, $accessToken, $productId)
    {
        $fileResponse = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
            'Content-Type' => 'application/json',
        ])->post("https://{$shopifyDomain}/admin/api/2023-10/products/{$productId}/images.json", [
            'image'=>[
                'attachment' => base64_encode(file_get_contents($imageFile))
            ]
        ]);
    
        if ($fileResponse->successful()) {
            $image = $fileResponse->json()['image'];
            return $image['src'];
        } else {
            throw new \Exception("Error uploading image: " . $fileResponse->body());
        }
    }
    
    
    
    

}