<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">ศูนย์จัดการใบสั่งซ่อม</h1>
            <p class="text-xs text-slate-500">คัดกรอง อนุมัติรับเรื่อง จ่ายงานช่าง และติดตามกำหนดเวลาตามข้อตกลง SLA</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-100">
                พบใบงานทั้งหมด <?= count($tickets) ?> รายการ
            </span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
        <form action="<?= url('/admin/tickets') ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 text-xs">
            <!-- Keyword -->
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">ค้นหาคำสำคัญ</label>
                <input type="text" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" placeholder="ค้นชื่อ, อาคาร, ปัญหา..." 
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">สถานะใบงาน</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs">
                    <option value="">-- ทุกสถานะ --</option>
                    <option value="pending_approval" <?= ($filters['status'] ?? '') === 'pending_approval' ? 'selected' : '' ?>>รออนุมัติ</option>
                    <option value="approved" <?= ($filters['status'] ?? '') === 'approved' ? 'selected' : '' ?>>อนุมัติแล้ว</option>
                    <option value="assigned" <?= ($filters['status'] ?? '') === 'assigned' ? 'selected' : '' ?>>จ่ายงานแล้ว</option>
                    <option value="en_route" <?= ($filters['status'] ?? '') === 'en_route' ? 'selected' : '' ?>>กำลังเดินทาง</option>
                    <option value="in_progress" <?= ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' ?>>กำลังดำเนินการซ่อม</option>
                    <option value="waiting_parts" <?= ($filters['status'] ?? '') === 'waiting_parts' ? 'selected' : '' ?>>รออะไหล่</option>
                    <option value="resolved" <?= ($filters['status'] ?? '') === 'resolved' ? 'selected' : '' ?>>ซ่อมเสร็จสิ้น (รอปิดงาน)</option>
                    <option value="closed" <?= ($filters['status'] ?? '') === 'closed' ? 'selected' : '' ?>>ปิดงานสมบูรณ์</option>
                    <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>ยกเลิก</option>
                </select>
            </div>

            <!-- SLA Filter -->
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">สถานะ SLA</label>
                <select name="sla_filter" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs">
                    <option value="">-- ทุกรายการ --</option>
                    <option value="overdue" <?= ($filters['sla_filter'] ?? '') === 'overdue' ? 'selected' : '' ?>>⚠️ เกินกำหนดเวลา</option>
                    <option value="near_due" <?= ($filters['sla_filter'] ?? '') === 'near_due' ? 'selected' : '' ?>>⏰ ใกล้ครบกำหนดเวลา (ภายใน 4 ชม.)</option>
                </select>
            </div>

            <!-- Priority Filter -->
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">ความเร่งด่วน</label>
                <select name="priority" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs">
                    <option value="">-- ทุกระดับ --</option>
                    <option value="urgent" <?= ($filters['priority'] ?? '') === 'urgent' ? 'selected' : '' ?>>ด่วนที่สุด</option>
                    <option value="high" <?= ($filters['priority'] ?? '') === 'high' ? 'selected' : '' ?>>สูง</option>
                    <option value="normal" <?= ($filters['priority'] ?? '') === 'normal' ? 'selected' : '' ?>>ปกติ</option>
                    <option value="low" <?= ($filters['priority'] ?? '') === 'low' ? 'selected' : '' ?>>ต่ำ</option>
                </select>
            </div>

            <!-- Technician Filter -->
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">ช่างเทคนิค</label>
                <select name="technician_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs">
                    <option value="">-- ทุกคน --</option>
                    <option value="unassigned" <?= ($filters['technician_id'] ?? '') === 'unassigned' ? 'selected' : '' ?>>[ยังไม่ได้จ่ายงาน]</option>
                    <?php foreach ($technicians as $tech): ?>
                        <option value="<?= $tech['id'] ?>" <?= ($filters['technician_id'] ?? '') == $tech['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tech['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition">
                    🔍 คัดกรอง
                </button>
                <a href="<?= url('/admin/tickets') ?>" class="py-2 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold transition text-center">
                    ล้าง
                </a>
            </div>
        </form>
    </div>

    <!-- Master Ticket Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3.5">รหัส</th>
                        <th class="px-5 py-3.5">หัวข้อปัญหา &amp; สถานที่</th>
                        <th class="px-5 py-3.5">ผู้แจ้ง</th>
                        <th class="px-5 py-3.5">ระดับความเร่งด่วน</th>
                        <th class="px-5 py-3.5">กำหนด SLA</th>
                        <th class="px-5 py-3.5">สถานะ</th>
                        <th class="px-5 py-3.5">ช่างผู้รับผิดชอบ</th>
                        <th class="px-5 py-3.5 text-right">การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">
                                ไม่พบรายการตามเงื่อนไขที่เลือก
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tickets as $t): 
                            $statusEnum = \App\Enums\TicketStatus::tryFrom($t['status']);
                            $priorityEnum = \App\Enums\TicketPriority::tryFrom($t['priority']);
                            $isOverdue = !empty($t['is_overdue']);
                        ?>
                        <tr class="hover:bg-slate-50/80 transition group">
                            <td class="px-5 py-3.5 font-bold text-slate-900">#<?= $t['id'] ?></td>
                            <td class="px-5 py-3.5">
                                <a href="<?= url('/tickets/' . $t['id']) ?>" class="font-bold text-slate-900 hover:text-indigo-600 transition block max-w-xs truncate">
                                    <?= htmlspecialchars($t['title']) ?>
                                </a>
                                <div class="text-slate-500 font-medium text-[11px] mt-0.5">
                                    📍 <?= htmlspecialchars($t['location']) ?> &bull; <span class="text-slate-400"><?= htmlspecialchars($t['category_name']) ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">
                                <span class="font-medium"><?= htmlspecialchars($t['user_name']) ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded-full font-bold <?= $priorityEnum ? $priorityEnum->badgeClass() : '' ?>">
                                    <?= $priorityEnum ? $priorityEnum->label() : $t['priority'] ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <?php if ($t['sla_due_at']): ?>
                                    <div class="<?= $isOverdue ? 'text-rose-600 font-bold animate-pulse' : 'text-slate-700' ?>">
                                        <?= date('d/m H:i', strtotime($t['sla_due_at'])) ?>
                                        <?= $isOverdue ? '<span class="text-[10px] bg-rose-100 px-1.5 py-0.2 rounded font-extrabold ml-1">เกินกำหนดเวลา</span>' : '' ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full font-semibold <?= $statusEnum ? $statusEnum->badgeClass() : '' ?>">
                                    <?= $statusEnum ? $statusEnum->label() : $t['status'] ?>
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <?php if ($t['technician_name']): ?>
                                    <span class="font-semibold text-slate-800"><?= htmlspecialchars($t['technician_name']) ?></span>
                                <?php else: ?>
                                    <span class="text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded">⚠️ รอจ่ายงาน</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                                <?php if ($t['status'] === 'pending_approval'): ?>
                                    <form action="<?= url('/admin/tickets/' . $t['id'] . '/approve') ?>" method="POST" class="inline">
                                        <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition">
                                            ✓ อนุมัติ
                                        </button>
                                    </form>
                                    <button onclick="openRejectModal(<?= $t['id'] ?>)" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold transition">
                                        ✕ ปฏิเสธ
                                    </button>
                                <?php endif; ?>

                                <?php if (in_array($t['status'], ['pending_approval', 'approved', 'assigned'], true)): ?>
                                    <button type="button" 
                                            onclick="openAssignModal(<?= $t['id'] ?>, '<?= htmlspecialchars(addslashes($t['title'])) ?>', '<?= $t['technician_id'] ?? '' ?>', '<?= $t['priority'] ?>')" 
                                            class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold transition">
                                        🛠️ จ่ายงาน
                                    </button>
                                <?php endif; ?>

                                <a href="<?= url('/tickets/' . $t['id']) ?>" class="px-2 py-1 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 font-semibold transition">
                                    ดู
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

