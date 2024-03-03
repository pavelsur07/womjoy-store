<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Unisender;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UnisenderService
{
    private string $apiKey;

    public function setCredential(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function sendEmailWithPromoCode(string $email, int $templateId, string $promocode): void
    {
        $client = new Client([
            'base_uri' => 'https://api.unisender.com/ru/api/',
        ]);

        try {
            $response = $client->post('sendEmail', [
                'form_params' => [
                    'api_key' => $this->apiKey,
                    'format' => 'json',
                    'email' => $email, // Замените на адрес получателя
                    'template' => 218679874, // $templateId,
                    'params[PROMOCODE]' => $promocode,
                ],
            ]);
        } catch (Exception $e) {
            // Обработка исключений
            // echo 'Произошла ошибка: ' . $e->getMessage();
        } catch (GuzzleException $e) {
            // Обработка исключений
        }
    }
}
