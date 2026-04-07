{{--
    Cabecera del inicio solo para guardias: tono institucional (grises fríos, acento discreto).
    Estilos con utilidades Tailwind compiladas en resources/css/filament/guard-panel.css.
--}}
<div
    class="mb-10 font-sans antialiased text-[#d4d6dc] [font-feature-settings:'tnum']"
>
    <div
        class="relative overflow-hidden rounded border border-[#3d4352] bg-[#1e222b] shadow-[0_1px_0_0_rgba(255,255,255,0.04)_inset]"
    >
        <div
            class="pointer-events-none absolute -right-16 -top-24 h-64 w-64 rounded-full bg-[#2c3140] opacity-80 blur-3xl"
            aria-hidden="true"
        ></div>
        <div
            class="pointer-events-none absolute inset-0 opacity-[0.07] [background-image:repeating-linear-gradient(135deg,#fff_0px,#fff_1px,transparent_1px,transparent_10px)]"
            aria-hidden="true"
        ></div>

        <div class="relative grid gap-8 p-6 sm:grid-cols-[1fr_auto] sm:items-start sm:p-8">
            <div class="min-w-0 space-y-4">
                <p class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-[#8b919f]">
                    Unidad de control de visitas
                </p>
                <h1 class="text-balance text-2xl font-semibold leading-tight tracking-tight text-[#f0f2f5] sm:text-3xl">
                    Panel operativo — Cárcel El Redentor
                </h1>
                <p class="max-w-2xl text-sm leading-relaxed text-[#9aa0ae]">
                    Registro de prisioneros, visitantes y citas. Las visitas presenciales se autorizan únicamente
                    <span class="font-medium text-[#c4c8d4]">domingos, 14:00 a 17:00</span>, sin solapar horarios del mismo interno.
                </p>
                <div class="flex flex-wrap gap-2 pt-1">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-sm border border-[#4a5163]/80 bg-[#262b36] px-2.5 py-1 text-xs font-medium text-[#b8bcc8]"
                    >
                        <span class="h-1.5 w-1.5 rounded-full bg-[#6b8f71]" aria-hidden="true"></span>
                        Sesión: {{ auth()->user()->name }}
                    </span>
                    <span
                        class="inline-flex items-center rounded-sm border border-[#4a5163]/60 bg-transparent px-2.5 py-1 text-xs text-[#8f95a3]"
                    >
                        {{ now()->translatedFormat('l j \d\e F Y') }}
                    </span>
                </div>
            </div>

            <div
                class="w-full shrink-0 border-t border-[#3d4352] pt-6 sm:w-56 sm:border-l sm:border-t-0 sm:pl-8 sm:pt-0"
            >
                <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-[#7a808e]">
                    Ventana legal
                </p>
                <dl class="mt-3 space-y-3 text-sm">
                    <div class="flex items-baseline justify-between gap-3 border-b border-[#343945] pb-2">
                        <dt class="text-[#8b919f]">Día</dt>
                        <dd class="font-medium tabular-nums text-[#e8eaef]">Domingo</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-3 border-b border-[#343945] pb-2">
                        <dt class="text-[#8b919f]">Desde</dt>
                        <dd class="font-medium tabular-nums text-[#e8eaef]">14:00</dd>
                    </div>
                    <div class="flex items-baseline justify-between gap-3">
                        <dt class="text-[#8b919f]">Hasta</dt>
                        <dd class="font-medium tabular-nums text-[#e8eaef]">17:00</dd>
                    </div>
                </dl>
                <p class="mt-4 text-[0.7rem] leading-snug text-[#6d7380]">
                    Verifique identidad del visitante y coherencia del horario antes de confirmar la cita.
                </p>
            </div>
        </div>
    </div>
</div>
