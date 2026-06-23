<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create superadmin
$superadmin = User::updateOrCreate(
    ['email' => 'contact.rakeshmaity@gmail.com'],
    [
        'name'     => 'Rakesh Maity',
        'password' => Hash::make('Password@123'),
        'is_admin' => true,
        'role'     => 'superadmin',
    ]
);
echo "Superadmin created: {$superadmin->email} (role: {$superadmin->role})\n";

// Change existing admin to restricted admin role
$admin = User::where('email', 'admin@therapistlysander.nl')->first();
if ($admin) {
    $admin->role = 'admin';
    $admin->save();
    echo "Admin role updated: {$admin->email} (role: {$admin->role})\n";
}

echo "Done!\n";
