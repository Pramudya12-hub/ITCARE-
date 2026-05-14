<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\KnowledgeBaseArticle;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $support = User::create([
            'name' => 'IT Support',
            'email' => 'it@itcare.com',
            'password' => bcrypt('password'),
            'role' => 'it_support',
        ]);

        $user = User::create([
            'name' => 'Rani Pratiwi',
            'email' => 'user@itcare.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'title' => 'Laptop tidak bisa connect ke wifi kantor',
            'category' => 'Network',
            'priority' => 'High',
            'description' => 'Sejak pagi laptop saya tidak bisa terhubung ke jaringan WIFI KEMENKEU-STAFF. Sudah dicoba restart tapi tetap tidak bisa.',
            'status' => 'In Progress',
        ]);

        Ticket::create([
            'user_id' => $user->id,
            'title' => 'Microsoft Excel sering crash',
            'category' => 'Software',
            'priority' => 'Medium',
            'description' => 'Excel tiba-tiba close sendiri saat membuka file laporan bulanan.',
            'status' => 'Open',
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $support->id,
            'comment' => 'Halo Bu Rani, saya sedang cek dari sisi controller. Mohon tunggu 10 menit ya.',
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'comment' => 'Baik pak, saya tunggu.',
        ]);

        KnowledgeBaseArticle::create([
            'title' => 'Cara Connect Ulang ke WIFI Kantor',
            'category' => 'Network',
            'content' => 'Panduan reset network adapter dan re-authenticate ke SSID utama kantor. Buka Control Panel > Network and Internet > Network Connections...',
            'status' => 'published',
            'created_by' => $support->id,
        ]);

        KnowledgeBaseArticle::create([
            'title' => 'Mengatasi Paper Jam pada Printer',
            'category' => 'Hardware',
            'content' => 'Langkah-langkah membersihkan roller pickup dan mengatasi sensor error pada printer Canon.',
            'status' => 'published',
            'created_by' => $support->id,
        ]);
    }
}
