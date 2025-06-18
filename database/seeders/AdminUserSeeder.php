<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Import the User model

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This method will find the user with ID 1 and assign them the 'admin' role.
     */
    public function run(): void
    {
        // Find the user with the primary key (id) of 1.
        $user = User::find(1);

        // Check if the user was actually found to avoid errors.
        if ($user) {
            // Update the user's role to 'admin'.
            $user->role = 'admin';
            // Save the changes to the database.
            $user->save();

            // Optionally, you can output a success message to the console.
            $this->command->info('User with ID 1 has been granted admin privileges.');
        } else {
            // Output a warning if the user with ID 1 could not be found.
            $this->command->warn('Could not find user with ID 1 to make an admin.');
        }
    }
}
