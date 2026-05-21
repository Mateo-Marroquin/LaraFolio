<x-layouts::app :title="__('Contáctame')">
    <div class="max-w-2xl mx-auto cp-6 rounded-xl space-y-6">

        <div>
            <flux:heading size="lg" level="2">{{ __('Ponte en contacto con :user', ['user' => $username]) }}</flux:heading>
            <flux:subheading>{{ __('Envía un correo electrónico directamente a la cuenta vinculada de GitHub de este desarrollador.') }}</flux:subheading>
        </div>

        @if(session('success'))
            <div class="p-4 text-sm text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 dark:text-emerald-400 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @error('error')
        <div class="p-4 text-sm text-rose-600 bg-rose-50 dark:bg-rose-950/30 dark:text-rose-400 rounded-lg">
            {{ $message }}
        </div>
        @enderror

        <flux:card>
            <form action="{{ route('public.contact.send', $username) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <flux:input
                        label="{{ __('Tu Nombre') }}"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="Ej. Juan Pérez"
                    />
                    @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <flux:input
                        type="email"
                        label="{{ __('Tu Correo Electrónico') }}"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="tu@correo.com"
                    />
                    @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <flux:textarea
                        label="{{ __('Mensaje o Propuesta') }}"
                        name="message"
                        rows="5"
                        required
                        placeholder="{{ __('Escribe aquí detalladamente lo que te gustaría proponerle al desarrollador...') }}"
                    >{{ old('message') }}</flux:textarea>
                    @error('message') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary" icon="paper-airplane">
                        {{ __('Enviar Correo') }}
                    </flux:button>
                </div>
            </form>
        </flux:card>

    </div>
</x-layouts::app>
