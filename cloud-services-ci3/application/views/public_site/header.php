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
        }
        body {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(1000px 500px at 10% -5%, #dff5ff 0%, transparent 70%),
                radial-gradient(900px 500px at 90% -10%, #ffe7d8 0%, transparent 65%),
                var(--page-bg);
        }
        h1, h2, h3, h4, h5, h6 { font-family: 'Space Grotesk', sans-serif; }
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
