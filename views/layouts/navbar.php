<?php
$currentUser = \App\Core\Auth::user();
$currentRole = $currentUser['role'] ?? 'guest';
$roleEnum = \App\Enums\UserRole::tryFrom($currentRole);
$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$basePath = \App\Core\Request::basePath();
$currentPath = ($basePath !== '' && str_starts_with($rawUri, $basePath)) ? substr($rawUri, strlen($basePath)) : $rawUri;
?>
<nav class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Brand & Primary Nav -->
            <div class="flex items-center gap-6">
                <a href="<?= url('/') ?>" class="flex items-center gap-2.5 text-indigo-600 font-bold text-lg tracking-tight hover:opacity-90 transition">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white flex items-center justify-center shadow-md shadow-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span>ระบบแจ้งซ่อม<span class="text-slate-800">และงานอาคาร</span></span>
                </a>

                <?php if ($currentUser): ?>
                <div class="hidden lg:flex items-center gap-1">
                    <?php if ($currentRole === 'user'): ?>
                        <a href="<?= url('/dashboard') ?>" class="px-3 py-2 rounded-lg text-xs font-semibold <?= str_starts_with($currentPath, '/dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?> transition">
                            📋 แดชบอร์ดของฉัน
                        </a>
                        <a href="<?= url('/tickets/create') ?>" class="px-3 py-2 rounded-lg text-xs font-semibold <?= str_starts_with($currentPath, '/tickets/create') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?> transition">
                            ➕ แจ้งซ่อมใหม่
                        </a>
                    <?php elseif ($currentRole === 'technician'): ?>
                        <a href="<?= url('/technician/dashboard') ?>" class="px-3 py-2 rounded-lg text-xs font-semibold <?= str_starts_with($currentPath, '/technician') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?> transition">
                            🛠️ คิวงานซ่อมของฉัน
                        </a>
                    <?php elseif ($currentRole === 'admin'): ?>
                        <a href="<?= url('/admin/dashboard') ?>" class="px-2.5 py-2 rounded-lg text-xs font-semibold <?= $currentPath === '/admin/dashboard' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?> transition">
                            📊 สถิติภาพรวม
                        </a>
                        <a href="<?= url('/admin/tickets') ?>" class="px-2.5 py-2 rounded-lg text-xs font-semibold <?= str_starts_with($currentPath, '/admin/tickets') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?> transition">
                            🎫 จ่ายงานและติดตาม
                        </a>
                        <a href="<?= url('/admin/parts-requests') ?>" class="px-2.5 py-2 rounded-lg text-xs font-semibold <?= str_starts_with($currentPath, '/admin/parts-requests') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?> transition">
                            📦 อนุมัติเบิกอะไหล่
                        </a>
                        <a href="<?= url('/admin/inventory') ?>" class="px-2.5 py-2 rounded-lg text-xs font-semibold <?= str_starts_with($currentPath, '/admin/inventory') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?> transition">
                            🧰 คลังอะไหล่
                        </a>
                        <a href="<?= url('/admin/users') ?>" class="px-2.5 py-2 rounded-lg text-xs font-semibold <?= str_starts_with($currentPath, '/admin/users') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?> transition">
                            👥 ผู้ใช้งาน
                        </a>
                        <a href="<?= url('/admin/categories') ?>" class="px-2.5 py-2 rounded-lg text-xs font-semibold <?= str_starts_with($currentPath, '/admin/categories') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?> transition">
                            🏷️ หมวดหมู่
                        </a>
                        <a href="<?= url('/admin/email-logs') ?>" class="px-2.5 py-2 rounded-lg text-xs font-semibold <?= str_starts_with($currentPath, '/admin/email-logs') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' ?> transition">
                            📧 ประวัติอีเมล
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Profile Area -->
            <div class="flex items-center gap-3">
                <?php if ($currentUser): ?>

                    <!-- Role Badge -->
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?= $roleEnum ? $roleEnum->badgeClass() : 'bg-slate-100 text-slate-800' ?>">
                        <?= htmlspecialchars($roleEnum ? $roleEnum->label() : $currentRole) ?>
                    </span>

                    <!-- User Name & Logout -->
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                        <span class="text-xs text-slate-600 hidden md:inline font-medium"><?= htmlspecialchars($currentUser['name']) ?></span>
                        <a href="<?= url('/logout') ?>" title="ออกจากระบบ" class="text-xs bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold px-2.5 py-1.5 rounded-lg transition border border-rose-200">
                            ออกจากระบบ
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 px-3 py-2">เข้าสู่ระบบ</a>
                    <a href="<?= url('/register') ?>" class="text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-sm">สมัครสมาชิก</a>
                <?php endif; ?>
        </div>
    </div>
</nav>
