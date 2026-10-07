<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Users Controller
 *
 * Routes (defined in app/Config/Routes.php):
 *   GET  /users              → index()   — listing with avatars (no password hashes exposed)
 *   GET  /users/new          → newForm() — blank create form
 *   POST /users/new          → create()  — process create with password confirmation + hashing
 *   GET  /users/(:num)/edit  → edit()    — pre-filled edit form with avatar & optional password change
 *   POST /users/(:num)/edit  → update()  — process update + avatar upload + optional password hash
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
            0, 0,                                 // destination x, y
            $cropX, $cropY,                       // source x, y
            $this->thumbSize, $this->thumbSize,   // destination w, h
            $cropSide, $cropSide                  // source w, h
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
            'users'     => $model->select('id, username, full_name, email, avatar, created_at')
                                 ->orderBy('id', 'ASC')
                                 ->findAll(),
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

        $rules = [
            'username'         => 'required|min_length[3]|max_length[50]|alpha_dash|is_unique[users.username]',
            'full_name'        => 'required|min_length[2]|max_length[100]',
            'email'            => 'permit_empty|valid_email|max_length[100]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
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
            'password' => [
                'required'   => 'Password is required.',
                'min_length' => 'Password must be at least 8 characters.',
            ],
            'password_confirm' => [
                'required' => 'Please confirm your password.',
                'matches'  => 'Password confirmation does not match the password.',
            ],
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);

        $rawUsername = trim((string) $this->request->getPost('username'));
        $rawFullName = trim((string) $this->request->getPost('full_name'));
        $rawEmail    = trim((string) $this->request->getPost('email'));

        if (! $validation->withRequest($this->request)->run()) {
            session()->setFlashdata('errors', $validation->getErrors());
            // Never preserve plain-text password or confirmation in session
            session()->setFlashdata('old', [
                'username'  => $rawUsername,
                'full_name' => $rawFullName,
                'email'     => $rawEmail,
            ]);
            return redirect()->to(base_url('/users/new'));
        }

        $passwordRaw = (string) $this->request->getPost('password');
        $hashedPassword = password_hash($passwordRaw, PASSWORD_DEFAULT);

        $data = [
            'username'   => $rawUsername,
            'full_name'  => $rawFullName,
            'email'      => $rawEmail,
            'password'   => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if (! $model->skipValidation(true)->insert($data)) {
            session()->setFlashdata('errors', $model->errors());
            session()->setFlashdata('old', [
                'username'  => $rawUsername,
                'full_name' => $rawFullName,
                'email'     => $rawEmail,
            ]);
            return redirect()->to(base_url('/users/new'));
        }

        session()->setFlashdata('success', 'User "' . esc($rawUsername) . '" was added successfully.');
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

        // Never expose password hash to the view
        unset($user['password']);

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

        $newUsername = trim((string) $this->request->getPost('username'));
        $newFullName = trim((string) $this->request->getPost('full_name'));
        $newEmail    = trim((string) $this->request->getPost('email'));
        $newPassword = (string) $this->request->getPost('password');

        // ── Build validation rules for update ─────────────────────────────
        // Skip uniqueness check if username is unchanged for this user
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

        // Optional password change
        if (! empty($newPassword)) {
            $rules['password']         = 'min_length[8]';
            $rules['password_confirm'] = 'required|matches[password]';

            $messages['password'] = [
                'min_length' => 'New password must be at least 8 characters.',
            ];
            $messages['password_confirm'] = [
                'required' => 'Please confirm your new password.',
                'matches'  => 'Password confirmation does not match the new password.',
            ];
        }

        // Run field validation
        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);

        if (! $validation->withRequest($this->request)->run()) {
            session()->setFlashdata('errors', $validation->getErrors());
            // Never preserve plain-text password in session
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
                // GD unavailable or failed — original file kept as-is
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
        $updateData = [
            'username'  => $newUsername,
            'full_name' => $newFullName,
            'email'     => $newEmail,
            'avatar'    => $newAvatar,
        ];

        // Only update password if a new one was provided
        if (! empty($newPassword)) {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $model->skipValidation(true)->update($id, $updateData);

        session()->setFlashdata('success', 'User "' . esc($newUsername) . '" was updated successfully.');
        return redirect()->to(base_url('/users'));
    }
}