<!-- Modal: Assign Technician & Set Priority -->
<div id="assign-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-xs">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900">🛠️ จ่ายงาน &amp; กำหนดระดับความเร่งด่วน</h3>
            <button onclick="document.getElementById('assign-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>

        <form id="assign-form" action="" method="POST" class="space-y-3">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">ใบงาน:</label>
                <p id="modal-ticket-title" class="p-2.5 bg-slate-50 rounded-xl text-slate-800 font-semibold"></p>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">เลือกช่างเทคนิคตามความเชี่ยวชาญ <span class="text-rose-500">*</span></label>
                <select name="technician_id" id="modal-tech-select" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                    <option value="">-- เลือกช่างเทคนิค --</option>
                    <?php foreach ($technicians as $tech): ?>
                        <option value="<?= $tech['id'] ?>"><?= htmlspecialchars($tech['name']) ?> (<?= htmlspecialchars($tech['email']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">ระดับความเร่งด่วน (กำหนด SLA อัตโนมัติ)</label>
                <select name="priority" id="modal-priority-select" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                    <option value="urgent">ด่วนที่สุด - SLA 4 ชม.</option>
                    <option value="high">สูง - SLA 8 ชม.</option>
                    <option value="normal">ปกติ - SLA 24 ชม.</option>
                    <option value="low">ต่ำ - SLA 48 ชม.</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">โน้ตแจ้งช่าง / กำชับการทำงาน</label>
                <textarea name="note" rows="2" placeholder="ระบุคำแนะนำเพิ่มเติมให้ช่าง เช่น เตรียมบันไดสูงไปด้วย, ให้ติดต่อคุณสมศรีที่หน้างาน..." 
                          class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('assign-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600">ยกเลิก</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md">ยืนยันจ่ายงาน &amp; ส่งเมล</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Reject Ticket -->
<div id="reject-admin-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-xs">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-rose-600">✕ ปฏิเสธคำขอใบแจ้งซ่อม</h3>
            <button onclick="document.getElementById('reject-admin-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form id="reject-admin-form" action="" method="POST" class="space-y-3">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">ระบุเหตุผลในการปฏิเสธ <span class="text-rose-500">*</span></label>
                <textarea name="reason" rows="3" required placeholder="เช่น อยู่นอกเหนือขอบเขตการรับผิดชอบของอาคาร, ข้อมูลไม่เพียงพอ..." 
                          class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('reject-admin-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600">ยกเลิก</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold shadow-md">ยืนยันปฏิเสธ</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAssignModal(ticketId, title, currentTechId, priority) {
        document.getElementById('assign-form').action = `<?= url('/admin/tickets') ?>/${ticketId}/assign`;
        document.getElementById('modal-ticket-title').textContent = `#${ticketId} - ${title}`;
        document.getElementById('modal-tech-select').value = currentTechId || '';
        document.getElementById('modal-priority-select').value = priority || 'normal';
        document.getElementById('assign-modal').classList.remove('hidden');
    }

    function openRejectModal(ticketId) {
        document.getElementById('reject-admin-form').action = `<?= url('/admin/tickets') ?>/${ticketId}/reject`;
        document.getElementById('reject-admin-modal').classList.remove('hidden');
    }
</script>
