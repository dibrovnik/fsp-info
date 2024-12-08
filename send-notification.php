<?php

// URL API
$apiUrl = 'http://127.0.0.1:2250/send-notification';

// Данные для отправки
$data = [
    'platform_user_id' => '1', // ID пользователя на платформе
    'message' => 'Ваше уведомление!' // Текст уведомления
];

// Инициализация cURL
$ch = curl_init($apiUrl);

// Настройка cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Выполнение запроса и получение результата
$response = curl_exec($ch);

// Проверка на ошибки
if ($response === false) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo 'Response: ' . $response;
}

// Закрытие cURL
curl_close($ch);
?>
