<?php
$flash = $flash ?? \App\Core\Response::getFlash();
if ($flash):
    $type = $flash['type'] ?? 'info';
    $message = $flash['message'] ?? '';

    [$bgColor, $borderColor, $textColor, $icon] = match ($type) {
        'success' => ['bg-emerald-50', 'border-emerald-300', 'text-emerald-800', '✅'],
        'error' => ['bg-rose-50', 'border-rose-300', 'text-rose-800', '⚠️'],
        'warning' => ['bg-amber-50', 'border-amber-300', 'text-amber-800', '🔔'],
        default => ['bg-blue-50', 'border-blue-300', 'text-blue-800', 'ℹ️'],
    };
?>
<div id="flash-alert" class="mb-6 <?= $bgColor ?> border <?= $borderColor ?> <?= $textColor ?> px-4 py-3 rounded-xl shadow-sm flex items-center justify-between transition-all duration-300">
    <div class="flex items-center gap-3">
        <span class="text-xl"><?= $icon ?></span>
        <span class="font-medium text-sm"><?= htmlspecialchars($message) ?></span>
    </div>
    <button onclick="document.getElementById('flash-alert').remove()" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
</div>
<?php endif; ?>
