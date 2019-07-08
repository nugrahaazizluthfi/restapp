<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitialSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet-sederhana:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial Setup';

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
        //
        $this->fire();
    }

    public function fire()
    {
        \Artisan::call('key:generate', [], $this->getOutput());
        \Artisan::call('migrate:refresh', [], $this->getOutput());
        \Artisan::call('passport:install', ['--force' => 'default'], $this->getOutput());
        \Artisan::call('db:seed', [], $this->getOutput());
    }
}
