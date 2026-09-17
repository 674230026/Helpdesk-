<!DOCTYPE html>
<html lang="th" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Smart IT Helpdesk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', 'Inter', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-full flex items-center justify-center p-4">

<div class="max-w-md w-full">
    <!-- Header Logo -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600 text-white shadow-xl shadow-indigo-500/30 mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white tracking-tight">Smart IT Helpdesk</h1>
        <p class="text-slate-400 text-sm mt-1">ระบบแจ้งซ่อมและแจ้งเตือนอัตโนมัติ (OOP PHP 8.2)</p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-2xl p-8 border border-slate-100">
        <?php if (!empty($flash)): ?>
            <div class="mb-5 p-3 rounded-lg text-sm <?= $flash['type'] === 'error' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-blue-50 text-blue-700 border border-blue-200' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <form action="<?= url('/login') ?>" method="POST" class="space-y-4">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">อีเมล</label>
                <input type="email" name="email" required placeholder="admin@helpdesk.local" 
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">รหัสผ่าน</label>
                </div>
                <input type="password" name="password" required placeholder="••••••••" 
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md shadow-indigo-200 transition">
                เข้าสู่ระบบ &rarr;
            </button>
        </form>


        <div class="mt-6 text-center text-xs text-slate-500">
            ยังไม่มีบัญชีผู้ใช้? <a href="<?= url('/register') ?>" class="text-indigo-600 font-semibold hover:underline">สมัครสมาชิกใหม่</a>
        </div>
    </div>
</div>

</body>
</html>
