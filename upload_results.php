<?php
use Maatwebsite\Excel\Facades\Excel;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['results_file'])) {
    $file = $_FILES['results_file'];
    $path = $file['tmp_name']; // Путь к загруженному файлу

    try {
        $data = Excel::toArray([], $path);

        foreach ($data[0] as $index => $row) {
            if ($index === 0) continue; // Пропускаем заголовок

            $resultData = [
                'event_id' => $row[0] ?? null,
                'winner_name' => $row[1] ?? null,
                'contact_info' => $row[2] ?? null,
                'position' => $row[3] ?? null,
                'score' => $row[4] ?? null,
            ];

            // Вызов метода для сохранения данных (например, через модель Result)
            // Result::createOrUpdateResult($resultData);
        }

        echo 'Результаты успешно загружены.';
    } catch (\Exception $e) {
        echo 'Ошибка при обработке файла: ' . $e->getMessage();
    }
} else {
    echo 'Файл не был загружен.';
}
