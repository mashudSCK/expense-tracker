<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;
    
    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }
    
    public function index()
    {
        // Only admin can access categories
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }
        
        $categories = $this->categoryModel->getAllCategories();
        
        $data = [
            'title' => 'Manage Categories',
            'categories' => $categories,
        ];
        
        return view('categories/index', $data);
    }
    
    public function create()
    {
        // Only admin can access categories
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }
        
        $data = [
            'title' => 'Add New Category',
        ];
        
        return view('categories/create', $data);
    }
    
    public function store()
    {
        // Only admin can access categories
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
        ];
        
        if ($this->categoryModel->insert($data)) {
            return redirect()->to('/categories')->with('success', 'Category added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add category');
        }
    }
    
    public function edit($id)
    {
        // Only admin can access categories
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }
        
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/categories')->with('error', 'Category not found');
        }
        
        $data = [
            'title' => 'Edit Category',
            'category' => $category,
        ];
        
        return view('categories/edit', $data);
    }
    
    public function update($id)
    {
        // Only admin can access categories
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }
        
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/categories')->with('error', 'Category not found');
        }
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
        ];
        
        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('/categories')->with('success', 'Category updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update category');
        }
    }
    
    public function delete($id)
    {
        // Only admin can access categories
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }
        
        $category = $this->categoryModel->find($id);
        
        if (!$category) {
            return redirect()->to('/categories')->with('error', 'Category not found');
        }
        
        if ($this->categoryModel->delete($id)) {
            return redirect()->to('/categories')->with('success', 'Category deleted successfully');
        } else {
            return redirect()->to('/categories')->with('error', 'Failed to delete category. It may be in use.');
        }
    }
}
