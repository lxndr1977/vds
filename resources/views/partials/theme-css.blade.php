@php
$configuration = \App\Models\SystemConfiguration::first();
$primaryColor = $configuration?->primary_color ?? '#b21653';
$secondaryColor = $configuration?->secondary_color ?? '#313674';
@endphp

<style>
    :root {

        /* ── Cores principais ── */
        --color-primary: {{  $primaryColor }} ;

        --color-secondary:  {{  $secondaryColor }} ;

        /*
         * DaisyUI 5 usa color-mix() para gerar variantes de hover/focus.
         * Fornecendo a mesma cor em --color-primary-600 (Tailwind palette)
         * garantimos que os utilitários bg-primary-600, text-primary-600
         * também reflitam a cor dinâmica.
         */
        --color-primary-500: {{ $primaryColor }};

        --color-primary-600: {{ $primaryColor }};

        --color-secondary-500: {{ $secondaryColor }};

        --color-secondary-600: {{ $secondaryColor }};
    }
</style>