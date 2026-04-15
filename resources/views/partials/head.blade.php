<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
/* Livewire loading styles */
[wire\:loading] {
    display: none;
}
[wire\:loading].wire-loading {
    display: block;
}
[wire\:loading].inline-block {
    display: none;
}
[wire\:loading].inline-flex {
    display: none;
}
[wire\:loading].flex {
    display: none;
}

/* Fix for mobile button sticking issues */
.touch-manipulation {
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
    user-select: none;
    -webkit-user-select: none;
}

/* Prevent default touch behaviors on buttons */
button, [role="button"] {
    touch-action: manipulation;
}

/* Disable text selection on interactive elements */
.cursor-pointer {
    cursor: pointer;
}

/* Mobile typography consistency */
@media (max-width: 1024px) {
    body {
        font-family: 'Instrument Sans', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        letter-spacing: -0.025em;
    }
}
