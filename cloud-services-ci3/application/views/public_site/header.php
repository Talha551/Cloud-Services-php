<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? html_escape($title) : 'CloudPanel'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        };
    </script>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Inter, sans-serif; background: #0a0c12; color: #e2e8f0; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #1a1d27; }
        ::-webkit-scrollbar-thumb { background: #3a3f55; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #4a5068; }
    </style>
</head>
<body class="bg-[#0a0c12] text-slate-200">
