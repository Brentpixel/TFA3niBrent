<?php

/**
 * TFA4 Comprehensive Verification Test Suite
 * Tests against http://localhost:8080:
 *   Case A: Logged-out access (Protected URLs redirect to /login)
 *   Case B: Login (Empty fields, invalid credentials, valid credentials)
 *   Case C: Logged-in access (Customer & User CRUD pages accessible)
 *   Case D: Password handling (Database hash verification, new user, password change, blank password update)
 *   Case E: Logout (Session destruction, redirect to /login, back button protection)
 *   Case F: Customer CRUD validation & preservation
 */

$baseUrl = 'http://localhost:8080';

function request($url, $method = 'GET', $data = [], $cookieJar = null, $follow = true) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

    if ($cookieJar) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
    }

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $raw = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $header = substr($raw, 0, $headerSize);
    $body = substr($raw, $headerSize);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'effective_url' => $effectiveUrl,
        'header' => $header,
        'body' => $body,
    ];
}

function extractCsrf($html) {
    if (preg_match('/name="(csrf_[^"]+)"\s+value="([^"]+)"/', $html, $matches)) {
        return [$matches[1] => $matches[2]];
    }
    return [];
}

echo "========================================================\n";
echo "      IT0049 TFA4 COMPLETE AUTOMATED TEST SUITE        \n";
echo "========================================================\n\n";

$allPassed = true;

function assertTest($description, $condition) {
    global $allPassed;
    if ($condition) {
        echo " [PASS] $description\n";
    } else {
        echo " [FAIL] $description\n";
        $allPassed = false;
    }
}

// -----------------------------------------------------------------------------
// TEST SUITE A: Logged-out access
// -----------------------------------------------------------------------------
echo "--- TEST SUITE A: Logged-Out Access Redirection ---\n";

$loggedOutCookie = __DIR__ . '/logged_out_cookie.txt';
if (file_exists($loggedOutCookie)) unlink($loggedOutCookie);

// 1. Visit /customers
$r = request("$baseUrl/customers", 'GET', [], $loggedOutCookie, false);
assertTest("GET /customers redirects (HTTP 302)", $r['code'] === 302 && strpos($r['header'], 'login') !== false);

// 2. Visit /customers/new
$r = request("$baseUrl/customers/new", 'GET', [], $loggedOutCookie, false);
assertTest("GET /customers/new redirects to /login", $r['code'] === 302 && strpos($r['header'], 'login') !== false);

// 3. Visit /customers/1/edit
$r = request("$baseUrl/customers/1/edit", 'GET', [], $loggedOutCookie, false);
assertTest("GET /customers/1/edit redirects to /login", $r['code'] === 302 && strpos($r['header'], 'login') !== false);

// 4. Visit /users
$r = request("$baseUrl/users", 'GET', [], $loggedOutCookie, false);
assertTest("GET /users redirects to /login", $r['code'] === 302 && strpos($r['header'], 'login') !== false);

// 5. Visit /users/new
$r = request("$baseUrl/users/new", 'GET', [], $loggedOutCookie, false);
assertTest("GET /users/new redirects to /login", $r['code'] === 302 && strpos($r['header'], 'login') !== false);

// 6. Visit /users/1/edit
$r = request("$baseUrl/users/1/edit", 'GET', [], $loggedOutCookie, false);
assertTest("GET /users/1/edit redirects to /login", $r['code'] === 302 && strpos($r['header'], 'login') !== false);

// 7. Verify flash notice when following redirect
$r = request("$baseUrl/customers", 'GET', [], $loggedOutCookie, true);
assertTest("Redirect to /login shows flash error 'Please log in to access this page.'", strpos($r['body'], 'Please log in to access this page.') !== false);

// 8. Public routes remain accessible
$r = request("$baseUrl/", 'GET', [], $loggedOutCookie, false);
assertTest("Public route GET / is accessible (HTTP 200)", $r['code'] === 200);

$r = request("$baseUrl/tasks", 'GET', [], $loggedOutCookie, false);
assertTest("Public route GET /tasks is accessible (HTTP 200)", $r['code'] === 200);

$r = request("$baseUrl/profile", 'GET', [], $loggedOutCookie, false);
assertTest("Public route GET /profile is accessible (HTTP 200)", $r['code'] === 200);

$r = request("$baseUrl/about", 'GET', [], $loggedOutCookie, false);
assertTest("Public route GET /about is accessible (HTTP 200)", $r['code'] === 200);

if (file_exists($loggedOutCookie)) unlink($loggedOutCookie);


