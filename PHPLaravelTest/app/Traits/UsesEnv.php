<?php

namespace App\Traits;

trait UsesEnv
{
    /**
     * Get value from .env file
     */
    protected static function getEnvValue(string $key): ?string
    {
        $regex = '/^'.$key.'=(.*)\n$/';

        $handle = fopen(base_path('.env'), 'r');
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle);
                if (preg_match($regex, $buffer)) {
                    return preg_replace($regex, '$1', $buffer);
                }
            }
            fclose($handle);
        }

        return null;
    }

    /**
     * Inject a passed string into .env file, after a line matching the passed regex
     */
    protected static function injectIntoEnvAfterLineMatchingRegEx(string $regex, string $injection): void
    {
        $found = false;
        $before = null;
        $after = null;

        $handle = fopen(self::envPath(), 'r');
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle);
                if ($found) {
                    $after .= $buffer;

                    continue;
                }

                if (preg_match($regex, $buffer)) {
                    $found = true;
                }

                $before .= $buffer;
            }
            fclose($handle);
        }

        $newContent = $before.$injection."\n".$after;

        file_put_contents(self::envPath(), $newContent);
    }

    /**
     * Remove lines from .env file matching passed regex
     */
    protected static function removeLinesFromEnvMatchingRegEx(string $regex): void
    {
        $newContent = '';
        $handle = fopen(self::envPath(), 'r');
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle);
                if (!preg_match($regex, $buffer)) {
                    $newContent .= $buffer;
                }
            }
            fclose($handle);
        }

        file_put_contents(self::envPath(), $newContent);
    }

    /**
     * Get path to .env file
     */
    protected static function envPath(): string
    {
        return base_path('.env');
    }
}
