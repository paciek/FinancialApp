@php
    $manifestPath = public_path('frontend/assets/manifest.json');
    $manifest = file_exists($manifestPath) ? json_decode((string) file_get_contents($manifestPath), true) : [];
    $isDev = app()->environment('local');
    $viteDevUrl = rtrim((string) env('VITE_DEV_SERVER_URL', 'http://localhost:5173'), '/');

    $entries = [
        'frontend/assets/scss/app.scss',
        'frontend/assets/js/app.js',
    ];

    $styles = [];
    $scripts = [];

    foreach ($entries as $entry) {
        if (!isset($manifest[$entry])) {
            continue;
        }

        $chunk = $manifest[$entry];

        if (!empty($chunk['file']) && str_ends_with($chunk['file'], '.css')) {
            $styles[] = $chunk['file'];
        }

        if (!empty($chunk['css']) && is_array($chunk['css'])) {
            $styles = array_merge($styles, $chunk['css']);
        }

        if (!empty($chunk['file']) && str_ends_with($chunk['file'], '.js')) {
            $scripts[] = $chunk['file'];
        }
    }

    $styles = array_values(array_unique($styles));
    $scripts = array_values(array_unique($scripts));
@endphp

@if (!empty($manifest))
    @foreach ($styles as $style)
        <link rel="stylesheet" href="{{ asset('frontend/assets/' . $style) }}">
    @endforeach

    @foreach ($scripts as $script)
        <script type="module" src="{{ asset('frontend/assets/' . $script) }}"></script>
    @endforeach
@elseif ($isDev)
    <script type="module" src="{{ $viteDevUrl }}/@vite/client"></script>
    <script type="module" src="{{ $viteDevUrl }}/frontend/assets/scss/app.scss"></script>
    <script type="module" src="{{ $viteDevUrl }}/frontend/assets/js/app.js"></script>
@endif