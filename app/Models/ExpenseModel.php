<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table = 'expenses';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'category_id',
        'description',
        'amount',
        'currency',
        'converted_amount',
        'expense_date'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'category_id' => 'required|integer',
        'description' => 'required|min_length[3]',
        'amount' => 'required|decimal|greater_than[0]',
        'currency' => 'required|in_list[PHP,USD,EUR]',
        'expense_date' => 'required|valid_date',
    ];
    
    protected $validationMessages = [
        'category_id' => [
            'required' => 'Category is required',
        ],
        'description' => [
            'required' => 'Description is required',
            'min_length' => 'Description must be at least 3 characters',
        ],
        'amount' => [
            'required' => 'Amount is required',
            'greater_than' => 'Amount must be greater than 0',
        ],
        'currency' => [
            'required' => 'Currency is required',
        ],
        'expense_date' => [
            'required' => 'Expense date is required',
            'valid_date' => 'Please enter a valid date',
        ],
    ];
    
    public function getExpensesByUser($userId)
    {
        return $this->select('expenses.*, categories.name as category_name')
                    ->join('categories', 'categories.id = expenses.category_id')
                    ->where('user_id', $userId)
                    ->orderBy('expense_date', 'DESC')
                    ->findAll();
    }
    
    public function getAllExpenses()
    {
        return $this->select('expenses.*, categories.name as category_name, users.username')
                    ->join('categories', 'categories.id = expenses.category_id')
                    ->join('users', 'users.id = expenses.user_id')
                    ->orderBy('expense_date', 'DESC')
                    ->findAll();
    }
    
    public function getTotalExpensesByUser($userId)
    {
        $result = $this->selectSum('converted_amount')
                       ->where('user_id', $userId)
                       ->first();
        
        return $result['converted_amount'] ?? 0;
    }
    
    public function getTotalExpensesByCategory($userId)
    {
        return $this->select('categories.name, SUM(expenses.converted_amount) as total')
                    ->join('categories', 'categories.id = expenses.category_id')
                    ->where('user_id', $userId)
                    ->groupBy('expenses.category_id')
                    ->orderBy('total', 'DESC')
                    ->findAll();
    }
    
    public function getAllTotalExpenses()
    {
        $result = $this->selectSum('converted_amount')->first();
        return $result['converted_amount'] ?? 0;
    }
    
    public function getAllTotalExpensesByCategory()
    {
        return $this->select('categories.name, SUM(expenses.converted_amount) as total')
                    ->join('categories', 'categories.id = expenses.category_id')
                    ->groupBy('expenses.category_id')
                    ->orderBy('total', 'DESC')
                    ->findAll();
    }
}
