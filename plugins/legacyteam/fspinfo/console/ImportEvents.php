<?php

namespace Legacyteam\FspInfo\Console;

use Illuminate\Console\Command;
use Legacyteam\FspInfo\Models\Event;
use File;

class ImportEvents extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'fspinfo:importevents';

    /**
     * @var string The console command description.
     */
    protected $description = 'Imports events from a JSON file into the database.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $filePath = base_path('plugins/legacyteam/fspinfo/data/events.json'); // Путь к файлу JSON

        if (!File::exists($filePath)) {
            $this->error("File not found: $filePath");
            return;
        }

        $jsonData = File::get($filePath);
        $events = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Invalid JSON: " . json_last_error_msg());
            return;
        }

        foreach ($events as $eventData) {
            try {
                $event = new Event();
                $event->name = $eventData['title'] ?? null;
                $event->agent_id = 2;
                $event->description = $eventData['disciplines'] ?? null;
                $event->date_from = isset($eventData['start_date']) ? date('Y-m-d', strtotime($eventData['start_date'])) : null;
                $event->date_to = isset($eventData['end_date']) ? date('Y-m-d', strtotime($eventData['end_date'])) : null;
                $event->address = $eventData['location'] ?? null;
                $event->participants = $eventData['participants'] ?? null;
                $event->priority = 0; // Пример значения по умолчанию
                $event->status = 1; // Пример значения по умолчанию
                $event->save();

                $this->info("Imported event: {$event->name}");
            } catch (\Exception $e) {
                $this->error("Error importing event: {$eventData['title']} - {$e->getMessage()}");
            }
        }

        $this->info("Import completed.");
    }
}
