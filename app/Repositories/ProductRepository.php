<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    private $id, $name, $price, $quantity, $category_id, $created_at, $updated_at, $deleted_at;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }

    public function getCategories()
    {
        return Category::get();
    }

    public function getProduct()
    {
        return Product::findOrFail($this->id)->leftJoin('product_categories as pc', 'pc.product_id','=','products.id')
            ->select('products.*', 'pc.category_id')->first();
    }

    public function getTableData()
    {
        return DB::table('products as p')
            ->whereNull('p.deleted_at')
            ->leftJoin('product_categories as pc', 'pc.product_id', '=', 'p.id')
            ->leftJoin('categories as c', 'c.id', '=', 'pc.category_id')
            ->select('p.*', 'c.name as category_name', 'c.id as category_id')
            ->get();
    }


    public function create()
    {
        DB::beginTransaction();
        try {
            $product = new Product();
            $product->name = $this->name;
            $product->price = $this->price;
            $product->quantity = $this->quantity;
            $product->created_at = $this->created_at;
            $product->save();
            $product_categories = new ProductCategory();
            $product_categories->product_id = $product->id;
            $product_categories->category_id = $this->category_id;
            $product_categories->created_at = $this->created_at;
            $product_categories->save();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function getUserEmails()
    {
        return DB::table('users')
            ->whereNull('deleted_at')
            ->pluck('email')
            ->toArray();
    }

    public function update()
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($this->id);
            $product->name = $this->name;
            $product->price = $this->price;
            $product->quantity = $this->quantity;
            $product->updated_at = $this->updated_at;
            $product->save();
            $product_categories =ProductCategory::where('product_id', $this->id)->firstOrFail();
            $product_categories->category_id = $this->category_id;
            $product_categories->updated_at = $this->updated_at;
            $product_categories->save();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function getCategory()
    {
        return Category::findOrFail($this->id);
    }

    public function getAllProducts()
    {
        return DB::table('products as p')
            ->whereNull('p.deleted_at')
            ->leftJoin('product_categories as pc', 'pc.product_id', '=', 'p.id')
            ->leftJoin('categories as c', 'c.id', '=', 'pc.category_id')
            ->select('p.*', 'c.name as category_name', 'c.id as category_id')
            ->get();
    }

    public function import($file)
    {

        DB::beginTransaction();
        try {
            $f= file($file);
            $row = explode(',', $f[0]);

            if(count($row)!=4 ||$row[0]!="name"|| $row[1]!="price"|| $row[2]!="quantity" || $row[3]!="category\r\n")
                return false;
            array_splice($f, 0, 1);
            foreach($f as $line) {
                $l = explode(',', $line);
                $category = DB::table('categories')->where('name', $l[3])->value('id');
                if (!$category) {
                    $category = DB::table('categories')
                        ->insertGetId([
                            'name' => $l[3],
                            'created_at' => $this->created_at,
                        ]);
                }
                $product_id = DB::table('products')->insertGetId([
                    'name' => $l[0],
                    'price' => $l[1],
                    'quantity' => $l[2]
                ]);
                DB::table('product_categories')
                    ->insert([
                        'product_id' => $product_id,
                        'category_id' => $category,
                    ]);
            }
                DB::commit();
                return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function delete()
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($this->id);
            $product_categories =ProductCategory::where('product_id', $this->id)->firstOrFail();
            $product_categories->delete() ;
            $product->delete();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

}
