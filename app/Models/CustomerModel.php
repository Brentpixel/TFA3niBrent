<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CustomerModel
 *
 * Handles CRUD for the `customers` table.
 * Validation rules are declared here so controllers stay thin.
 */
class CustomerModel extends Model
{
    protected $table          = 'customers';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = false;

    protected $allowedFields  = [
        'full_name',
        'email',
        'phone',
        'created_at',
    ];

    // ── Validation rules ───────────────────────────────────────────────────
    protected $validationRules = [
        'full_name' => 'required|min_length[2]|max_length[100]',
        'email'     => 'required|valid_email|max_length[100]',
        'phone'     => 'permit_empty|max_length[20]',
    ];

    protected $validationMessages = [
        'full_name' => [
            'required'   => 'Full name is required.',
            'min_length' => 'Full name must be at least 2 characters.',
            'max_length' => 'Full name may not exceed 100 characters.',
        ],
        'email' => [
            'required'    => 'Email address is required.',
            'valid_email' => 'Please enter a valid email address.',
            'max_length'  => 'Email may not exceed 100 characters.',
        ],
        'phone' => [
            'max_length' => 'Phone number may not exceed 20 characters.',
        ],
    ];

    protected $skipValidation = false;
}