<?php

use App\Traits\UsesEnv;
use Illuminate\Database\Migrations\Migration;

class GatewayDatabaseConnection extends Migration
{
    use UsesEnv;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('app.env') === 'local' || config('app.env') === 'dusk') {
            return;
        }

        $host = self::getEnvValue('DB_HOST');
        $port = self::getEnvValue('DB_PORT');
        $database = self::getEnvValue('DB_DATABASE');
        $username = self::getEnvValue('DB_USERNAME');
        $password = self::getEnvValue('DB_PASSWORD');

        $newDatabase = config('app.env') === 'production' ? 'gateway_brownbear_com' : preg_replace('/bbc_web_(.*)$/', 'bbc_$1', $database);

        $newBlock = "\n".
            "GATEWAY_DB_CONNECTION=mysql\n".
            "GATEWAY_DB_HOST=".$host."\n".
            "GATEWAY_DB_PORT=".$port."\n".
            "GATEWAY_DB_DATABASE=".$newDatabase."\n".
            "GATEWAY_DB_USERNAME=".$username."\n".
            "GATEWAY_DB_PASSWORD=".$password;

        self::injectIntoEnvAfterLineMatchingRegEx('/^DB_PASSWORD=/', $newBlock);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('app.env') === 'local' || config('app.env') === 'dusk') {
            return;
        }

        self::removeLinesFromEnvMatchingRegEx('/^GATEWAY_DB_/');
    }
}
