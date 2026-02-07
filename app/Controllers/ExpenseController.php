<?php

namespace App\Controllers;

use App\Models\ExpenseModel;
use App\Models\CategoryModel;
use App\Libraries\CurrencyService;

class ExpenseController extends BaseController
{
    protected $expenseModel;
    protected $categoryModel;
    protected $currencyService;
    
    public function __construct()
    {
        $this->expenseModel = new ExpenseModel();
        $this->categoryModel = new CategoryModel();
        $this->currencyService = new CurrencyService();
    }
    
    public function index()
    {
        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        if ($role === 'admin') {
            $expenses = $this->expenseModel->getAllExpenses();
        } else {
            $expenses = $this->expenseModel->getExpensesByUser($userId);
        }
        
        $data = [
            'title' => 'Manage Expenses',
            'expenses' => $expenses,
        ];
        
        return view('expenses/index', $data);
    }
    
    public function create()
    {
        $categories = $this->categoryModel->getAllCategories();
        $currencies = $this->currencyService->getSupportedCurrencies();
        
        $data = [
            'title' => 'Add New Expense',
            'categories' => $categories,
            'currencies' => $currencies,
        ];
        
        return view('expenses/create', $data);
    }
    
    public function store()
    {
        $rules = [
            'category_id' => 'required|integer',
            'description' => 'required|min_length[3]',
            'amount' => 'required|decimal|greater_than[0]',
            'currency' => 'required|in_list[PHP,USD,EUR]',
            'expense_date' => 'required|valid_date',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $amount = $this->request->getPost('amount');
        $currency = $this->request->getPost('currency');
        
        // Convert to PHP currency
        $convertedAmount = $this->currencyService->convert($amount, $currency, 'PHP');
        
        $data = [
            'user_id' => session()->get('user_id'),
            'category_id' => $this->request->getPost('category_id'),
            'description' => $this->request->getPost('description'),
            'amount' => $amount,
            'currency' => $currency,
            'converted_amount' => $convertedAmount,
            'expense_date' => $this->request->getPost('expense_date'),
        ];
        
        if ($this->expenseModel->insert($data)) {
            return redirect()->to('/expenses')->with('success', 'Expense added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add expense');
        }
    }
    
    public function edit($id)
    {
        $expense = $this->expenseModel->find($id);
        
        if (!$expense) {
            return redirect()->to('/expenses')->with('error', 'Expense not found');
        }
        
        // Check if user owns this expense (unless admin)
        if (session()->get('role') !== 'admin' && $expense['user_id'] != session()->get('user_id')) {
            return redirect()->to('/expenses')->with('error', 'Unauthorized access');
        }
        
        $categories = $this->categoryModel->getAllCategories();
        $currencies = $this->currencyService->getSupportedCurrencies();
        
        $data = [
            'title' => 'Edit Expense',
            'expense' => $expense,
            'categories' => $categories,
            'currencies' => $currencies,
        ];
        
        return view('expenses/edit', $data);
    }
    
    public function update($id)
    {
        $expense = $this->expenseModel->find($id);
        
        if (!$expense) {
            return redirect()->to('/expenses')->with('error', 'Expense not found');
        }
        
        // Check if user owns this expense (unless admin)
        if (session()->get('role') !== 'admin' && $expense['user_id'] != session()->get('user_id')) {
            return redirect()->to('/expenses')->with('error', 'Unauthorized access');
        }
        
        $rules = [
            'category_id' => 'required|integer',
            'description' => 'required|min_length[3]',
            'amount' => 'required|decimal|greater_than[0]',
            'currency' => 'required|in_list[PHP,USD,EUR]',
            'expense_date' => 'required|valid_date',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $amount = $this->request->getPost('amount');
        $currency = $this->request->getPost('currency');
        
        // Convert to PHP currency
        $convertedAmount = $this->currencyService->convert($amount, $currency, 'PHP');
        
        $data = [
            'category_id' => $this->request->getPost('category_id'),
            'description' => $this->request->getPost('description'),
            'amount' => $amount,
            'currency' => $currency,
            'converted_amount' => $convertedAmount,
            'expense_date' => $this->request->getPost('expense_date'),
        ];
        
        if ($this->expenseModel->update($id, $data)) {
            return redirect()->to('/expenses')->with('success', 'Expense updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update expense');
        }
    }
    
    public function delete($id)
    {
        $expense = $this->expenseModel->find($id);
        
        if (!$expense) {
            return redirect()->to('/expenses')->with('error', 'Expense not found');
        }
        
        // Check if user owns this expense (unless admin)
        if (session()->get('role') !== 'admin' && $expense['user_id'] != session()->get('user_id')) {
            return redirect()->to('/expenses')->with('error', 'Unauthorized access');
        }
        
        if ($this->expenseModel->delete($id)) {
            return redirect()->to('/expenses')->with('success', 'Expense deleted successfully');
        } else {
            return redirect()->to('/expenses')->with('error', 'Failed to delete expense');
        }
    }
}
