<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Users Controller
 *
 * Routes (defined in app/Config/Routes.php):
 *   GET  /users              → index()   — listing with avatars
 *   GET  /users/new          → newForm() — blank create form
 *   POST /users/new          → create()  — process create
 *   GET  /users/(:num)/edit  → edit()    — pre-filled edit form with avatar upload
 *   POST /users/(:num)/edit  → update()  — process update + avatar upload
 */
class Users extends BaseController
{
    /** Max file size in bytes (2 MB) */
    private int $maxBytes = 2 * 1024 * 1024;

    /** Thumbnail size in pixels (square). */
    private int $thumbSize = 200;

    /** Returns the absolute path to the avatar upload directory. */
    private function uploadDir(): string
    {
        return FCPATH . 'uploads/avatars/';
    }

    /**
     * Resize + centre-crop an image to a square thumbnail using GD.
     *
     * The output is always saved as a JPEG (quality 90) regardless of
     * the source format. The source file is overwritten in-place.
     *
     * @param  string $filePath  Absolute path to the uploaded image file.
     * @param  string $mime      MIME type ('image/jpeg' or 'image/png').
     * @return bool              True on success, false if GD is unavailable or failed.
     */
    private function makeThumbnail(string $filePath, string $mime): bool
    {
        if (! extension_loaded('gd')) {
            return false; // GD not available — keep original file as-is
        }

        // Load source image
        $src = match ($mime) {
            'image/png'  => @imagecreatefrompng($filePath),
            default      => @imagecreatefromjpeg($filePath),
        };

        if (! $src) {
            return false;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);

        // ── Centre-crop to a square ───────────────────────────────────────
        if ($origW > $origH) {
            $cropX = (int)(($origW - $origH) / 2);
            $cropY = 0;
            $cropSide = $origH;
        } elseif ($origH > $origW) {
            $cropX = 0;
            $cropY = (int)(($origH - $origW) / 2);
            $cropSide = $origW;
        } else {
            $cropX = $cropY = 0;
            $cropSide = $origW;
        }

        // ── Resize to thumbSize × thumbSize ───────────────────────────────
        $thumb = imagecreatetruecolor($this->thumbSize, $this->thumbSize);

        // Preserve transparency for PNG sources
        if ($mime === 'image/png') {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
            imagefilledrectangle($thumb, 0, 0, $this->thumbSize, $this->thumbSize, $transparent);
        }

        imagecopyresampled(
            $thumb, $src,
            0, 0,                  // destination x, y
            $cropX, $cropY,        // source x, y
            $this->thumbSize, $this->thumbSize,   // destination w, h
            $cropSide, $cropSide   // source w, h
        );

        imagedestroy($src);

        // ── Save thumbnail as JPEG (overwrite the original file) ──────────
        $result = imagejpeg($thumb, $filePath, 90);
        imagedestroy($thumb);

        return $result;
    }

    // ── Listing ────────────────────────────────────────────────────────────
    public function index(): string
    {
        $model = new UserModel();

        return view('users/index', [
            'pageTitle' => 'User Accounts',
            'title'     => 'User Accounts',
            'users'     => $model->orderBy('id', 'ASC')->findAll(),
            'flash'     => session()->getFlashdata('success'),
        ]);
    }

    // ── New user form (GET) ────────────────────────────────────────────────
    public function newForm(): string
    {
        return view('users/form', [
            'pageTitle'  => 'New User',
            'formAction' => base_url('/users/new'),
            'user'       => null,
            'errors'     => session()->getFlashdata('errors') ?? [],
            'old'        => session()->getFlashdata('old')    ?? [],
        ]);
    }

    // ── Create (POST) ──────────────────────────────────────────────────────
    public function create()
    {
        $model = new UserModel();

        $data = [
            'username'   => trim($this->request->getPost('username')),
            'full_name'  => trim($this->request->getPost('full_name')),
            'email'      => trim($this->request->getPost('email')),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // No avatar on create (only editable after record exists)
        if (! $model->insert($data)) {
            session()->setFlashdata('errors', $model->errors());
            session()->setFlashdata('old', [
                'username'  => $data['username'],
                'full_name' => $data['full_name'],
                'email'     => $data['email'],
            ]);
            return redirect()->to(base_url('/users/new'));
        }

        session()->setFlashdata('success', 'User "' . esc($data['username']) . '" was added successfully.');
        return redirect()->to(base_url('/users'));
    }

    // ── Edit form (GET) ────────────────────────────────────────────────────
    public function edit(int $id): string
    {
        $model = new UserModel();
        $user  = $model->find($id);

        if (! $user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                "User #{$id} not found."
            );
        }

        return view('users/form', [
            'pageTitle'  => 'Edit User',
            'formAction' => base_url("/users/{$id}/edit"),
            'user'       => $user,
            'errors'     => session()->getFlashdata('errors') ?? [],
            'old'        => session()->getFlashdata('old')    ?? [],
        ]);
    }

