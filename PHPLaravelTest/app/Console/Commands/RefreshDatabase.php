<?php

namespace App\Console\Commands;

use App\App;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Artisan;

class RefreshDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:refresh
        {--is-migrated}
        {--is-before-application-destroyed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh database. This is intended to be called by Gateway, as part of the automated E2E tests tooling.';

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
     * @return void
     */
    public function handle()
    {
        RefreshDatabaseState::$migrated = false;
        if ($this->option('is-migrated')) {
            RefreshDatabaseState::$migrated = true;
        }

        if ($this->option('is-before-application-destroyed')) {
            $this->beforeApplicationDestroyed();

            exit(0);
        }

        $this->refreshDatabase();
    }

    /**
     * Define hooks to migrate the database before and after each test.
     */
    public function refreshDatabase(): void
    {
        $this->usingInMemoryDatabase()
            ? $this->refreshInMemoryDatabase()
            : $this->refreshTestDatabase();
    }

    /**
     * Determine if an in-memory database is being used.
     */
    protected function usingInMemoryDatabase(): bool
    {
        $default = config('database.default');

        return config("database.connections.$default.database") === ':memory:';
    }

    /**
     * Refresh the in-memory database.
     */
    protected function refreshInMemoryDatabase(): void
    {
        Artisan::call('migrate');
    }

    /**
     * Refresh a conventional test database.
     */
    protected function refreshTestDatabase(): void
    {
        if (!RefreshDatabaseState::$migrated) {
            Artisan::call('migrate:fresh', [
                '--drop-views' => $this->shouldDropViews(),
                '--drop-types' => $this->shouldDropTypes(),
            ]);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }

    /**
     * Begin a database transaction on the testing database.
     */
    public function beginDatabaseTransaction(): void
    {
        $database = App::make('db');

        foreach ($this->connectionsToTransact() as $name) {
            $connection = $database->connection($name);
            $dispatcher = $connection->getEventDispatcher();

            $connection->unsetEventDispatcher();
            $connection->beginTransaction();
            $connection->setEventDispatcher($dispatcher);
        }
    }

    /**
     * The database connections that should have transactions.
     */
    protected function connectionsToTransact(): array
    {
        return property_exists($this, 'connectionsToTransact')
            ? $this->connectionsToTransact : [null];
    }

    /**
     * Determine if views should be dropped when refreshing the database.
     */
    protected function shouldDropViews(): bool
    {
        return property_exists($this, 'dropViews')
            ? $this->dropViews : false;
    }

    /**
     * Determine if types should be dropped when refreshing the database.
     */
    protected function shouldDropTypes(): bool
    {
        return property_exists($this, 'dropTypes')
            ? $this->dropTypes : false;
    }

    /**
     * Before application destroyed
     */
    public function beforeApplicationDestroyed(): void
    {
        $database = App::make('db');

        foreach ($this->connectionsToTransact() as $name) {
            $connection = $database->connection($name);
            $dispatcher = $connection->getEventDispatcher();

            $connection->unsetEventDispatcher();
            $connection->rollback();
            $connection->setEventDispatcher($dispatcher);
            $connection->disconnect();
        }
    }
}
