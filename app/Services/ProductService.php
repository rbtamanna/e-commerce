<?php

namespace App\Services;

use App\Jobs\NewProductJob;
use App\Repositories\ProductRepository;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getCategories()
    {
        return $this->productRepository->getCategories();
    }

    public function getProduct($id)
    {
        return $this->productRepository->setId($id)->getProduct();
    }

    public function create($data)
    {
        $product = $this->productRepository->setName($data['name'])
            ->setPrice($data['price'])
            ->setQuantity($data['quantity'])
            ->setCategoryId($data['category_id'])
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->create();
        if($product)
        {
            $to = $this->productRepository->getUserEmails();
            $data =[
                'to' => $to,
                'user_email' => auth()->user()->email,
                'user_name' => auth()->user()->name,
                'subject' => "New Product : ".$data['name'],
            ];
            NewProductJob::dispatch($data);
            return true;
        }
        return false;
    }

    public function update($data)
    {
        return $this->productRepository->setId($data['id'])
            ->setName($data['name'])
            ->setPrice($data['price'])
            ->setQuantity($data['quantity'])
            ->setCategoryId($data['category_id'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
    }

    public function delete($id)
    {
        return $this->productRepository->setId($id)->delete();
    }

    public  function import($data)
    {
        return $this->productRepository->setCreatedAt(date('Y-m-d H:i:s'))
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->import($data['file']);
    }

    public function getAllProducts()
    {
        return $this->productRepository->getAllProducts();
    }

    public function fetchData()
    {
        $result = $this->productRepository->getTableData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $price = $row->price;
                $quantity = $row->quantity;
                $category = $row->category_name;
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">";

                $edit_url = url('product/'.$id.'/edit');
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $delete_url = url('product/'.$id.'/destroy');
                $delete_btn = "<a class=\"dropdown-item\" href=\"$delete_url\">Destroy</a>";
                $action_btn .= " $edit_btn $delete_btn ";
                $action_btn .= "</div>
                                    </div>
                                </div>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $name);
                array_push($temp, $price);
                array_push($temp, $quantity);
                array_push($temp, $category);
                array_push($temp, $action_btn);
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                "sEcho": 1,
                "iTotalRecords": "0",
                "iTotalDisplayRecords": "0",
                "aaData": []
            }';
        }
    }
}
