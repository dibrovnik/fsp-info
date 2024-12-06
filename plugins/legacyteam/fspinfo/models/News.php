<?php namespace Legacyteam\FspInfo\Models;

use Model;

/**
 * Model
 */
class News extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'legacyteam_fspinfo_news';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public $belongsTo = [
        'region' => ['Legacyteam\FspInfo\Models\Region', 'key' => 'region_id'],
    ];

    public $attachOne = [
        'avatar' => \System\Models\File::class
    ];

    public static function createOrUpdateNews(array $data, $newsId = null)
    {
        $news = $newsId ? static::find($newsId) : new static();

        // Если запись для обновления не найдена, бросаем исключение
        if ($newsId && !$news) {
            throw new \Exception("Новость с ID {$newsId} не найдена.");
        }

        // Валидируем данные
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'link' => 'nullable|url|max:255',
            'region_id' => 'required|integer|exists:legacyteam_fspinfo_regions,id',
            'short_description' => 'nullable|string|max:500',
        ];

        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new \ValidationException($validation);
        }

        // Заполняем данные
        $news->title = $data['title'];
        $news->content = $data['content'];
        $news->link = $data['link'] ?? null;
        $news->region_id = $data['region_id'];
        $news->short_description = $data['short_description'] ?? null;

        // Сохраняем запись
        $news->save();

        return $news;
    }


}
