<x-filament::page>
    <x-filament::form wire:submit.prevent="import">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Import Data
        </x-filament::button>
    </x-filament::form>
</x-filament::page>
