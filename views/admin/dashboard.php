<div class="space-y-8">
    <!-- Header banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-purple-800 via-indigo-900 to-slate-900 rounded-2xl p-6 sm:p-8 text-white shadow-xl shadow-purple-950/20">
        <div>
            <span class="inline-block px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-purple-200 mb-2">ศูนย์ควบคุมการสั่งการและหัวหน้างาน</span>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">แดชบอร์ดวิเคราะห์และติดตาม SLA</h1>
            <p class="text-purple-200 text-sm mt-1">มอนิเตอร์ภาพรวมงานซ่อมบำรุง, ประสิทธิภาพ SLA, MTTR, และการอนุมัติเบิกอะไหล่</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="<?= url('/admin/tickets?status=pending_approval') ?>" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md transition">
                ⚡ คัดกรองงานรออนุมัติ (<?= $counts['pending_approval'] ?>)
            </a>
            <a href="<?= url('/admin/parts-requests') ?>" class="px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs shadow-md transition">
                📦 รออนุมัติอะไหล่ (<?= $pendingPartsCount ?>)
            </a>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Tickets -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">ใบงานทั้งหมด</p>
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">📑</span>
            </div>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-2"><?= $counts['total'] ?></h3>
            <div class="mt-2 text-xs text-slate-500 flex items-center gap-2">
                <span class="text-amber-600 font-semibold"><?= $counts['pending_approval'] ?> รออนุมัติ</span> &bull;
                <span class="text-indigo-600 font-semibold"><?= $counts['in_progress'] ?> กำลังซ่อม</span>
            </div>
        </div>

        <!-- SLA Compliance Rate -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">อัตราการทำตามกำหนดเวลา SLA</p>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">🎯</span>
            </div>
            <h3 class="text-3xl font-extrabold text-emerald-600 mt-2"><?= $slaRate ?>%</h3>
            <div class="mt-2 text-xs text-slate-500">
                <?= $counts['overdue'] > 0 ? "<span class='text-rose-600 font-bold'>⚠️ เกินกำหนด SLA: {$counts['overdue']} งาน</span>" : "<span class='text-emerald-600 font-semibold'>✓ ทุกงานอยู่ในเกณฑ์ SLA</span>" ?>
            </div>
        </div>

        <!-- MTTR -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">ระยะเวลาเฉลี่ยในการซ่อม (MTTR)</p>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">⏱️</span>
            </div>
            <h3 class="text-3xl font-extrabold text-blue-600 mt-2"><?= $mttrHours ?> <span class="text-sm font-medium text-slate-500">ชม.</span></h3>
            <p class="text-xs text-slate-400 mt-2">ระยะเวลาเฉลี่ยตั้งแต่เริ่มจนปิดงานสำเร็จ</p>
        </div>

        <!-- CSAT Average Score -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">ความพึงพอใจ (CSAT)</p>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">⭐</span>
            </div>
            <h3 class="text-3xl font-extrabold text-amber-500 mt-2"><?= $avgRating ?: '5.0' ?> <span class="text-sm font-medium text-slate-400">/ 5.0</span></h3>
            <p class="text-xs text-slate-400 mt-2"><?= str_repeat('★', (int)$avgRating) ?> จากการประเมินของผู้ใช้งาน</p>
        </div>
    </div>

    <!-- SLA Overdue Alert Box (If any) -->
    <?php if (!empty($overdueTickets)): ?>
    <div class="bg-rose-50 border border-rose-300 rounded-2xl p-6 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-rose-900 flex items-center gap-2">
                <span class="text-lg">🚨</span> รายการแจ้งซ่อมที่เกินกำหนดเวลา SLA (เกินกำหนดเวลา)
            </h3>
            <a href="<?= url('/admin/tickets?sla_filter=overdue') ?>" class="text-xs text-rose-700 font-bold hover:underline">ดูรายการเกินเวลาทั้งหมด &rarr;</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
            <?php foreach ($overdueTickets as $ot): ?>
                <div class="bg-white p-3 rounded-xl border border-rose-200 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-slate-900">#<?= $ot['id'] ?>: <?= htmlspecialchars($ot['title']) ?></div>
                        <div class="text-slate-500">📍 <?= htmlspecialchars($ot['location']) ?> | ช่าง: <?= htmlspecialchars($ot['technician_name'] ?? 'ยังไม่จ่ายงาน') ?></div>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                            กำหนด: <?= date('d/m H:i', strtotime($ot['sla_due_at'])) ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Charts Section (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Category Distribution Chart -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    🍩 สัดส่วนงานแยกตามหมวดหมู่
                </h3>
            </div>
            <div class="relative h-64 flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Technician Performance Chart -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    📊 สถิติการปิดงานและงานค้างของช่างเทคนิค
                </h3>
            </div>
            <div class="relative h-64">
                <canvas id="technicianChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Category Chart Data
        const catLabels = <?= json_encode(array_column($categoryDist, 'name')) ?>;
        const catData = <?= json_encode(array_map('intval', array_column($categoryDist, 'ticket_count'))) ?>;

        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catData,
                    backgroundColor: ['#6366f1', '#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { family: 'Sarabun' } } }
                }
            }
        });

        // Technician Performance Chart Data
        const techLabels = <?= json_encode(array_column($techPerf, 'name')) ?>;
        const techClosed = <?= json_encode(array_map('intval', array_column($techPerf, 'closed_count'))) ?>;
        const techActive = <?= json_encode(array_map('intval', array_column($techPerf, 'active_count'))) ?>;

        new Chart(document.getElementById('technicianChart'), {
            type: 'bar',
            data: {
                labels: techLabels,
                datasets: [
                    {
                        label: 'ปิดงานสำเร็จแล้ว',
                        data: techClosed,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                    },
                    {
                        label: 'งานกำลังดำเนินการ',
                        data: techActive,
                        backgroundColor: '#6366f1',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                },
                plugins: {
                    legend: { position: 'bottom', labels: { font: { family: 'Sarabun' } } }
                }
            }
        });
    });
</script>
