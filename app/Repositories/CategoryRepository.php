<?php

namespace App\Repositories;


use App\Models\Category;

class CategoryRepository
{
    private $id, $name,   $created_at, $updated_at, $deleted_at;

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

    public function getTableData()
    {
        return Category::get();
    }

    public function isNameExists()
    {
        return Category::withTrashed()->where('name', $this->name)->exists() ;
    }

    public function isNameUnique()
    {
        return Category::withTrashed()->where('name',$this->name)->where('id', '!=', $this->id)->first() ;
    }

    public function create()
    {
        $category = new Category();
        $category->name = $this->name;
        $category->created_at = $this->created_at;
        return $category->save();
    }

    public function update()
    {
        $category = Category::findOrFail($this->id);
        $category->name = $this->name;
        $category->updated_at = $this->updated_at;
        return $category->save();
    }

    public function getCategory()
    {
        return Category::findOrFail($this->id);
    }

    public function delete()
    {
        $category = Category::findOrFail($this->id);
        return $category->delete();
    }

}
