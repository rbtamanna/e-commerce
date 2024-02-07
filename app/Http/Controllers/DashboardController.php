<?php

namespace App\Http\Controllers;

use App\Events\ProductPurchased;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{

    private  $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        View::share('main_menu', 'Dashboard');
        View::share('sub_menu', 'Dashboard');
    }
    public function index(Request $request)
    {
        $products = $this->dashboardService->getAllProducts();
        $url = $request->fullUrl();
        $shareComponent = \Share::page(
            $url,
            'Checkout My ecommerce website'
        )
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();
        return \view('backend.pages.dashboard', compact('products','shareComponent'));
    }

    public function purchase($id)
    {
        event(new ProductPurchased($id));
        return redirect('/');
    }

}
