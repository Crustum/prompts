<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Collection\Collection;
use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\DataTablePrompt;
use Override;

/**
 * Cake Console helper for DataTablePrompt.
 *
 * Expected `$args` keys:
 * - `headers` (array|\Cake\Collection\Collection) — table headers, or rows when `rows` is null
 * - `rows` (array|\Cake\Collection\Collection|null) — table rows
 * - `scroll` (int) — visible row count before scrolling
 * - `label` (string) — prompt label
 * - `hint` (string) — optional hint shown while active
 * - `required` (bool|string) — required flag or message
 * - `validate` (mixed) — validator callback or rules
 * - `transform` (\Closure|null) — value transform callback
 * - `filter` (\Closure|null) — optional custom row filter for search
 */
class DataTableHelper extends Helper
{
    /**
     * Build and run a DataTablePrompt.
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return mixed The selected row value
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        $headers = $args['headers'] ?? [];
        if ($headers instanceof Collection) {
            $headers = $headers->toArray();
        }

        if (!is_array($headers)) {
            $headers = [];
        }

        $rows = $args['rows'] ?? null;
        if ($rows instanceof Collection) {
            $rows = $rows->toArray();
        }

        if ($rows !== null && !is_array($rows)) {
            $rows = null;
        }

        return (new DataTablePrompt(
            headers: $this->normalizeHeaderList($headers),
            rows: $rows === null ? null : $this->normalizeKeyedRowList($rows),
            scroll: (int)($args['scroll'] ?? 10),
            label: (string)($args['label'] ?? ''),
            hint: (string)($args['hint'] ?? ''),
            required: $args['required'] ?? false,
            validate: $args['validate'] ?? null,
            transform: ($args['transform'] ?? null) instanceof Closure ? $args['transform'] : null,
            filter: ($args['filter'] ?? null) instanceof Closure ? $args['filter'] : null,
        ))->prompt();
    }

    /**
     * Normalize headers to a list shape for DataTablePrompt.
     *
     * @param array<mixed> $headers Raw headers
     * @return list<string|list<string>>
     */
    private function normalizeHeaderList(array $headers): array
    {
        $normalized = [];

        foreach (array_values($headers) as $header) {
            if (is_array($header)) {
                $cells = [];
                foreach (array_values($header) as $cell) {
                    $cells[] = (string)$cell;
                }

                $normalized[] = $cells;
                continue;
            }

            $normalized[] = (string)$header;
        }

        return $normalized;
    }

    /**
     * Normalize rows preserving keys for DataTablePrompt selection values.
     *
     * @param array<mixed> $rows Raw rows
     * @return array<int|string, list<string>>
     */
    private function normalizeKeyedRowList(array $rows): array
    {
        $normalized = [];

        foreach ($rows as $key => $row) {
            $cells = [];
            if (is_array($row)) {
                foreach (array_values($row) as $cell) {
                    $cells[] = (string)$cell;
                }
            }

            $normalized[$key] = $cells;
        }

        return $normalized;
    }

    /**
     * Build and run a DataTablePrompt.
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
