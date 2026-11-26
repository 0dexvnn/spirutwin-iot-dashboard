<?php

namespace Tests\Feature;

use App\Models\Kolam;
use App\Models\SensorReading;
use App\Models\User;
use App\Services\TelegramBotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TelegramWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_registers_a_telegram_user_once_and_refreshes_profile(): void
    {
        $this->postJson('/api/telegram/webhook', $this->startUpdate(123456789, 'riski_petaniku', 'Riski'))->assertOk();

        $this->assertDatabaseHas('users', [
            'telegram_user_id' => '123456789',
            'telegram_username' => 'riski_petaniku',
            'telegram_name' => 'Riski',
            'telegram_connection_status' => 'connected',
        ]);

        $this->postJson('/api/telegram/webhook', $this->startUpdate(123456789, 'riski_petani', 'Riski Baru'))->assertOk();

        $this->assertSame(1, User::where('telegram_user_id', '123456789')->count());
        $this->assertDatabaseHas('users', [
            'petani_code' => null,
            'telegram_username' => 'riski_petani',
            'telegram_first_name' => 'Riski Baru',
            'telegram_name' => 'Riski Baru',
        ]);

        $this->postJson('/api/telegram/webhook', $this->startUpdate(987654321, null, 'Tanpa Username'))->assertOk();
        $this->assertDatabaseHas('users', [
            'telegram_user_id' => '987654321',
            'telegram_username' => null,
        ]);
    }

    public function test_condition_callback_only_reads_assigned_ponds(): void
    {
        $petani = User::factory()->create(['role' => 'petani', 'telegram_user_id' => '123456789', 'telegram_chat_id' => '123456789']);
        $assigned = Kolam::firstWhere('code', 'K01');
        $petani->kolams()->attach($assigned);
        SensorReading::create(['kolam_id' => 'K01', 'kolam_nama' => 'Kolam 01', 'recorded_at' => now(), 'ph' => 7.2, 'suhu' => 28, 'cahaya' => 100, 'turbidity' => 2, 'tds' => 500, 'biomassa' => 10]);
        SensorReading::create(['kolam_id' => 'K02', 'kolam_nama' => 'Kolam 02', 'recorded_at' => now(), 'ph' => 8, 'suhu' => 30, 'cahaya' => 200, 'turbidity' => 3, 'tds' => 600, 'biomassa' => 20]);

        $this->postJson('/api/telegram/webhook', ['callback_query' => ['id' => 'callback-1', 'data' => 'check_condition', 'from' => ['id' => 123456789, 'first_name' => 'Riski'], 'message' => ['chat' => ['id' => 123456789]]]])->assertOk();

        $this->assertDatabaseCount('users', 1);
        $message = app(TelegramBotService::class)->conditionMessage($petani);
        $this->assertStringContainsString('Kolam 01', $message);
        $this->assertStringNotContainsString('Kolam 02', $message);
    }

    private function startUpdate(int $id, ?string $username, string $firstName): array
    {
        return ['message' => ['text' => '/start', 'from' => array_filter(['id' => $id, 'username' => $username, 'first_name' => $firstName], fn ($value) => $value !== null), 'chat' => ['id' => $id, 'type' => 'private']]];
    }
}
