<div>
    <form wire:submit="authenticate">
        {{ $this->form }}
        <x-button type="submit" size="lg" outlined class="inline-block mt-4 hover:bg-black hover:text-white">
            Login
        </x-button>
    </form>
</div>
