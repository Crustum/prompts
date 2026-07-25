<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Collection\Collection;
use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\Table;
use Override;

/**
 * Cake Console helper for Table.
 *
 * Expected `$args` keys:
 * - `headers` (array|\Cake\Collection\Collection) — table headers, or rows when `rows` is null
 * - `rows` (array|\Cake\Collection\Collection|null) — table rows
 */
class TableHelper extends Helper
{
    /**
     * Display a table.
     *
     * @param array<string, mixed> $args Table configuration (see class docblock)
     */
    public function run(array $args): null
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

        $table = new Table(
            $this->normalizeHeaderList($headers),
            $rows === null ? null : $this->normalizeRowList($rows),
        );

        if (Table::shouldFallback()) {
            ConsoleIoFallbacks::outputTable($table);
        } else {
            $table->display();
        }

        return null;
    }

    /**
     * Normalize headers to a list shape for Table.
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
     * Normalize rows to a list shape for Table.
     *
     * @param array<mixed> $rows Raw rows
     * @return list<list<string>>
     */
    private function normalizeRowList(array $rows): array
    {
        $normalized = [];

        foreach (array_values($rows) as $row) {
            $cells = [];
            if (is_array($row)) {
                foreach (array_values($row) as $cell) {
                    $cells[] = (string)$cell;
                }
            }

            $normalized[] = $cells;
        }

        return $normalized;
    }

    /**
     * Display a table.
     *
     * @param array<string, mixed> $args Table configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
