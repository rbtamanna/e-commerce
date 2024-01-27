<?php

namespace App\Listeners;

use App\Events\ProductPurchased;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductQuantity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductPurchased $event): void
    {
        $product_id = $event->product_id;
        $product = Product::find($product_id);
        if($product->quantity>0)
            $product->quantity = $product->quantity-1;
        $product->save();
    }
}
