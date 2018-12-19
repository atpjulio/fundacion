<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Patient;

class ProcessTxt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:txt {fileName} {--eps=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process big txt patient files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->argument('fileName')) {
          echo "Nombre de archivo inválido";
          return;
        }

        if (!$this->option('eps')) {
          echo "Código EPS inválido";
          return;
        }

        Patient::processMassivePatientFile($this->option('eps'), $this->argument('fileName'));
    }
}
