<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $notifications = [
            // System notifications
            [
                'user_id' => null, // Null for system-wide notifications
                'title' => 'System Maintenance Scheduled',
                'message' => 'Our platform will undergo maintenance on Friday, 10:00 PM - 12:00 AM UTC. Please save your work.',
                'type' => 'info',
                'is_read' => false,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ],
            [
                'user_id' => null,
                'title' => 'New Feature Released: Advanced Analytics',
                'message' => 'We\'ve released advanced analytics dashboard. Check it out in your account settings!',
                'type' => 'success',
                'is_read' => false,
                'created_at' => $now->copy()->subDays(5),
                'updated_at' => $now->copy()->subDays(5),
            ],
            
            // User-specific notifications (for user_id = 1)
            [
                'user_id' => 1,
                'title' => 'Welcome to Our Platform!',
                'message' => 'Thank you for registering. Complete your profile to get started with all features.',
                'type' => 'success',
                'is_read' => true,
                'created_at' => $now->copy()->subDays(10),
                'updated_at' => $now->copy()->subDays(10),
            ],
            [
                'user_id' => 1,
                'title' => 'Profile Update Required',
                'message' => 'Your profile information is incomplete. Please update your profile details for better experience.',
                'type' => 'warning',
                'is_read' => false,
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(3),
            ],
            [
                'user_id' => 1,
                'title' => 'Payment Successful',
                'message' => 'Your payment of $49.99 for Premium Subscription has been processed successfully.',
                'type' => 'success',
                'is_read' => true,
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
            [
                'user_id' => 1,
                'title' => 'Security Alert',
                'message' => 'New login detected from New York, USA. If this wasn\'t you, please change your password immediately.',
                'type' => 'error',
                'is_read' => false,
                'created_at' => $now->copy()->subHours(4),
                'updated_at' => $now->copy()->subHours(4),
            ],
            
            // User-specific notifications (for user_id = 2)
            [
                'user_id' => 2,
                'title' => 'Subscription Expiring Soon',
                'message' => 'Your subscription will expire in 3 days. Renew now to avoid service interruption.',
                'type' => 'warning',
                'is_read' => false,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ],
            [
                'user_id' => 2,
                'title' => 'Document Approved',
                'message' => 'Your uploaded document "Quarterly Report.pdf" has been approved by the admin.',
                'type' => 'success',
                'is_read' => true,
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
            
            // User-specific notifications (for user_id = 3)
            [
                'user_id' => 3,
                'title' => 'New Message Received',
                'message' => 'You have received a new message from Support Team regarding your ticket #TKT-7891.',
                'type' => 'info',
                'is_read' => false,
                'created_at' => $now->copy()->subHours(12),
                'updated_at' => $now->copy()->subHours(12),
            ],
            [
                'user_id' => 3,
                'title' => 'Task Deadline Approaching',
                'message' => 'Your task "Monthly Review" is due in 24 hours. Please complete it on time.',
                'type' => 'warning',
                'is_read' => false,
                'created_at' => $now->copy()->subHours(6),
                'updated_at' => $now->copy()->subHours(6),
            ],
            
            // More system notifications
            [
                'user_id' => null,
                'title' => 'Holiday Schedule Update',
                'message' => 'Our support team will have limited availability during the holiday season (Dec 24-26).',
                'type' => 'info',
                'is_read' => false,
                'created_at' => $now->copy()->subDays(7),
                'updated_at' => $now->copy()->subDays(7),
            ],
            [
                'user_id' => null,
                'title' => 'API Rate Limits Updated',
                'message' => 'We have updated our API rate limits to improve service stability. Check our documentation for details.',
                'type' => 'info',
                'is_read' => false,
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
        ];

        // Insert notifications
        DB::table('notifications')->insert($notifications);
        
        $this->command->info('✅ Notifications seeded successfully!');
        $this->command->info('📊 Total notifications: ' . count($notifications));
        $this->command->info('👥 User notifications: User 1: 4, User 2: 2, User 3: 2, System: 4');
    }
}
