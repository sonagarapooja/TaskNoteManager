<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\Note;
use Illuminate\Support\Facades\Hash;

class UserTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the user already exists
        $user = User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => Hash::make('TestPWD123'), // Hash the password
        ]);

        // Create two sample tasks for the user
        for ($i = 1; $i <= 2; $i++) {
            $task = Task::create([
                'subject' => 'Sample Task ' . $i,
                'description' => 'Description for Sample Task ' . $i,
                'start_date' => now(),
                'due_date' => now()->addDays(7),
                'status' => 'New', // Set default status
                'priority' => 'Medium', // Set default priority
                'user_id' => $user->id, // Associate task with the user
            ]);

            // Create notes for the task
            Note::create([
                'subject' => 'Note for Task ' . $i,
                'attachment' => null, // You can specify a file path if needed
                'note' => 'This is a note related to Sample Task ' . $i,
                'task_id' => $task->id, // Associate note with the task
            ]);
        }
    }
}
