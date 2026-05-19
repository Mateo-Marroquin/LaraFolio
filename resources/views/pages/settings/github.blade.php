<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Ajustes de GitHub')] class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Ajustes de GitHub') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Cuenta de GitHub')" :subheading="__('Vincula tu cuenta de GitHub')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-pages::settings.layout>
</section>
