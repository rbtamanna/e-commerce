<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;



class ProductExport implements FromCollection, WithHeadings {




    public function headings(): array {




        // according to users table




        return [

            "ID",

            "Name",

            "Price",

            "Quantity",

            "Category",

        ];

    }




    public function collection(){

        $productData = \Illuminate\Support\Facades\DB::table('products as p')
            ->whereNull('p.deleted_at')
            ->leftJoin('product_categories as pc', 'pc.product_id', '=', 'p.id')
            ->leftJoin('categories as c', 'c.id', '=', 'pc.category_id')
            ->select('p.id', 'p.name', 'p.price', 'p.quantity', 'c.name as category_name')
            ->get();


        return collect($productData);

    }

}
