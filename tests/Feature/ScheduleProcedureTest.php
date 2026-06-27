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

test('client sees subtopics when admin adds them to a procedure', function () {
    $admin = User::factory()->admin()->create();
    $client = User::factory()->create();
    $date = now()->addDay()->toDateString();

    $this->actingAs($admin)
        ->post("/admin/procedures/{$date}", [
            'procedures' => [
                [
                    'name_lv' => 'Apjoms',
                    'name_en' => 'Volume',
                    'name_ru' => 'Объем',
                    'price' => 0,
                    'subtopics' => [
                        [
                            'name_lv' => '2D',
                            'name_en' => '2D',
                            'name_ru' => '2D',
                            'price' => 25,
                        ],
                        [
                            'name_lv' => '3D',
                            'name_en' => '3D',
                            'name_ru' => '3D',
                            'price' => 30,
                        ],
                    ],
                ],
            ],
            'times' => ['10:00'],
        ])
        ->assertRedirect();

    $response = $this->actingAs($client)
        ->getJson('/calendar/schedule?date=' . $date);

    $response->assertOk()
        ->assertJsonPath('procedures.0.name', 'Apjoms')
        ->assertJsonCount(2, 'procedures.0.subtopics')
        ->assertJsonPath('procedures.0.subtopics.0.name', '2D')
        ->assertJsonPath('procedures.0.subtopics.0.price', '25.00');
});

test('client can book a subtopic procedure', function () {
    $admin = User::factory()->admin()->create();
    $client = User::factory()->create();
    $date = now()->addDay()->toDateString();

    $this->actingAs($admin)
        ->post("/admin/procedures/{$date}", [
            'procedures' => [
                [
                    'name_lv' => 'Apjoms',
                    'name_en' => 'Volume',
                    'name_ru' => 'Объем',
                    'price' => 0,
                    'subtopics' => [
                        [
                            'name_lv' => '2D',
                            'name_en' => '2D',
                            'name_ru' => '2D',
                            'price' => 25,
                        ],
                    ],
                ],
            ],
            'times' => ['10:00'],
        ]);

    $schedule = app(ScheduleService::class)->getProceduresForDate($date);
    $subRef = $schedule->first()->subtopics[0]->ref;

    $this->actingAs($client)
        ->post('/book', [
            'date' => $date,
            'time' => '10:00',
            'client_name' => 'Līga',
            'procedure_ref' => $subRef,
        ])
        ->assertRedirect(route('user.index'));

    $this->assertDatabaseHas('bookings', [
        'user_id' => $client->id,
        'client_name' => 'Līga',
    ]);
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
            'subtopics' => [
                [
                    'name_lv' => '1:1',
                    'name_en' => '1:1',
                    'name_ru' => '1:1',
                    'price' => 18,
                ],
            ],
        ],
    ], ['09:00']);

    $procedures = $service->getProceduresForDate($date);

    expect($procedures)->toHaveCount(1)
        ->and($procedures->first()->name_lv)->toBe('Klasika')
        ->and((float) $procedures->first()->price)->toBe(15.0)
        ->and($procedures->first()->subtopics)->toHaveCount(1)
        ->and($procedures->first()->subtopics[0]->name_lv)->toBe('1:1');
});
