<x-layouts::app :title="__('Contáctame')">
    <div class="max-w-2xl mx-auto space-y-6">

        @if(session('success'))
            <div class="p-4 text-sm text-emerald-600 bg-emerald-50 dark:bg-emerald-950/30 dark:text-emerald-400 rounded-lg border border-emerald-200/50 dark:border-emerald-800/30">
                {{ session('success') }}
            </div>
        @endif

        @error('error')
        <div class="p-4 text-sm text-rose-600 bg-rose-50 dark:bg-rose-950/30 dark:text-rose-400 rounded-lg border border-rose-200/50 dark:border-rose-800/30">
            {{ $message }}
        </div>
        @enderror

        <flux:card class="p-6 sm:p-8 border-t-4 border-t-emerald-500 bg-gradient-to-b from-blue-500/5 via-transparent to-transparent space-y-6">

            <div class="space-y-1.5 pb-2">
                <flux:heading size="lg" level="2" class="font-bold tracking-tight">
                    {{ __('Ponte en contacto con :user', ['user' => $username]) }}
                </flux:heading>
                <flux:subheading size="sm">
                    {{ __('Envía un correo electrónico directamente a la cuenta vinculada de GitHub de este desarrollador.') }}
                </flux:subheading>
            </div>

            <form action="{{ route('public.contact.send', $username) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <flux:input
                        label="{{ __('Tu Nombre') }}"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="Ej. Juan Pérez"
                        class="w-full"
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
                        class="w-full"
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
                        class="w-full"
                    >{{ old('message') }}</flux:textarea>
                    @error('message') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <flux:button type="submit" variant="primary" icon="paper-airplane" class="shadow-sm">
                        {{ __('Enviar Correo') }}
                    </flux:button>
                </div>
            </form>
        </flux:card>

    </div>
</x-layouts::app>