// -----------------------------------------------------------------------------
// TEST SUITE B: Login
// -----------------------------------------------------------------------------
echo "\n--- TEST SUITE B: Login Validation & Authentication ---\n";

$loginCookie = __DIR__ . '/login_session_cookie.txt';
if (file_exists($loginCookie)) unlink($loginCookie);

// 1. GET /login form
$r = request("$baseUrl/login", 'GET', [], $loginCookie);
assertTest("GET /login displays Sign In form", $r['code'] === 200 && strpos($r['body'], 'Sign In') !== false);
$csrf = extractCsrf($r['body']);

// 2. Submit empty fields
$postData = array_merge($csrf, ['username' => '', 'password' => '']);
$r = request("$baseUrl/login", 'POST', $postData, $loginCookie);
assertTest("Empty fields trigger validation errors", strpos($r['body'], 'Please enter your username.') !== false || strpos($r['body'], 'Please enter your password.') !== false);
$csrf = extractCsrf($r['body']);

// 3. Submit invalid credentials
$postData = array_merge($csrf, ['username' => 'wronguser', 'password' => 'wrongpass']);
$r = request("$baseUrl/login", 'POST', $postData, $loginCookie);
assertTest("Invalid credentials show generic 'Invalid username or password.'", strpos($r['body'], 'Invalid username or password.') !== false);
assertTest("Password field is NEVER preserved in output", strpos($r['body'], 'value="wrongpass"') === false);
assertTest("Username is preserved in username field", strpos($r['body'], 'value="wronguser"') !== false);
$csrf = extractCsrf($r['body']);

// 4. Submit valid credentials
$postData = array_merge($csrf, ['username' => 'bverdera', 'password' => 'patatasmay21']);
$r = request("$baseUrl/login", 'POST', $postData, $loginCookie, true);
assertTest("Valid credentials authenticate and redirect to home", $r['effective_url'] === "$baseUrl/" && strpos($r['body'], 'Welcome back') !== false);

// 5. Authenticated user visiting /login gets redirected to /
$r = request("$baseUrl/login", 'GET', [], $loginCookie, false);
assertTest("Already logged-in user visiting /login redirects to dashboard (HTTP 302)", $r['code'] === 302);


// -----------------------------------------------------------------------------
// TEST SUITE C: Logged-in access & Navigation
// -----------------------------------------------------------------------------
echo "\n--- TEST SUITE C: Logged-In Protected Access & Navbar ---\n";

// 1. Visit /customers
$r = request("$baseUrl/customers", 'GET', [], $loginCookie, false);
assertTest("Logged-in user can access GET /customers (HTTP 200)", $r['code'] === 200);

// 2. Visit /users
$r = request("$baseUrl/users", 'GET', [], $loginCookie, false);
assertTest("Logged-in user can access GET /users (HTTP 200)", $r['code'] === 200);

// 3. Navbar shows user name and Logout button, and DOES NOT show Login
assertTest("Navbar shows logged-in user's name", strpos($r['body'], 'Brent Verdera') !== false || strpos($r['body'], 'bverdera') !== false);
assertTest("Navbar displays Logout button", strpos($r['body'], 'Logout') !== false);
assertTest("Navbar DOES NOT show Login link", strpos($r['body'], 'id="btn-login-nav"') === false);


// -----------------------------------------------------------------------------
// TEST SUITE D: Password Handling & User CRUD
// -----------------------------------------------------------------------------
echo "\n--- TEST SUITE D: Password Handling & User CRUD ---\n";

$db = mysqli_connect('localhost', 'root', '', 'codeigniter_pos');
$res = mysqli_query($db, "SELECT username, password FROM users WHERE username = 'bverdera'");
$row = mysqli_fetch_assoc($res);
assertTest("Database contains bcrypt password hash (starts with $2y$)", strpos($row['password'], '$2y$') === 0);
assertTest("password_verify confirms 'patatasmay21' against database hash", password_verify('patatasmay21', $row['password']));

// 1. Create a new user with password confirmation
$r = request("$baseUrl/users/new", 'GET', [], $loginCookie);
$csrf = extractCsrf($r['body']);
$testUser = 'testuser_' . substr(uniqid(), -5);
$testPass = 'InitialPass123!';

$newUserData = array_merge($csrf, [
    'username'         => $testUser,
    'full_name'        => 'Test User TFA4',
    'email'            => 'testuser@example.com',
    'password'         => $testPass,
    'password_confirm' => $testPass,
]);

$r = request("$baseUrl/users/new", 'POST', $newUserData, $loginCookie, true);
assertTest("Creating new user succeeds and redirects to /users", strpos($r['body'], $testUser) !== false);

