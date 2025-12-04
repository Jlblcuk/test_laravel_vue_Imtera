<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\YandexService;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    protected $yandexService;

    public function __construct(YandexService $yandexService)
    {
        $this->yandexService = $yandexService;
    }

    public function getSettings()
    {
        $setting = Setting::first();
        return response()->json($setting ?? []);
    }

    public function updateSettings(Request $request, YandexService $yandexService)
    {
        $request->validate([
            'yandex_url' => 'required|url'
        ]);

        $data = $yandexService->parse($request->yandex_url);

        if (!$data) {
            return response()->json([
                'message' => 'Не удалось получить данные. Проверьте ссылку'
            ], 422);
        }

        // Сохраняем настройки
        $setting = Setting::firstOrNew();
        $setting->yandex_url = $request->yandex_url;
        $setting->rating = $data['rating'];
        $setting->reviews_count = $data['reviews_count'];
        $setting->cached_reviews = $data['reviews'];
        $setting->save();

        return response()->json(['message' => 'Settings updated', 'data' => $setting]);
    }

    public function getReviews()
    {
        $setting = Setting::first();

        if (!$setting) {
            return response()->json(['reviews' => []]);
        }

        return response()->json([
            'rating' => $setting->rating,
            'reviews_count' => $setting->reviews_count,
            'reviews' => $setting->cached_reviews
        ]);
    }
}
