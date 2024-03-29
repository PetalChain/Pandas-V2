<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="border-b-[1.5px] py-2 border-black flex gap-x-1 items-center font-medium" x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <div class="flex">
        </div>

        <x-input
            :attributes="\Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                ->merge($getExtraAlpineAttributes(), escape: false)
                ->merge([
                    'placeholder' => strtoupper($getPlaceholder()),
                    'autocapitalize' => $getAutocapitalize(),
                    'autocomplete' => $getAutocomplete(),
                    'autofocus' => $isAutofocused(),
                    'disabled' => $isDisabled(),
                    'required' => $isRequired(),
                    'max' => (! $isConcealed()) ? $getMaxValue() : null,
                    'maxlength' => (! $isConcealed()) ? $getMaxLength() : null,
                    'min' => (! $isConcealed()) ? $getMinValue() : null,
                    'minlength' => (! $isConcealed()) ? $getMinLength() : null,
                    'type' => $getType() ?? 'text',
                    'x-mask' . ($getMask() instanceof \Filament\Support\RawJs ? ':dynamic' : '') => filled($getMask()) ? $getMask() : null,
                    $applyStateBindingModifiers('wire:model') => $getStatePath(),
                ], escape: false)
                "
        />
    </div>
</x-dynamic-component>
