<?php

namespace App\Controllers;

use App\Models\CustomerModel;

/**
 * Customers Controller
 *
 * Routes (defined in app/Config/Routes.php):
 *   GET  /customers          → index()   — listing
 *   GET  /customers/new      → newForm() — blank create form
 *   POST /customers/new      → create()  — process create
 *   GET  /customers/(:num)/edit → edit() — pre-filled edit form
 *   POST /customers/(:num)/edit → update()— process update
 */
class Customers extends BaseController
{
    // ── Listing ────────────────────────────────────────────────────────────
    public function index(): string
    {
        $model = new CustomerModel();

        return view('customers/index', [
            'pageTitle' => 'Customer Accounts',
            'title'     => 'Customer Accounts',
            'customers' => $model->orderBy('id', 'ASC')->findAll(),
            'flash'     => session()->getFlashdata('success'),
        ]);
    }

    // ── New customer form (GET) ────────────────────────────────────────────
    public function newForm(): string
    {
        return view('customers/form', [
            'pageTitle'  => 'New Customer',
            'formAction' => base_url('/customers/new'),
            'customer'   => null,
            'errors'     => session()->getFlashdata('errors') ?? [],
            'old'        => session()->getFlashdata('old')    ?? [],
        ]);
    }

    // ── Create (POST) ──────────────────────────────────────────────────────
    public function create()
    {
        $model = new CustomerModel();

        $data = [
            'full_name'  => trim($this->request->getPost('full_name')),
            'email'      => trim($this->request->getPost('email')),
            'phone'      => trim($this->request->getPost('phone')),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if (! $model->insert($data)) {
            // Re-display form with errors and entered values preserved
            session()->setFlashdata('errors', $model->errors());
            session()->setFlashdata('old', [
                'full_name' => $data['full_name'],
                'email'     => $data['email'],
                'phone'     => $data['phone'],
            ]);
            return redirect()->to(base_url('/customers/new'));
        }

        session()->setFlashdata('success', 'Customer "' . esc($data['full_name']) . '" was added successfully.');
        return redirect()->to(base_url('/customers'));
    }

    // ── Edit form (GET) ────────────────────────────────────────────────────
    public function edit(int $id): string
    {
        $model    = new CustomerModel();
        $customer = $model->find($id);

        if (! $customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                "Customer #{$id} not found."
            );
        }

        return view('customers/form', [
            'pageTitle'  => 'Edit Customer',
            'formAction' => base_url("/customers/{$id}/edit"),
            'customer'   => $customer,
            'errors'     => session()->getFlashdata('errors') ?? [],
            'old'        => session()->getFlashdata('old')    ?? [],
        ]);
    }

    // ── Update (POST) ──────────────────────────────────────────────────────
    public function update(int $id)
    {
        $model    = new CustomerModel();
        $customer = $model->find($id);

        if (! $customer) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                "Customer #{$id} not found."
            );
        }

        $data = [
            'full_name' => trim($this->request->getPost('full_name')),
            'email'     => trim($this->request->getPost('email')),
            'phone'     => trim($this->request->getPost('phone')),
        ];

        if (! $model->update($id, $data)) {
            session()->setFlashdata('errors', $model->errors());
            session()->setFlashdata('old', $data);
            return redirect()->to(base_url("/customers/{$id}/edit"));
        }

        session()->setFlashdata('success', 'Customer "' . esc($data['full_name']) . '" was updated successfully.');
        return redirect()->to(base_url('/customers'));
    }
}