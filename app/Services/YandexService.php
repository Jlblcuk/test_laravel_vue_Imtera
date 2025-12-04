<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class YandexService
{
    public function parse(string $url)
    {
        try {
            Log::info('YandexService: Starting parse for URL: ' . $url);

            // Извлекаем Id из URL
            $businessId = $this->extractBusinessId($url);

            if (!$businessId) {
                Log::warning('YandexService: Could not extract businessId from URL');
                return null;
            }

            Log::info('YandexService: Extracted businessId: ' . $businessId);

            //Виджет Яндекса
            $widgetUrl = "https://yandex.ru/maps-reviews-widget/{$businessId}?comments";
            Log::info('YandexService: Using widget URL: ' . $widgetUrl);

            $html = $this->fetchPageHtml($widgetUrl);

            if (!$html) {
                Log::warning('YandexService: Failed to fetch widget HTML');
                return null;
            }

            $data = $this->parseWidgetHtml($html);

            if ($data) {
                Log::info('YandexService: Successfully extracted data from widget');
                return $data;
            }

            Log::warning('YandexService: Widget parsing failed');
            return null;

        } catch (\Exception $e) {
            Log::error('YandexService error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    private function extractBusinessId($url)
    {
        if (preg_match('/\/(\d{10,})/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function parseWidgetHtml($html)
    {
        try {
            $crawler = new Crawler($html);

            // Рейтинг
            $rating = null;
            $ratingNode = $crawler->filter('.mini-badge__stars-count')->first();
            if ($ratingNode->count() > 0) {
                $ratingText = $ratingNode->text();
                $rating = floatval(str_replace(',', '.', $ratingText));
            }

            // Отзывы
            $reviewsCount = 0;
            $countNode = $crawler->filter('.mini-badge__rating')->first();
            if ($countNode->count() > 0) {
                $countText = $countNode->text();
                if (preg_match('/(\d+)\s+отзыв/', $countText, $matches)) {
                    $reviewsCount = intval($matches[1]);
                }
            }

            // Список отзывов
            $reviews = [];
            $commentNodes = $crawler->filter('.comment');

            foreach ($commentNodes as $commentNode) {
                $commentCrawler = new Crawler($commentNode);

                // Имя автора
                $authorNode = $commentCrawler->filter('.comment__name')->first();
                $author = $authorNode->count() > 0 ? $authorNode->text() : 'Аноним';

                // Дата отзыва
                $dateNode = $commentCrawler->filter('.comment__date')->first();
                $dateText = $dateNode->count() > 0 ? $dateNode->text() : '';
                $date = $this->transformDate($dateText);

                // Рейтинг отзыва
                $starsNode = $commentCrawler->filter('.comment__stars .stars-list__star');
                $reviewRating = $starsNode->count();

                // Текст отзыва
                $textNode = $commentCrawler->filter('.comment__text')->first();
                $text = $textNode->count() > 0 ? trim($textNode->text()) : '';

                $reviews[] = [
                    'author' => $author,
                    'text' => $text,
                    'rating' => $reviewRating,
                    'date' => $date
                ];
            }

            if ($rating || $reviewsCount || count($reviews) > 0) {
                return [
                    'rating' => $rating ?? 0,
                    'reviews_count' => $reviewsCount,
                    'reviews' => array_slice($reviews, 0, 10)
                ];
            }

        } catch (\Exception $e) {
            Log::error('YandexService: Widget HTML parsing failed: ' . $e->getMessage());
        }

        return null;
    }

    private function transformDate($dateText)
    {
        if (empty($dateText)) {
            return date('Y-m-d');
        }

        $months = [
            'января' => '01', 'февраля' => '02', 'марта' => '03',
            'апреля' => '04', 'мая' => '05', 'июня' => '06',
            'июля' => '07', 'августа' => '08', 'сентября' => '09',
            'октября' => '10', 'ноября' => '11', 'декабря' => '12'
        ];

        foreach ($months as $monthName => $monthNum) {
            if (stripos($dateText, $monthName) !== false) {
                if (preg_match('/(\d+)\s+' . $monthName . '/ui', $dateText, $matches)) {
                    $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                    $year = date('Y');

                    return "{$year}-{$monthNum}-{$day}";
                }
            }
        }

        return date('Y-m-d');
    }

    private function fetchPageHtml($url)
    {
        try {
            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ])->timeout(15)->get($url);

            if ($response->successful()) {
                return $response->body();
            }

            Log::warning('YandexService: HTTP request failed with status: ' . $response->status());
            return null;

        } catch (\Exception $e) {
            Log::error('YandexService: Failed to fetch HTML: ' . $e->getMessage());
            return null;
        }
    }
}
