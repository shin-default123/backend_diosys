<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\EmailTemplate;

class SystemSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['key' => 'parish_name', 'value' => 'St. Joseph Cathedral'],
            ['key' => 'parish_address', 'value' => 'Butuan City, Philippines'],
            ['key' => 'contact_number', 'value' => '0912-345-6789'],
            ['key' => 'email', 'value' => 'info@dioceseofbutuan.org'],
            ['key' => 'maintenance_mode', 'value' => '0'], 
            ['key' => 'theme_color', 'value' => 'light'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']], 
                ['value' => $setting['value']] 
            );
        }

        $templates = [
            [
                'code' => 'booking_received',
                'name' => 'Booking Received',
                'subject' => 'We received your booking request',
                'body' => 'Hello {name}, we have received your request for {type} on {date}. We will review it shortly.'
            ],
            [
                'code' => 'booking_approved',
                'name' => 'Booking Approved',
                'subject' => 'Booking Confirmed!',
                'body' => 'Good news {name}! Your booking for {type} on {date} has been confirmed.'
            ],
            [
                'code' => 'booking_rejected',
                'name' => 'Booking Declined',
                'subject' => 'Update regarding your booking',
                'body' => 'Dear {name}, we regret to inform you that your request for {date} cannot be accommodated.'
            ]
        ];

        foreach ($templates as $t) {
            EmailTemplate::updateOrCreate(
                ['code' => $t['code']], 
                [
                    'name' => $t['name'],
                    'subject' => $t['subject'],
                    'body' => $t['body']
                ]
            );
        }
    }
}