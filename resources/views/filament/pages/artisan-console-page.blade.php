<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament-panels::form wire:submit="runCommand">
            {{ $this->form }}
        </x-filament-panels::form>

        @if ($output !== null)
            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Output:</p>
                <pre class="p-4 bg-gray-950 text-green-400 rounded-lg overflow-x-auto font-mono text-sm whitespace-pre-wrap border border-gray-700">{{ $output }}</pre>
            </div>
        @endif
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>