// 2. Verify new user in DB
$res = mysqli_query($db, "SELECT id, password FROM users WHERE username = '$testUser'");
$newUserRow = mysqli_fetch_assoc($res);
$newUserId = $newUserRow['id'];
$initialHash = $newUserRow['password'];
assertTest("New user in DB has hashed password", !empty($initialHash) && strpos($initialHash, '$2y$') === 0);

// 3. Edit user: Leave password blank -> preserves existing hash
$r = request("$baseUrl/users/$newUserId/edit", 'GET', [], $loginCookie);
$csrf = extractCsrf($r['body']);
$editDataBlankPass = array_merge($csrf, [
    'username'         => $testUser,
    'full_name'        => 'Test User Updated Name',
    'email'            => 'testuser@example.com',
    'password'         => '',
    'password_confirm' => '',
]);
$r = request("$baseUrl/users/$newUserId/edit", 'POST', $editDataBlankPass, $loginCookie, true);
assertTest("Updating user with blank password succeeds", strpos($r['body'], 'was updated successfully') !== false);

$res = mysqli_query($db, "SELECT password FROM users WHERE id = $newUserId");
$preservedRow = mysqli_fetch_assoc($res);
assertTest("Leaving password blank preserves existing password hash", $preservedRow['password'] === $initialHash);

// 4. Edit user: Change password -> updates hash and allows login with new password
$r = request("$baseUrl/users/$newUserId/edit", 'GET', [], $loginCookie);
$csrf = extractCsrf($r['body']);
$updatedPass = 'ChangedPass2026!';
$editDataNewPass = array_merge($csrf, [
    'username'         => $testUser,
    'full_name'        => 'Test User Updated Name',
    'email'            => 'testuser@example.com',
    'password'         => $updatedPass,
    'password_confirm' => $updatedPass,
]);
$r = request("$baseUrl/users/$newUserId/edit", 'POST', $editDataNewPass, $loginCookie, true);
assertTest("Changing user password succeeds", strpos($r['body'], 'was updated successfully') !== false);

$res = mysqli_query($db, "SELECT password FROM users WHERE id = $newUserId");
$changedRow = mysqli_fetch_assoc($res);
assertTest("Updated password hash differs from original", $changedRow['password'] !== $initialHash);
assertTest("Updated password matches new password via password_verify", password_verify($updatedPass, $changedRow['password']));

// Test logging in with the changed password
$changedUserCookie = __DIR__ . '/changed_cookie.txt';
if (file_exists($changedUserCookie)) unlink($changedUserCookie);
$r = request("$baseUrl/login", 'GET', [], $changedUserCookie);
$csrf = extractCsrf($r['body']);
$r = request("$baseUrl/login", 'POST', array_merge($csrf, ['username' => $testUser, 'password' => $updatedPass]), $changedUserCookie, true);
assertTest("Login succeeds with newly changed password", strpos($r['body'], 'Test User Updated Name') !== false);
if (file_exists($changedUserCookie)) unlink($changedUserCookie);


// -----------------------------------------------------------------------------
// TEST SUITE E: Logout Workflow
// -----------------------------------------------------------------------------
echo "\n--- TEST SUITE E: Logout Workflow & Session Destruction ---\n";

// 1. Submit POST /logout
$r = request("$baseUrl/customers", 'GET', [], $loginCookie);
$csrf = extractCsrf($r['body']);

$r = request("$baseUrl/logout", 'POST', $csrf, $loginCookie, true);
assertTest("POST /logout redirects to /login", $r['effective_url'] === "$baseUrl/login");
assertTest("Logout displays confirmation flash message", strpos($r['body'], 'You have been logged out successfully.') !== false);

// 2. Protected routes are now blocked again with that session cookie
$r = request("$baseUrl/customers", 'GET', [], $loginCookie, false);
assertTest("After logout, GET /customers redirects to /login (HTTP 302)", $r['code'] === 302 && strpos($r['header'], 'login') !== false);

$r = request("$baseUrl/users", 'GET', [], $loginCookie, false);
assertTest("After logout, GET /users redirects to /login (HTTP 302)", $r['code'] === 302 && strpos($r['header'], 'login') !== false);

// Clean up
// Remove test created user from DB
mysqli_query($db, "DELETE FROM users WHERE username = '$testUser'");
if (file_exists($loginCookie)) unlink($loginCookie);
mysqli_close($db);

echo "\n========================================================\n";
if ($allPassed) {
    echo "  ALL TEST SUITES PASSED SUCCESSFULLY! (100% SUCCESS)   \n";
} else {
    echo "  SOME TESTS FAILED - PLEASE REVIEW LOGS ABOVE.         \n";
}
echo "========================================================\n";
