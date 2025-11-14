<?php

// Script helper to create development users quickly.
// Usage: php scripts/create_dev_users.php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Creating dev users...\n";

$users = [
    ['name' => 'Dev Admin', 'email' => 'admin@example.test', 'password' => 'secret123', 'role' => 'admin'],
    ['name' => 'Admin Demo', 'email' => 'admin@campus.local', 'password' => 'password', 'role' => 'admin'],
    ['name' => 'Enseignant Demo', 'email' => 'teacher@campus.local', 'password' => 'password', 'role' => 'teacher'],
    ['name' => 'Etudiant Demo', 'email' => 'student@campus.local', 'password' => 'password', 'role' => 'student'],
];

foreach ($users as $u) {
    $user = User::where('email', $u['email'])->first();
    if ($user) {
        echo "- User {$u['email']} already exists, updating password/role...\n";
        $user->password = Hash::make($u['password']);
        $user->role = $u['role'];
        $user->name = $u['name'];
        $user->save();
    } else {
        User::create([
            'name' => $u['name'],
            'email' => $u['email'],
            'password' => Hash::make($u['password']),
            'role' => $u['role'],
        ]);
        echo "- Created {$u['email']}\n";
    }
}

echo "Done. You can now login with:\n";
echo "  admin@example.test / secret123\n";
echo "  admin@campus.local / password\n";
echo "  teacher@campus.local / password\n";
echo "  student@campus.local / password\n";
