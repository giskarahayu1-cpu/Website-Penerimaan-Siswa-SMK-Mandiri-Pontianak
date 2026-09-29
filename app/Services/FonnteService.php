<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $token;
    protected string $url;

    public function __construct()
    {
        $this->token = config('services.fonnte.token') ?? '';
        $this->url = config('services.fonnte.url') ?? 'https://api.fonnte.com/send';
    }

    /**
     * Send a WhatsApp message.
     *
     * @param string $to
     * @param string $message
     * @return bool
     */
    public function sendMessage(string $to, string $message): bool
    {
        if (empty($this->token)) {
            Log::warning('Fonnte API Token is not configured. WhatsApp message not sent.');
            return false;
        }

        $formattedTo = $this->formatPhoneNumber($to);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->post($this->url, [
                'target' => $formattedTo,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp message successfully sent to {$formattedTo} via Fonnte.");
                return true;
            }

            Log::group("Failed to send WhatsApp message via Fonnte", function () use ($formattedTo, $response) {
                Log::error("Target: {$formattedTo}");
                Log::error("Status: " . $response->status());
                Log::error("Error: " . $response->body());
            });
            return false;
        } catch (\Exception $e) {
            Log::error("Fonnte API request failed for target {$formattedTo}. Message: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format/normalize phone numbers to international standard (628...)
     *
     * @param string $number
     * @return string
     */
    public function formatPhoneNumber(string $number): string
    {
        // Remove non-digit characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // Convert leading '0' to '62'
        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        // Return number if it already starts with '62'
        if (str_starts_with($number, '62')) {
            return $number;
        }

        // Fallback for number starting directly with 8...
        if (strlen($number) >= 9) {
            return '62' . $number;
        }

        return $number;
    }
}
