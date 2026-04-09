<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => '松井 慶太',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
        ]);

        $clients = [
            ['name' => '株式会社テックスタジオ', 'contact_person' => '田中 健一', 'email' => 'tanaka@techstudio.jp', 'phone' => '03-1234-5678'],
            ['name' => '合同会社クリエイティブ', 'contact_person' => '鈴木 美咲', 'email' => 'suzuki@creative.co.jp', 'phone' => '06-9876-5432'],
            ['name' => '株式会社フューチャー', 'contact_person' => '佐藤 大輝', 'email' => 'sato@future.inc', 'phone' => '045-555-0000'],
            ['name' => '有限会社デザインラボ', 'contact_person' => '山田 花子', 'email' => 'yamada@designlab.jp', 'phone' => '052-111-2222'],
        ];

        $createdClients = [];
        foreach ($clients as $clientData) {
            $createdClients[] = $user->clients()->create($clientData);
        }

        $projects = [
            ['client' => 0, 'name' => 'コーポレートサイトリニューアル', 'budget' => 1200000, 'start_date' => '2026-01-15', 'deadline' => '2026-04-30', 'status' => '進行中', 'description' => 'トップページ〜各サービスページの全面刷新'],
            ['client' => 0, 'name' => 'ECサイト構築', 'budget' => 2500000, 'start_date' => '2026-02-01', 'deadline' => '2026-06-30', 'status' => '進行中', 'description' => 'Laravelを使ったECサイトの新規開発'],
            ['client' => 1, 'name' => 'ブランドロゴ制作', 'budget' => 350000, 'start_date' => '2026-03-01', 'deadline' => '2026-04-15', 'status' => '完了'],
            ['client' => 1, 'name' => 'SNS広告バナー制作', 'budget' => 180000, 'start_date' => '2026-03-20', 'deadline' => '2026-04-20', 'status' => '商談中'],
            ['client' => 2, 'name' => '採用管理システム開発', 'budget' => 3200000, 'start_date' => '2026-01-10', 'deadline' => '2026-05-31', 'status' => '進行中'],
            ['client' => 3, 'name' => 'スマホアプリUI設計', 'budget' => 680000, 'start_date' => '2026-02-15', 'deadline' => '2026-03-31', 'status' => '完了'],
        ];

        $createdProjects = [];
        foreach ($projects as $p) {
            $createdProjects[] = $user->projects()->create([
                'client_id'   => $createdClients[$p['client']]->id,
                'name'        => $p['name'],
                'budget'      => $p['budget'],
                'start_date'  => $p['start_date'],
                'deadline'    => $p['deadline'],
                'status'      => $p['status'],
                'description' => $p['description'] ?? null,
            ]);
        }

        $invoices = [
            ['project' => 0, 'number' => 'INV-2026-001', 'amount' => 600000, 'issue_date' => '2026-02-28', 'due_date' => '2026-03-31', 'status' => '入金済'],
            ['project' => 0, 'number' => 'INV-2026-002', 'amount' => 600000, 'issue_date' => '2026-04-01', 'due_date' => '2026-04-30', 'status' => '送付済'],
            ['project' => 1, 'number' => 'INV-2026-003', 'amount' => 1000000, 'issue_date' => '2026-02-15', 'due_date' => '2026-03-15', 'status' => '入金済'],
            ['project' => 2, 'number' => 'INV-2026-004', 'amount' => 350000, 'issue_date' => '2026-04-01', 'due_date' => '2026-04-30', 'status' => '未送付'],
            ['project' => 4, 'number' => 'INV-2026-005', 'amount' => 1600000, 'issue_date' => '2026-02-28', 'due_date' => '2026-03-15', 'status' => '未入金期限超過'],
            ['project' => 5, 'number' => 'INV-2026-006', 'amount' => 680000, 'issue_date' => '2026-04-01', 'due_date' => '2026-04-30', 'status' => '送付済'],
        ];

        foreach ($invoices as $inv) {
            $user->invoices()->create([
                'project_id'     => $createdProjects[$inv['project']]->id,
                'invoice_number' => $inv['number'],
                'amount'         => $inv['amount'],
                'issue_date'     => $inv['issue_date'],
                'due_date'       => $inv['due_date'],
                'status'         => $inv['status'],
            ]);
        }
    }
}