    // ── Update (POST) ──────────────────────────────────────────────────────
    public function update(int $id)
    {
        $model = new UserModel();
        $user  = $model->find($id);

        if (! $user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                "User #{$id} not found."
            );
        }

        $newUsername = trim($this->request->getPost('username'));
        $newFullName = trim($this->request->getPost('full_name'));
        $newEmail    = trim($this->request->getPost('email'));

        // ── Build validation rules for update ─────────────────────────────
        // If the username is unchanged we skip the unique check for this row.
        if ($newUsername === $user['username']) {
            $usernameRule = 'required|min_length[3]|max_length[50]|alpha_dash';
        } else {
            $usernameRule = 'required|min_length[3]|max_length[50]|alpha_dash|is_unique[users.username]';
        }

        $rules = [
            'username'  => $usernameRule,
            'full_name' => 'required|min_length[2]|max_length[100]',
            'email'     => 'permit_empty|valid_email|max_length[100]',
        ];

        $messages = [
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

        // Run field validation
        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);

        if (! $validation->withRequest($this->request)->run()) {
            session()->setFlashdata('errors', $validation->getErrors());
            session()->setFlashdata('old', [
                'username'  => $newUsername,
                'full_name' => $newFullName,
                'email'     => $newEmail,
            ]);
            return redirect()->to(base_url("/users/{$id}/edit"));
        }

        // ── Handle avatar upload ───────────────────────────────────────────
        $avatarFile = $this->request->getFile('avatar');
        $newAvatar  = $user['avatar']; // keep existing by default

        if ($avatarFile && $avatarFile->isValid() && ! $avatarFile->hasMoved()) {

            // Validate MIME type by actual file content
            $mime = $avatarFile->getMimeType();
            if (! in_array($mime, ['image/jpeg', 'image/png'], true)) {
                session()->setFlashdata('errors', [
                    'avatar' => 'Only JPG and PNG images are accepted.',
                ]);
                session()->setFlashdata('old', [
                    'username'  => $newUsername,
                    'full_name' => $newFullName,
                    'email'     => $newEmail,
                ]);
                return redirect()->to(base_url("/users/{$id}/edit"));
            }

            // Validate file size
            if ($avatarFile->getSize() > $this->maxBytes) {
                session()->setFlashdata('errors', [
                    'avatar' => 'Image must be 2 MB or smaller.',
                ]);
                session()->setFlashdata('old', [
                    'username'  => $newUsername,
                    'full_name' => $newFullName,
                    'email'     => $newEmail,
                ]);
                return redirect()->to(base_url("/users/{$id}/edit"));
            }

            // Thumbnails are always stored as JPEG regardless of source format
            $newFilename = 'avatar_' . $id . '_' . bin2hex(random_bytes(8)) . '.jpg';

            // Ensure upload directory exists and is writable
            if (! is_dir($this->uploadDir())) {
                mkdir($this->uploadDir(), 0755, true);
            }

            // Move uploaded file to the avatars directory
            if (! $avatarFile->move($this->uploadDir(), $newFilename)) {
                session()->setFlashdata('errors', [
                    'avatar' => 'Upload failed. Please try again.',
                ]);
                session()->setFlashdata('old', [
                    'username'  => $newUsername,
                    'full_name' => $newFullName,
                    'email'     => $newEmail,
                ]);
                return redirect()->to(base_url("/users/{$id}/edit"));
            }

            // ── Generate 200×200 thumbnail (overwrites the moved file) ────
            $movedPath = $this->uploadDir() . $newFilename;
            if (! $this->makeThumbnail($movedPath, $mime)) {
                // GD unavailable or failed — original file kept as-is; that's fine
            }

            // Delete old avatar file if it exists and is different
            if (
                ! empty($user['avatar']) &&
                $user['avatar'] !== $newFilename &&
                file_exists($this->uploadDir() . $user['avatar'])
            ) {
                @unlink($this->uploadDir() . $user['avatar']);
            }

            $newAvatar = $newFilename;
        }

        // ── Persist changes ────────────────────────────────────────────────
        $model->skipValidation(true)->update($id, [
            'username'  => $newUsername,
            'full_name' => $newFullName,
            'email'     => $newEmail,
            'avatar'    => $newAvatar,
        ]);

        session()->setFlashdata('success', 'User "' . esc($newUsername) . '" was updated successfully.');
        return redirect()->to(base_url('/users'));
    }
}