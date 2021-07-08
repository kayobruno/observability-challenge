<?php

namespace App\Console\Commands;

use App\Models\Alert;
use Illuminate\Console\Command;

class ImportAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realiza a importação de alertas para o banco de dados utilizando um arquivo CSV';

    /**
     * @var Alert
     */
    protected $alertRepository;

    /**
     * Create a new command instance.
     *
     * @param Alert $alertRepository
     */
    public function __construct(Alert $alertRepository)
    {
        parent::__construct();
        $this->alertRepository = $alertRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = env('ALERTS_IMPORT_FILE_PATH');
        if (!file_exists($file)) {
            return false;
        }

        if (!$this->alertRepository->all()->count()) {
            $header = null;
            if (($handle = fopen($file, 'r')) !== false) {
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (!$header) {
                        $header = $row;
                        continue;
                    }

                    $fields = $this->alertRepository->getFillable();
                    if (!array_search('enabled', $header)) {
                        $enabled = array_search('enabled', $fields);
                        unset($fields[$enabled]);
                    }

                    $data = array_combine($fields, $row);
                    $this->alertRepository->create($data);
                }
                fclose($handle);
            }

            $this->info('Importação finalizada');
        }
    }
}
