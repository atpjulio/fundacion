<?php

namespace App\Console\Commands;

use App\City as OldCity;
use App\Models\Shared\City;
use Illuminate\Console\Command;

class MigrateCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate cities';

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
     * @return int
     */
    public function handle()
    {
        $oldCities = OldCity::with('state')->get();

        if ($oldCities->isEmpty()) {
            $this->warn('Sin data para migrar');
        }

        $oldCities->each(function($oldCity) {
            City::create([
                'state_id' => $oldCity->state->id,
                'code' => $oldCity->code,
                'name' => $oldCity->name,
            ]);
        });

        $this->info('La migración ha terminado');
    }
}
