<?php
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$pengawasRole = Role::firstOrCreate(['name' => 'Pengawas']);

// Pindahkan user Pimpinan Daerah ke Pengawas
$pimpinanUsers = User::role('Pimpinan Daerah')->get();
foreach($pimpinanUsers as $user) {
    $user->assignRole('Pengawas');
    $user->removeRole('Pimpinan Daerah');
    echo "User {$user->username} reassigned to Pengawas\n";
}

// Pindahkan user Hiswana Migas ke Pengawas
$hiswanaUsers = User::role('Hiswana Migas')->get();
foreach($hiswanaUsers as $user) {
    $user->assignRole('Pengawas');
    $user->removeRole('Hiswana Migas');
    echo "User {$user->username} reassigned to Pengawas\n";
}

// Hapus role lama
Role::whereIn('name', ['Pimpinan Daerah', 'Hiswana Migas'])->delete();
echo "Old roles deleted successfully.\n";

// Update default user login for pengawas if needed
$defaultUser = User::where('username', 'pengawas')->first();
if (!$defaultUser) {
    $defaultUser = User::create([
        'name' => 'Pengawas',
        'username' => 'pengawas',
        'password' => \Hash::make('password'),
        'status_aktif' => true,
    ]);
    $defaultUser->assignRole('Pengawas');
    echo "Created default pengawas user.\n";
}

// Ensure old default users are disabled or deleted (optional, let's just delete them to avoid confusion)
User::whereIn('username', ['pimpinandaerah', 'hiswanamigas'])->delete();
echo "Old default users deleted.\n";
