<?php

namespace App\Services;

use App\Models\Procedure;
use App\Models\ScheduleProcedure;
use App\Models\ScheduleTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ScheduleService
{
    public const DEFAULT_TIMES = [
        '09:00', '10:00', '11:00', '12:00', '13:00',
        '14:00', '15:00', '16:00', '17:00', '18:00',
    ];

    public function getProceduresForDate(string $date): Collection
    {
        $scheduled = ScheduleProcedure::query()
            ->with('subtopics')
            ->whereDate('date', $date)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($scheduled->isNotEmpty()) {
            return $scheduled->map(fn (ScheduleProcedure $item) => (object) [
                'ref' => 'sched-' . $item->id,
                'name_lv' => $item->name_lv,
                'name_en' => $item->name_en,
                'name_ru' => $item->name_ru,
                'price' => $item->price,
                'subtopics' => $item->subtopics->map(fn ($sub) => (object) [
                    'ref' => 'sched-sub-' . $sub->id,
                    'name_lv' => $sub->name_lv,
                    'name_en' => $sub->name_en,
                    'name_ru' => $sub->name_ru,
                    'price' => $sub->price,
                ])->values()->all(),
            ]);
        }

        return Procedure::query()
            ->with('subtopics')
            ->whereNotIn('code', ['volume_2d_3d', 'volume_4d_plus'])
            ->orderBy('id')
            ->get()
            ->map(fn (Procedure $procedure) => (object) [
                'ref' => 'proc-' . $procedure->id,
                'name_lv' => $procedure->name_lv,
                'name_en' => $procedure->name_en,
                'name_ru' => $procedure->name_ru,
                'price' => $procedure->price,
                'subtopics' => $procedure->subtopics->map(fn ($sub) => (object) [
                    'ref' => 'proc-sub-' . $sub->id,
                    'name_lv' => $sub->name_lv,
                    'name_en' => $sub->name_en,
                    'name_ru' => $sub->name_ru,
                    'price' => $sub->price,
                ])->values()->all(),
            ]);
    }

    public function getTimesForDate(string $date): Collection
    {
        $scheduled = ScheduleTime::query()
            ->whereDate('date', $date)
            ->orderBy('time')
            ->get()
            ->map(fn (ScheduleTime $slot) => Carbon::parse($slot->time)->format('H:i'));

        if ($scheduled->isNotEmpty()) {
            return $scheduled;
        }

        return collect(self::DEFAULT_TIMES);
    }

    public function hasCustomSchedule(string $date): bool
    {
        return ScheduleProcedure::whereDate('date', $date)->exists()
            || ScheduleTime::whereDate('date', $date)->exists();
    }

    public function saveForDate(string $date, array $procedures, array $times): void
    {
        ScheduleProcedure::whereDate('date', $date)->delete();
        ScheduleTime::whereDate('date', $date)->delete();

        foreach ($procedures as $index => $procedure) {
            $nameLv = trim((string) ($procedure['name_lv'] ?? ''));
            $nameEn = trim((string) ($procedure['name_en'] ?? ''));
            $nameRu = trim((string) ($procedure['name_ru'] ?? ''));

            $primaryName = $nameLv ?: $nameEn ?: $nameRu;
            if ($primaryName === '') {
                continue;
            }

            $scheduleProcedure = ScheduleProcedure::create([
                'date' => $date,
                'name_lv' => $nameLv ?: $primaryName,
                'name_en' => $nameEn ?: $primaryName,
                'name_ru' => $nameRu ?: $primaryName,
                'price' => $procedure['price'] ?? 0,
                'sort_order' => $index,
            ]);

            $this->saveSubtopics($scheduleProcedure, $procedure['subtopics'] ?? []);
        }

        foreach ($times as $time) {
            $normalized = $this->normalizeTime($time);
            if ($normalized) {
                ScheduleTime::create([
                    'date' => $date,
                    'time' => $normalized,
                ]);
            }
        }
    }

    public function applyToAllDates(string $sourceDate, Carbon $from, Carbon $to): int
    {
        $procedures = ScheduleProcedure::whereDate('date', $sourceDate)
            ->with('subtopics')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (ScheduleProcedure $item) => [
                'name_lv' => $item->name_lv,
                'name_en' => $item->name_en,
                'name_ru' => $item->name_ru,
                'price' => $item->price,
                'subtopics' => $item->subtopics->map(fn ($sub) => [
                    'name_lv' => $sub->name_lv,
                    'name_en' => $sub->name_en,
                    'name_ru' => $sub->name_ru,
                    'price' => $sub->price,
                ])->all(),
            ])
            ->all();

        $times = ScheduleTime::whereDate('date', $sourceDate)
            ->orderBy('time')
            ->get()
            ->map(fn (ScheduleTime $item) => Carbon::parse($item->time)->format('H:i'))
            ->all();

        if (empty($procedures) && empty($times)) {
            $procedures = $this->getProceduresForDate($sourceDate)
                ->map(fn ($item) => [
                    'name_lv' => $item->name_lv,
                    'name_en' => $item->name_en,
                    'name_ru' => $item->name_ru,
                    'price' => $item->price,
                    'subtopics' => collect($item->subtopics)->map(fn ($sub) => [
                        'name_lv' => $sub->name_lv,
                        'name_en' => $sub->name_en,
                        'name_ru' => $sub->name_ru,
                        'price' => $sub->price,
                    ])->all(),
                ])
                ->all();

            $times = $this->getTimesForDate($sourceDate)->all();
        }

        $count = 0;
        for ($cursor = $from->copy(); $cursor->lte($to); $cursor->addDay()) {
            $this->saveForDate($cursor->toDateString(), $procedures, $times);
            $count++;
        }

        return $count;
    }

    public function normalizeTime(?string $time): ?string
    {
        if (!$time) {
            return null;
        }

        $parts = explode(':', trim($time));
        if (count($parts) < 2) {
            return null;
        }

        return sprintf('%02d:%02d', (int) $parts[0], (int) $parts[1]);
    }

    private function saveSubtopics(ScheduleProcedure $scheduleProcedure, array $subtopics): void
    {
        foreach ($subtopics as $subIndex => $subtopic) {
            $nameLv = trim((string) ($subtopic['name_lv'] ?? ''));
            $nameEn = trim((string) ($subtopic['name_en'] ?? ''));
            $nameRu = trim((string) ($subtopic['name_ru'] ?? ''));

            $primaryName = $nameLv ?: $nameEn ?: $nameRu;
            if ($primaryName === '') {
                continue;
            }

            $scheduleProcedure->subtopics()->create([
                'name_lv' => $nameLv ?: $primaryName,
                'name_en' => $nameEn ?: $primaryName,
                'name_ru' => $nameRu ?: $primaryName,
                'price' => $subtopic['price'] ?? 0,
                'sort_order' => $subIndex,
            ]);
        }
    }
}
