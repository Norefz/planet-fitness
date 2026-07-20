<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MealLog;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MealLogController extends Controller
{
    /**
     * Urutan tampil kategori makan — dipakai untuk mengurutkan daftar log
     * (bukan berdasarkan jam input, tapi berdasarkan waktu makan yang logis).
     */
    private const CATEGORY_ORDER = ['breakfast' => 0, 'lunch' => 1, 'dinner' => 2, 'snack' => 3];

    /**
     * GET /log-nutrisi — satu route publik untuk guest (preview) & member (data asli),
     * mengikuti pola yang sudah dipakai navbar & ProgramController.
     */
    public function index(Request $request): View
    {
        $date   = $this->resolveDate($request->query('date'));
        $member = Auth::check() ? Auth::user()->member : null;

        $data = $member ? $this->memberViewData($member, $date) : $this->guestViewData($date);

        return view('member.log-nutrisi', array_merge($data, [
            'dateLabel' => $this->formatIndonesianDate($date),
            'isToday'   => $date->isToday(),
            'prevDate'  => $date->copy()->subDay()->toDateString(),
            'nextDate'  => $date->isToday() ? null : $date->copy()->addDay()->toDateString(),
        ]));
    }

    /**
     * POST /member/log-nutrisi — tambah satu entri makanan untuk member yang login.
     */
    public function store(Request $request): RedirectResponse
    {
        $member = Auth::user()->member;
        abort_unless($member, 403, 'Fitur pencatatan hanya tersedia untuk akun Member.');

        $validated = $request->validate([
            'food_name' => ['required', 'string', 'max:150'],
            'category'  => ['required', 'in:breakfast,lunch,dinner,snack'],
            'carbs_g'   => ['required', 'integer', 'min:0', 'max:500'],
            'protein_g' => ['required', 'integer', 'min:0', 'max:500'],
            'fat_g'     => ['required', 'integer', 'min:0', 'max:500'],
            'log_date'  => ['nullable', 'date'],
        ]);

        $logDate = $this->resolveDate($validated['log_date'] ?? null);

        // Kalori tidak pernah diinput manual — selalu dihitung server-side dari
        // makro (4 kkal/g karbo & protein, 9 kkal/g lemak) supaya konsisten
        // dengan angka yang ditampilkan di form dan tidak bisa dimanipulasi client.
        $calories = (int) round(
            ($validated['carbs_g'] * 4) + ($validated['protein_g'] * 4) + ($validated['fat_g'] * 9)
        );

        $beforeTotal = (int) MealLog::where('member_id', $member->id)->forDate($logDate)->sum('calories');

        $log = MealLog::create([
            'member_id' => $member->id,
            'food_name' => trim($validated['food_name']),
            'category'  => $validated['category'],
            'calories'  => $calories,
            'carbs_g'   => $validated['carbs_g'],
            'protein_g' => $validated['protein_g'],
            'fat_g'     => $validated['fat_g'],
            'log_date'  => $logDate,
        ]);

        $afterTotal        = $beforeTotal + $log->calories;
        $justReachedTarget = $beforeTotal < $member->daily_calorie_target && $afterTotal >= $member->daily_calorie_target;

        $redirect = redirect()->route('member.log-nutrisi', $this->dateQuery($logDate));

        if ($justReachedTarget) {
            return $redirect->with('celebrate', '"' . $log->food_name . '" tersimpan — target kalori hari ini tercapai!');
        }

        return $redirect->with('success', '"' . $log->food_name . '" ditambahkan ke ' . $log->categoryLabel() . '.');
    }

    /**
     * DELETE /member/log-nutrisi/{mealLog} — hapus satu entri milik member yang login.
     */
    public function destroy(MealLog $mealLog): RedirectResponse
    {
        $member = Auth::user()->member;
        abort_unless($member && $mealLog->member_id === $member->id, 403, 'Kamu tidak memiliki akses ke log ini.');

        $date = $mealLog->log_date;
        $name = $mealLog->food_name;
        $mealLog->delete();

        return redirect()
            ->route('member.log-nutrisi', $this->dateQuery($date))
            ->with('success', '"' . $name . '" dihapus dari log nutrisi.');
    }

    // ─── Data assembly ──────────────────────────────────────────────────────

    private function memberViewData(Member $member, Carbon $date): array
    {
        $logs = MealLog::where('member_id', $member->id)
            ->forDate($date)
            ->orderBy('created_at')
            ->get()
            ->sortBy(fn (MealLog $log) => self::CATEGORY_ORDER[$log->category] ?? 99)
            ->values();

        $totals = $this->sumTotals($logs);

        $target = [
            'calories'  => $member->daily_calorie_target,
            'carbs_g'   => $member->daily_carbs_target_g,
            'protein_g' => $member->daily_protein_target_g,
            'fat_g'     => $member->daily_fat_target_g,
        ];

        $recentFoods = MealLog::where('member_id', $member->id)
            ->latest('created_at')
            ->limit(40)
            ->get(['food_name', 'category', 'calories', 'carbs_g', 'protein_g', 'fat_g'])
            ->unique('food_name')
            ->take(5)
            ->values();

        return array_merge(
            $this->computeSummary($totals, $target, $logs),
            [
                'logs'        => $logs,
                'recentFoods' => $recentFoods,
                'streak'      => $this->calculateStreak($member->id, $date),
                'date'        => $date,
                'canLog'      => true,
                'authState'   => 'member',
            ]
        );
    }

    private function guestViewData(Carbon $date): array
    {
        $demo = [
            ['food_name' => 'Oatmeal + Pisang',          'category' => 'breakfast', 'calories' => 310, 'carbs_g' => 48, 'protein_g' => 8,  'fat_g' => 4],
            ['food_name' => 'Nasi + Ayam Bakar',          'category' => 'lunch',     'calories' => 485, 'carbs_g' => 65, 'protein_g' => 32, 'fat_g' => 12],
            ['food_name' => 'Salad Tuna + Roti Gandum',   'category' => 'dinner',    'calories' => 290, 'carbs_g' => 28, 'protein_g' => 22, 'fat_g' => 9],
            ['food_name' => 'Protein Shake',              'category' => 'snack',     'calories' => 165, 'carbs_g' => 10, 'protein_g' => 25, 'fat_g' => 3],
        ];

        $logs = collect($demo)->map(
            fn (array $row) => new MealLog($row + ['log_date' => $date->toDateString()])
        );

        $totals = $this->sumTotals($logs);

        $target = [
            'calories'  => 1800,
            'carbs_g'   => 220,
            'protein_g' => 100,
            'fat_g'     => 60,
        ];

        return array_merge(
            $this->computeSummary($totals, $target, $logs),
            [
                'logs'        => $logs,
                'recentFoods' => collect(),
                'streak'      => 0,
                'date'        => $date,
                'canLog'      => false,
                'authState'   => Auth::check() ? 'other' : 'guest',
            ]
        );
    }

    /**
     * Angka & narasi ringkasan yang sama persis dipakai baik untuk guest (data
     * demo) maupun member (data asli) — supaya Blade tidak perlu tahu bedanya.
     */
    private function computeSummary(array $totals, array $target, Collection $logs): array
    {
        $consumedPct = $target['calories'] > 0 ? ($totals['calories'] / $target['calories']) * 100 : 0;
        $remaining   = $target['calories'] - $totals['calories'];

        $ringStatus = match (true) {
            $consumedPct >= 100 => 'over',
            $consumedPct >= 90  => 'near',
            default             => 'good',
        };

        $macroPct = [
            'carbs_g'   => $target['carbs_g']   > 0 ? ($totals['carbs_g']   / $target['carbs_g'])   * 100 : 0,
            'protein_g' => $target['protein_g'] > 0 ? ($totals['protein_g'] / $target['protein_g']) * 100 : 0,
            'fat_g'     => $target['fat_g']     > 0 ? ($totals['fat_g']     / $target['fat_g'])     * 100 : 0,
        ];

        $topCategory = $logs->isNotEmpty()
            ? $logs->groupBy('category')->map(fn ($group) => $group->sum('calories'))->sortDesc()->keys()->first()
            : null;

        $weakestMacro = $logs->isNotEmpty() ? collect($macroPct)->sort()->keys()->first() : null;

        return [
            'totals'         => $totals,
            'target'         => $target,
            'consumedPct'    => $consumedPct,
            'remaining'      => $remaining,
            'ringStatus'     => $ringStatus,
            'macroPct'       => $macroPct,
            'topCategory'    => $topCategory,
            'topCategoryLabel' => $topCategory ? (MealLog::categoryOptions()[$topCategory] ?? null) : null,
            'insight'        => $this->buildInsight($remaining, $weakestMacro, $macroPct, $logs->isNotEmpty()),
        ];
    }

    private function buildInsight(int $remaining, ?string $weakestMacro, array $macroPct, bool $hasLogs): ?string
    {
        if (! $hasLogs) {
            return null;
        }

        if ($remaining < 0) {
            return 'Kamu sudah melewati target kalori harian sekitar ' . number_format(abs($remaining)) . ' kkal. Coba porsi yang lebih ringan untuk sisa hari ini.';
        }

        if ($weakestMacro && $macroPct[$weakestMacro] < 60) {
            return 'Asupan ' . $this->macroLabel($weakestMacro) . ' baru ' . (int) $macroPct[$weakestMacro] . '% dari target — coba tambahkan ' . $this->macroFoodIdeas($weakestMacro) . '.';
        }

        if ($remaining > 0) {
            return 'Sisa ' . number_format($remaining) . ' kkal lagi untuk hari ini — kamu ada di jalur yang baik.';
        }

        return 'Target kalori hari ini tercapai pas! Kerja bagus.';
    }

    private function macroLabel(string $macro): string
    {
        return match ($macro) {
            'carbs_g'   => 'karbohidrat',
            'protein_g' => 'protein',
            'fat_g'     => 'lemak sehat',
            default     => $macro,
        };
    }

    private function macroFoodIdeas(string $macro): string
    {
        return match ($macro) {
            'carbs_g'   => 'nasi merah, oat, atau ubi',
            'protein_g' => 'dada ayam, telur, atau tahu/tempe',
            'fat_g'     => 'alpukat, kacang almond, atau minyak zaitun',
            default     => 'makanan bergizi seimbang',
        };
    }

    private function sumTotals(Collection $logs): array
    {
        return [
            'calories'  => (int) $logs->sum('calories'),
            'carbs_g'   => (int) $logs->sum('carbs_g'),
            'protein_g' => (int) $logs->sum('protein_g'),
            'fat_g'     => (int) $logs->sum('fat_g'),
        ];
    }

    /**
     * Streak = jumlah hari berturut-turut member mencatat setidaknya satu
     * makanan, dihitung mundur dari tanggal yang sedang dilihat. Jika hari ini
     * belum dicatat, streak tetap ditampilkan dari kemarin (belum "putus"
     * karena harinya belum selesai) untuk mendorong pengguna mencatat.
     */
    private function calculateStreak(string $memberId, Carbon $referenceDate): int
    {
        $loggedDates = MealLog::where('member_id', $memberId)
            ->where('log_date', '<=', $referenceDate->toDateString())
            ->distinct()
            ->pluck('log_date')
            ->map(fn ($value) => Carbon::parse($value)->toDateString())
            ->flip();

        $cursor = $loggedDates->has($referenceDate->toDateString())
            ? $referenceDate->copy()
            : $referenceDate->copy()->subDay();

        $streak = 0;
        while ($loggedDates->has($cursor->toDateString())) {
            $streak++;
            $cursor->subDay();
        }

        return $streak;
    }

    private function resolveDate(?string $raw): Carbon
    {
        $today = Carbon::today();

        if (! $raw) {
            return $today;
        }

        try {
            $date = Carbon::parse($raw)->startOfDay();
        } catch (\Throwable $e) {
            return $today;
        }

        return $date->greaterThan($today) ? $today : $date;
    }

    private function dateQuery(Carbon $date): array
    {
        return $date->isToday() ? [] : ['date' => $date->toDateString()];
    }

    /**
     * Format manual (bukan Carbon::translatedFormat) supaya tidak bergantung pada
     * locale aplikasi yang mungkin belum diset ke 'id'.
     */
    private function formatIndonesianDate(Carbon $date): string
    {
        static $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        return $date->day . ' ' . $months[$date->month - 1] . ' ' . $date->year;
    }
}
