<?php

namespace App\Controllers;

use App\Models\ExpenseModel;
use App\Models\CategoryModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $expenseModel = new ExpenseModel();
        $categoryModel = new CategoryModel();
        
        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        $data = [
            'title' => 'Dashboard',
        ];
        
        if ($role === 'admin') {
            // Admin sees all expenses
            $data['total_expenses'] = $expenseModel->getAllTotalExpenses();
            $data['expenses_by_category'] = $expenseModel->getAllTotalExpensesByCategory();
            $data['recent_expenses'] = $expenseModel->getAllExpenses();
        } else {
            // Regular user sees only their expenses
            $data['total_expenses'] = $expenseModel->getTotalExpensesByUser($userId);
            $data['expenses_by_category'] = $expenseModel->getTotalExpensesByCategory($userId);
            $data['recent_expenses'] = $expenseModel->getExpensesByUser($userId);
        }
        
        // Limit recent expenses to 10
        $data['recent_expenses'] = array_slice($data['recent_expenses'], 0, 10);
        
        return view('dashboard/index', $data);
    }
}
