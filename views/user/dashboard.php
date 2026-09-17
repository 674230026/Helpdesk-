<div class="space-y-8">
    <!-- Header banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-indigo-700 via-indigo-800 to-slate-900 rounded-2xl p-6 sm:p-8 text-white shadow-xl shadow-indigo-950/20">
        <div>
            <span class="inline-block px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-indigo-200 mb-2">ศูนย์บริการและแจ้งซ่อมสำหรับผู้ใช้งาน</span>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">สวัสดีครับ, คุณ<?= htmlspecialchars($user['name']) ?></h1>
            <p class="text-indigo-200 text-sm mt-1">ระบบแจ้งซ่อมและงานบริการอาคาร ติดตามขั้นตอนงานซ่อมแซมได้แบบเรียลไทม์</p>
        </div>
        <div>
            <a href="<?= url('/tickets/create') ?>" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-indigo-700 hover:bg-indigo-50 font-bold text-sm shadow-md transition transform active:scale-95">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>เปิดใบแจ้งซ่อมใหม่</span>
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Total -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">ใบแจ้งซ่อมทั้งหมด</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1"><?= $counts['total'] ?></h3>
                <p class="text-xs text-slate-500 mt-1">ประวัติคำขอทั้งหมดของคุณ</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                📑
            </div>
        </div>

        <!-- Active Jobs -->
        <?php $activeCount = $counts['pending_approval'] + $counts['approved'] + $counts['assigned'] + $counts['en_route'] + $counts['in_progress'] + $counts['waiting_parts']; ?>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-500">กำลังดำเนินการ</p>
                <h3 class="text-3xl font-extrabold text-indigo-600 mt-1"><?= $activeCount ?></h3>
                <p class="text-xs text-indigo-500 mt-1 font-medium">รวมขั้นตอนเดินทาง ตรวจสอบ และซ่อมแซม</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                ⚙️
            </div>
        </div>

        <!-- Resolved -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-500">ซ่อมเสร็จแล้ว</p>
                <h3 class="text-3xl font-extrabold text-emerald-600 mt-1"><?= $counts['resolved'] ?></h3>
                <p class="text-xs text-emerald-600 mt-1 font-medium">รอให้คุณตรวจรับและประเมินผลงาน</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                ✅
            </div>
        </div>
    </div>

    <!-- Ticket History Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <div>
                <h2 class="text-base font-bold text-slate-800">รายการแจ้งซ่อมของฉัน</h2>
                <p class="text-xs text-slate-500">ตรวจสอบสถานะการซ่อม สถานที่ และกดยืนยันปิดงาน</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-slate-100 text-slate-600">
                <?= count($tickets) ?> รายการ
            </span>
        </div>

        <?php if (empty($tickets)): ?>
            <div class="p-12 text-center">
                <div class="text-4xl mb-3">📬</div>
                <h4 class="text-sm font-bold text-slate-700">ยังไม่มีรายการแจ้งซ่อม</h4>
                <p class="text-xs text-slate-400 mt-1 mb-4">หากพบปัญหาอาคาร สถานที่ หรือระบบไอที สามารถกดแจ้งซ่อมได้ทันที</p>
                <a href="<?= url('/tickets/create') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                    ➕ แจ้งซ่อมครั้งแรก
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-600">
                        <tr>
                            <th class="px-5 py-4">รหัส</th>
                            <th class="px-5 py-4">หัวข้อปัญหา</th>
                            <th class="px-5 py-4">สถานที่</th>
                            <th class="px-5 py-4">หมวดหมู่</th>
                            <th class="px-5 py-4">ความเร่งด่วน</th>
                            <th class="px-5 py-4">สถานะปัจจุบัน</th>
                            <th class="px-5 py-4">ช่างผู้ดูแล</th>
                            <th class="px-5 py-4 text-right">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        <?php foreach ($tickets as $t): 
                            $statusEnum = \App\Enums\TicketStatus::tryFrom($t['status']);
                            $priorityEnum = \App\Enums\TicketPriority::tryFrom($t['priority']);
                        ?>
                        <tr class="hover:bg-slate-50/80 transition group">
                            <td class="px-5 py-4 font-bold text-slate-900">#<?= $t['id'] ?></td>
                            <td class="px-5 py-4">
                                <a href="<?= url('/tickets/' . $t['id']) ?>" class="font-semibold text-slate-800 group-hover:text-indigo-600 transition block max-w-xs truncate text-sm">
                                    <?= htmlspecialchars($t['title']) ?>
                                </a>
                            </td>
                            <td class="px-5 py-4 text-slate-700">
                                📍 <?= htmlspecialchars($t['location']) ?>
                            </td>
                            <td class="px-5 py-4 text-slate-600"><?= htmlspecialchars($t['category_name']) ?></td>
                            <td class="px-5 py-4">
                                <span class="px-2 py-0.5 rounded-full font-bold <?= $priorityEnum ? $priorityEnum->badgeClass() : '' ?>">
                                    <?= $priorityEnum ? $priorityEnum->label() : $t['priority'] ?>
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-semibold <?= $statusEnum ? $statusEnum->badgeClass() : '' ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?= $statusEnum ? $statusEnum->dotColor() : 'bg-slate-400' ?>"></span>
                                    <?= $statusEnum ? $statusEnum->label() : $t['status'] ?>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                <?= $t['technician_name'] ? htmlspecialchars($t['technician_name']) : '<span class="text-amber-600 font-medium">รอจ่ายงาน</span>' ?>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="<?= url('/tickets/' . $t['id']) ?>" class="inline-flex items-center gap-1 font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                                    <span>ติดตาม</span> &rarr;
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
