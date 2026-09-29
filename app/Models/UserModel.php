<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel
 *
 * Handles CRUD for the `users` table.
 * The `avatar` column stores only a filename (no path).
 * Validation rules vary between insert and update (uniqueness check).
 */
class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'username',
        'full_name',
        'email',
        'avatar',
        'created_at',
    ];

    // ── Default validation rules (used on insert) ──────────────────────────
    protected $validationRules = [
        'username'  => 'required|min_length[3]|max_length[50]|alpha_dash|is_unique[users.username]',
        'full_name' => 'required|min_length[2]|max_length[100]',
        'email'     => 'permit_empty|valid_email|max_length[100]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'   => 'Username is required.',
            'min_length' => 'Username must be at least 3 characters.',
            'max_length' => 'Username may not exceed 50 characters.',
            'alpha_dash' => 'Username may only contain letters, numbers, underscores, and dashes.',
            'is_unique'  => 'That username is already taken. Please choose another.',
        ],
        'full_name' => [
            'required'   => 'Full name is required.',
            'min_length' => 'Full name must be at least 2 characters.',
            'max_length' => 'Full name may not exceed 100 characters.',
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address.',
            'max_length'  => 'Email may not exceed 100 characters.',
        ],
    ];

    protected $skipValidation = false;

    // ── Helper: demo user ──────────────────────────────────────────────────
    public function getDemoUser(): ?array
    {
        return $this->where('username', 'bverdera')->first();
    }
}