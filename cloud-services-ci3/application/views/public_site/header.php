<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? html_escape($title) : 'CloudPanel'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif']
                    }
                }
            }
        };
    </script>
    <script>
        (function () {
            var saved = null;
            try { saved = localStorage.getItem('cp_theme'); } catch (e) { saved = null; }
            var initial = saved || 'light';
            document.documentElement.setAttribute('data-theme', initial);
        }());
    </script>
    <style>
        * { box-sizing: border-box; }
        :root {
            --page-bg: #f6f7fb;
            --text-main: #11203b;
            --text-muted: #5f6f89;
            --line: #dce3ef;
            --brand: #1f7ae0;
            --brand-soft: #e3f0ff;
            --accent: #ff7d3d;
            --surface: #ffffff;
            --surface-soft: #f8fbff;
            --footer-bg: #0f172a;
            --footer-text: #c3d2e8;
            --toggle-bg: #0f172a;
            --toggle-text: #f8fafc;
        }
        html[data-theme='dark'] {
            --page-bg: #0b1220;
            --text-main: #d8e6fb;
            --text-muted: #8da5c8;
            --line: #2b3d5f;
            --brand: #38bdf8;
            --brand-soft: #1b2f4c;
            --accent: #f9a84e;
            --surface: #121e33;
            --surface-soft: #0f1a2d;
            --footer-bg: #081121;
            --footer-text: #9eb6da;
            --toggle-bg: #f8fafc;
            --toggle-text: #0f172a;
        }
        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(1000px 500px at 10% -5%, color-mix(in srgb, var(--brand-soft) 85%, transparent) 0%, transparent 70%),
                radial-gradient(900px 500px at 90% -10%, color-mix(in srgb, var(--accent) 24%, transparent) 0%, transparent 65%),
                var(--page-bg);
            transition: background-color 220ms ease, color 220ms ease;
        }
        h1, h2, h3, h4, h5, h6 { font-family: 'Space Grotesk', sans-serif; }
        .bg-white,
        .bg-white\/70,
        .bg-white\/85,
        .bg-slate-50 {
            background: var(--surface) !important;
        }
        .text-slate-900,
        .text-slate-800,
        .text-slate-700 { color: var(--text-main) !important; }
        .text-slate-600,
        .text-slate-500,
        .text-slate-400 { color: var(--text-muted) !important; }
        .border-slate-200,
        .border-slate-300,
        .border-slate-800 { border-color: var(--line) !important; }
        .bg-slate-900 { background: #0f172a !important; }
        html[data-theme='dark'] .bg-slate-900 { background: #0a1322 !important; }
        .theme-toggle-fab {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 70;
            border: 1px solid color-mix(in srgb, var(--toggle-bg) 75%, #64748b);
            background: var(--toggle-bg);
            color: var(--toggle-text);
            border-radius: 999px;
            height: 44px;
            min-width: 44px;
            padding: 0 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.24);
            cursor: pointer;
        }
        .theme-toggle-fab:hover { transform: translateY(-1px); }
        .reveal {
            opacity: 0;
            transform: translateY(16px);
            animation: reveal-up 700ms ease forwards;
        }
        .reveal-delay-1 { animation-delay: 100ms; }
        .reveal-delay-2 { animation-delay: 180ms; }
        .reveal-delay-3 { animation-delay: 260ms; }
        @keyframes reveal-up {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #edf1f7; }
        ::-webkit-scrollbar-thumb { background: #b8c6da; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #93a8c5; }
    </style>
</head>
<body class="text-slate-800">
