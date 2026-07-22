<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Collection\Collection;
use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\Grid;
use Override;

/**
 * Cake Console helper for Grid.
 *
 * Expected `$args` keys:
 * - `items` (array|\Cake\Collection\Collection) — grid items
 * - `maxWidth` (int|null) — optional max width
 */
class GridHelper extends Helper
{
    /**
     * Display a grid.
     *
     * @param array<string, mixed> $args Grid configuration (see class docblock)
     * @return mixed Always null
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        $items = $args['items'] ?? [];
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        if (!is_array($items)) {
            $items = [];
        }

        /** @var array<int, string> $items */
        $items = array_values(array_map(static fn(mixed $item): string => (string)$item, $items));

        $maxWidth = $args['maxWidth'] ?? null;

        $grid = new Grid(
            items: $items,
            maxWidth: $maxWidth !== null ? (int)$maxWidth : null,
        );

        if (Grid::shouldFallback()) {
            ConsoleIoFallbacks::outputGrid($grid);
        } else {
            $grid->display();
        }

        return null;
    }

    /**
     * Display a grid.
     *
     * @param array<string, mixed> $args Grid configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
