<?php

namespace App\Filament\Concerns;

trait HasPagePersistent
{
    protected function shouldPersistTableSearchInSession(): bool
    {
        return true;
    }

    protected function shouldPersistTableColumnSearchInSession(): bool
    {
        return true;
    }

    protected function shouldPersistTableSortInSession(): bool
    {
        return true;
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }

    protected function shouldPersistTablePerPageInSession(): bool
    {
        return true;
    }
}
