<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class Customers extends BaseController
{
    public function index()
    {
        $customerModel = new CustomerModel();

        $data = [
            'title' => 'Customer Accounts',
            'customers' => $customerModel->findAll()
        ];

        return view('customers/index', $data);
    }
}