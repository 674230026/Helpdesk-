<div class="space-y-8">
    <!-- Header banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-amber-600 via-amber-700 to-slate-900 rounded-2xl p-6 sm:p-8 text-white shadow-xl shadow-amber-950/20">
        <div>
            <span class="inline-block px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-amber-200 mb-2">พื้นที่ปฏิบัติงานของช่างเทคนิค</span>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">สวัสดีครับ, ช่าง<?= htmlspecialchars($user['name']) ?></h1>
            <p class="text-amber-200 text-sm mt-1">คิวงานซ่อมบำรุงและงานระบบตามลำดับความเร่งด่วน พร้อมระบบแจ้งเตือนกำหนดเวลา SLA</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-xl bg-white/10 text-white font-bold text-sm border border-white/20">
                งานที่ต้องปฏิบัติ: <?= $counts['assigned'] + $counts['en_route'] + $counts['in_progress'] + $counts['waiting_parts'] ?> งาน
            </span>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">งานทั้งหมด</p>
            <h3 class="text-2xl font-extrabold text-slate-800 mt-1"><?= $counts['total'] ?></h3>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <p class="text-xs font-bold uppercase tracking-wider text-purple-600">เดินทาง/กำลังซ่อม</p>
            <h3 class="text-2xl font-extrabold text-indigo-600 mt-1"><?= $counts['en_route'] + $counts['in_progress'] ?></h3>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <p class="text-xs font-bold uppercase tracking-wider text-orange-600">รออะไหล่</p>
            <h3 class="text-2xl font-extrabold text-orange-600 mt-1"><?= $counts['waiting_parts'] ?></h3>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <p class="text-xs font-bold uppercase tracking-wider text-rose-500">เกินกำหนดเวลา SLA</p>
            <h3 class="text-2xl font-extrabold text-rose-600 mt-1"><?= $counts['overdue'] ?></h3>
        </div>
    </div>

    <!-- Assigned Jobs Grid -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-900">คิวงานซ่อมของฉัน</h2>
            <span class="text-xs text-slate-500">เรียงตาม: เกินกำหนด SLA &rarr; ด่วนที่สุด &rarr; สูง &rarr; ปกติ</span>
        </div>

        <?php if (empty($jobs)): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-xs">
                <div class="text-4xl mb-3">🎉</div>
                <h4 class="text-sm font-bold text-slate-700">ไม่มีงานซ่อมค้างในขณะนี้</h4>
                <p class="text-xs text-slate-400 mt-1">คุณได้จัดการงานซ่อมทั้งหมดเรียบร้อยแล้ว หรือยังไม่มีการมอบหมายงานใหม่</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($jobs as $job): 
                    $statusEnum = \App\Enums\TicketStatus::tryFrom($job['status']);
                    $priorityEnum = \App\Enums\TicketPriority::tryFrom($job['priority']);
                    $isOverdue = !empty($job['is_overdue']);
                ?>
                <div class="bg-white rounded-2xl border <?= $isOverdue ? 'border-rose-400 ring-2 ring-rose-500/20' : 'border-slate-200' ?> shadow-xs p-6 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <!-- Header with Badges & SLA -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                #<?= $job['id'] ?>
                            </span>
                            <div class="flex items-center gap-1.5 flex-wrap justify-end">
                                <?php if ($isOverdue): ?>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-600 text-white animate-pulse">
                                         ⚠️ เกินกำหนดเวลา SLA
                                     </span>
                                <?php endif; ?>
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold <?= $priorityEnum ? $priorityEnum->badgeClass() : '' ?>">
                                    <?= $priorityEnum ? $priorityEnum->label() : $job['priority'] ?>
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold <?= $statusEnum ? $statusEnum->badgeClass() : '' ?>">
                                    <?= $statusEnum ? $statusEnum->label() : $job['status'] ?>
                                </span>
                            </div>
                        </div>

                        <!-- Title & Location -->
                        <h3 class="font-bold text-slate-900 text-base mb-1 line-clamp-2">
                            <a href="<?= url('/technician/jobs/' . $job['id']) ?>" class="hover:text-amber-600 transition">
                                <?= htmlspecialchars($job['title']) ?>
                            </a>
                        </h3>
                        <p class="text-xs font-semibold text-slate-700 mb-1 flex items-center gap-1">
                            📍 <?= htmlspecialchars($job['location']) ?>
                        </p>
                        <p class="text-[11px] text-slate-400 mb-3">🏷️ <?= htmlspecialchars($job['category_name']) ?></p>

                        <!-- Description Snippet -->
                        <p class="text-xs text-slate-600 line-clamp-3 mb-4 leading-relaxed bg-slate-50 p-2.5 rounded-xl">
                            <?= htmlspecialchars($job['description']) ?>
                        </p>

                        <!-- User Info & SLA -->
                        <div class="space-y-1 text-xs text-slate-500 py-2 border-t border-slate-100">
                            <div class="flex justify-between">
                                <span>ผู้แจ้ง: <strong><?= htmlspecialchars($job['user_name']) ?></strong></span>
                                <span><?= date('d/m H:i', strtotime($job['created_at'])) ?></span>
                            </div>
                            <?php if ($job['sla_due_at']): ?>
                            <div class="flex justify-between <?= $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-600' ?>">
                                <span>กำหนด SLA:</span>
                                <span><?= date('d/m/Y H:i', strtotime($job['sla_due_at'])) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Quick Action Bar -->
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <a href="<?= url('/technician/jobs/' . $job['id']) ?>" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-2 py-1.5 rounded-lg hover:bg-slate-100 transition">
                            ดูรายละเอียด
                        </a>

                        <?php if ($job['status'] === 'assigned'): ?>
                            <form action="<?= url('/technician/jobs/' . $job['id'] . '/en-route') ?>" method="POST">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition">
                                    🚗 กำลังเดินทาง
                                </button>
                            </form>
                        <?php elseif ($job['status'] === 'en_route'): ?>
                            <form action="<?= url('/technician/jobs/' . $job['id'] . '/start') ?>" method="POST">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition">
                                    🔧 เริ่มซ่อมแซม
                                </button>
                            </form>
                        <?php elseif (in_array($job['status'], ['in_progress', 'waiting_parts'], true)): ?>
                            <a href="<?= url('/technician/jobs/' . $job['id']) ?>" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-xs transition">
                                ⚙️ จัดการ/ปิดงาน &rarr;
                            </a>
                        <?php else: ?>
                            <span class="text-xs text-slate-400 font-medium italic">ส่งมอบแล้ว</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
