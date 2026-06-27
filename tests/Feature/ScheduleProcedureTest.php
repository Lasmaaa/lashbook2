<?php

use App\Models\User;
use App\Services\ScheduleService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('client sees procedures saved from admin panel for a date', function () {
    $admin = User::factory()->admin()->create();
    $client = User::factory()->create();
    $date = now()->addDay()->toDateString();

    $this->actingAs($admin)
        ->post("/admin/procedures/{$date}", [
            'procedures' => [
                [
                    'name_lv' => 'Jauna procedūra',
                    'name_en' => 'New procedure',
                    'name_ru' => 'Новая процедура',
                    'price' => 35.50,
                ],
                [
                    'name_lv' => 'Cita procedūra',
                    'name_en' => 'Another procedure',
                    'name_ru' => 'Другая процедура',
                    'price' => 20,
                ],
            ],
            'times' => ['10:00', '11:00'],
        ])
        ->assertRedirect();

    $response = $this->actingAs($client)
        ->getJson('/calendar/schedule?date=' . $date);

    $response->assertOk();

    $payload = $response->json();
    expect($payload['procedures'])->toHaveCount(2);
    expect(collect($payload['procedures'])->pluck('name')->all())
        ->toContain('Jauna procedūra', 'Cita procedūra');
    expect($payload['available_times'])->toBe(['10:00', '11:00']);
});

test('schedule service groups procedure fields correctly', function () {
    $service = app(ScheduleService::class);
    $date = now()->addDays(2)->toDateString();

    $service->saveForDate($date, [
        [
            'name_lv' => 'Klasika',
            'name_en' => 'Classic',
            'name_ru' => 'Классика',
            'price' => 15,
        ],
    ], ['09:00']);

    $procedures = $service->getProceduresForDate($date);

    expect($procedures)->toHaveCount(1)
        ->and($procedures->first()->name_lv)->toBe('Klasika')
        ->and((float) $procedures->first()->price)->toBe(15.0);
});
