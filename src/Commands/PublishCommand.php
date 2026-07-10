<?php

namespace wsydney76\craft6blade\Commands;

use CraftCms\Cms\Console\CraftCommand;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use function app_path;
use function file_exists;
use function file_get_contents;

#[Signature('c6b:publish {function? : The function to publish} 
    {--remove : Remove the published function instead of publishing it} 
    {--noPrefix : Publish the function without the c6b_ prefix}')]
#[Description('Publish a Craft6 Blade function from the plugin\'s Publishables.php to app/c6b/functions.php')]
class PublishCommand extends Command implements PromptsForMissingInput
{
    use CraftCommand;

    public function handle(): int
    {
        $function = $this->argument('function');

        if (!$function) {
            $sourcePath = __DIR__ . "/../../Helpers/Publishables.php";
            $source = file_get_contents($sourcePath);
            preg_match_all('/^\/\/ === START (c6b_\S+)/m', $source, $matches);
            $available = array_map(fn($f) => preg_replace('/^c6b_/', '', $f), $matches[1] ?? []);

            if (empty($available)) {
                $this->components->error("No functions found in Publishables.php.");
                return self::FAILURE;
            }

            $function = $this->anticipate('Function name (type or select)', $available);
        }

        if (!str_starts_with($function, 'c6b_')) {
            $function = 'c6b_' . $function;
        }

        $noPrefix = $this->option('noPrefix');

        // Source markers always match the (prefixed) name in Publishables.php.
        $sourceStartMarker = "// === START $function\n";
        $sourceEndMarker = "// === END $function\n";

        // The published name may have the c6b_ prefix stripped.
        $publishedFunction = $noPrefix ? preg_replace('/^c6b_/', '', $function) : $function;

        // Match the exact function name by requiring an end-of-line boundary
        // after the name, so "c6b_t" does not match "c6b_tag".
        $startMarker = "// === START $publishedFunction\n";
        $endMarker = "// === END $publishedFunction\n";

        if ($this->option('remove')) {
            return $this->remove($publishedFunction, $startMarker, $endMarker);
        }

        $this->components->info("Publishing Craft6 Blade function $function...");

        // Locate the source function in Publishables.php
        $sourcePath = __DIR__ . "/../../Helpers/Publishables.php";
        $source = file_get_contents($sourcePath);

        $startPos = strpos($source, $sourceStartMarker);
        if ($startPos === false) {
            $this->components->error("Function $function was not found in Publishables.php.");
            return self::FAILURE;
        }

        $endPos = strpos($source, $sourceEndMarker, $startPos);
        if ($endPos === false) {
            $this->components->error("End marker for function $function was not found in Publishables.php.");
            return self::FAILURE;
        }

        // Extract the function block, including the START and END marker comments.
        $blockEnd = $endPos + strlen($sourceEndMarker);
        $functionCode = substr($source, $startPos, $blockEnd - $startPos);

        // Optionally strip the c6b_ prefix from the marker comments and the
        // function declaration (internal calls are left untouched).
        if ($noPrefix) {
            $functionCode = str_replace(
                [$sourceStartMarker, $sourceEndMarker, "function $function("],
                [$startMarker, $endMarker, "function $publishedFunction("],
                $functionCode,
            );
        }

        // Ensure the target file exists (seeded with the header).
        $targetPath = app_path("c6b/functions.php");
        if (!file_exists($targetPath)) {
            if (!file_exists(app_path("c6b"))) {
                mkdir(app_path("c6b"), 0755, true);
            }
            \Safe\file_put_contents($targetPath, file_get_contents(__DIR__ . "/../../Helpers/Header.php"));
        }

        // Reject if the function is already published.
        $target = file_get_contents($targetPath);
        if (strpos($target, $startMarker) !== false) {
            $this->components->error("Function $function is already present in {$targetPath}.");
            return self::FAILURE;
        }

        // Append the function block to the target file.
        \Safe\file_put_contents($targetPath, rtrim($target) . "\n\n" . $functionCode . "\n");

        $this->components->success("Function $function published to {$targetPath} as $publishedFunction.");

        return self::SUCCESS;
    }

    private function remove(string $function, string $startMarker, string $endMarker): int
    {
        $this->components->info("Removing Craft6 Blade function $function...");

        $targetPath = app_path("c6b/functions.php");
        if (!file_exists($targetPath)) {
            $this->components->error("Function $function is not published (target file does not exist).");
            return self::FAILURE;
        }

        $target = file_get_contents($targetPath);

        $startPos = strpos($target, $startMarker);
        if ($startPos === false) {
            $this->components->error("Function $function is not present in {$targetPath}.");
            return self::FAILURE;
        }

        $endPos = strpos($target, $endMarker, $startPos);
        if ($endPos === false) {
            $this->components->error("End marker for function $function was not found in {$targetPath}.");
            return self::FAILURE;
        }

        // Remove the function block, including the START and END marker comments.
        $blockEnd = $endPos + strlen($endMarker);
        $updated = substr($target, 0, $startPos) . substr($target, $blockEnd);

        // Collapse extra blank lines left behind by the removal.
        $updated = rtrim($updated) . "\n";

        \Safe\file_put_contents($targetPath, $updated);

        $this->components->success("Function $function removed from {$targetPath}.");

        return self::SUCCESS;
    }

}