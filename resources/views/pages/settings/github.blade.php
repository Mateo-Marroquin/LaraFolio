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
        @if (session('status') === 'profile-updated')
            <div
                x-data
                x-init="$nextTick(() => {
            $flux.toast({
                heading: 'Sincronizado',
                text: 'Perfil de GitHub vinculado correctamente.',
                variant: 'success',
                duration: 1500
            })
        })"
            ></div>
        @endif

        <form action="{{ route('import-profile') }}" method="POST" class="space-y-6">
            @csrf

            <flux:input
                name="username"
                label="Username"
                description="Ingresa el nombre de usuario de tu cuenta de GitHub."
                required
            />

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary" color="green">
                    {{ __('Buscar en GitHub') }}
                </flux:button>
            </div>
        </form>
    </x-pages::settings.layout>
</section>
