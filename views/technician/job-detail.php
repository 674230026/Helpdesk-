<?php
$statusEnum = \App\Enums\TicketStatus::tryFrom($ticket['status']);
$priorityEnum = \App\Enums\TicketPriority::tryFrom($ticket['priority']);
$isOverdue = !empty($ticket['is_overdue']);
?>

<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="<?= url('/technician/dashboard') ?>" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-800">ใบงาน #<?= $ticket['id'] ?></span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full <?= $priorityEnum ? $priorityEnum->badgeClass() : '' ?>">
                        <?= $priorityEnum ? $priorityEnum->label() : $ticket['priority'] ?>
                    </span>
                    <?php if ($isOverdue): ?>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-rose-600 text-white animate-pulse">
                            ⚠️ เกินกำหนดเวลา SLA
                        </span>
                    <?php endif; ?>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-1"><?= htmlspecialchars($ticket['title']) ?></h1>
                <p class="text-xs font-semibold text-indigo-700 mt-0.5">
                    📍 สถานที่: <?= htmlspecialchars($ticket['location']) ?>
                </p>
            </div>
        </div>

        <!-- Quick Status Control Panel -->
        <div class="flex items-center gap-2 flex-wrap justify-end">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold <?= $statusEnum ? $statusEnum->badgeClass() : '' ?>">
                <span class="w-2 h-2 rounded-full <?= $statusEnum ? $statusEnum->dotColor() : '' ?>"></span>
                <?= $statusEnum ? $statusEnum->label() : $ticket['status'] ?>
            </span>

            <?php if ($ticket['status'] === 'assigned'): ?>
                <form action="<?= url('/technician/jobs/' . $ticket['id'] . '/en-route') ?>" method="POST" class="inline">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition">
                        🚗 กำลังเดินทาง
                    </button>
                </form>
            <?php endif; ?>

            <?php if (in_array($ticket['status'], ['assigned', 'en_route', 'waiting_parts'], true)): ?>
                <form action="<?= url('/technician/jobs/' . $ticket['id'] . '/start') ?>" method="POST" class="inline">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition">
                        🔧 เริ่มลงมือซ่อม
                    </button>
                </form>
            <?php endif; ?>

            <?php if ($ticket['status'] === 'in_progress'): ?>
                <button onclick="document.getElementById('part-modal').classList.remove('hidden')" 
                        class="px-3 py-1.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs shadow-xs transition">
                    📦 ขอเบิกอะไหล่
                </button>
                <button onclick="document.getElementById('close-job-modal').classList.remove('hidden')" 
                        class="px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition">
                    ✅ บันทึกผล &amp; ส่งมอบงาน
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Ticket Information, Parts & Work Execution -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Detail Breakdown -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">ข้อมูลปัญหาจากผู้แจ้ง</h3>
                    <span class="text-xs text-slate-600">หมวดหมู่: <strong class="text-slate-800"><?= htmlspecialchars($ticket['category_name']) ?></strong></span>
                </div>
                <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed">
                    <?= nl2br(htmlspecialchars($ticket['description'])) ?>
                </div>
            </div>

            <!-- Spare Parts Requisition Section -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        📦 รายการอะไหล่ที่ขอเบิกใช้งาน (<?= count($partsUsed) ?> รายการ)
                    </h3>
                    <?php if (in_array($ticket['status'], ['in_progress', 'waiting_parts'], true)): ?>
                        <button onclick="document.getElementById('part-modal').classList.remove('hidden')" 
                                class="text-xs px-3 py-1 rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-700 font-bold border border-orange-200">
                            + ขอเบิกเพิ่ม
                        </button>
                    <?php endif; ?>
                </div>

                <div class="p-6">
                    <?php if (empty($partsUsed)): ?>
                        <p class="text-xs text-slate-400 text-center py-2">ยังไม่มีการขอเบิกอะไหล่สำหรับใบงานนี้</p>
                    <?php else: ?>
                        <div class="space-y-2">
                            <?php foreach ($partsUsed as $pu): ?>
                                <div class="flex items-center justify-between text-xs bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <div>
                                        <div class="font-bold text-slate-900"><?= htmlspecialchars($pu['part_name']) ?></div>
                                        <div class="text-slate-400 text-[11px] font-mono">รหัส: <?= htmlspecialchars($pu['part_code']) ?> | ราคา: <?= number_format($pu['unit_price'], 2) ?> ฿</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold text-slate-800 text-sm"><?= $pu['quantity'] ?> ชิ้น</span>
                                        <?php if ($pu['status'] === 'approved'): ?>
                                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">✓ อนุมัติแล้ว</span>
                                        <?php elseif ($pu['status'] === 'rejected'): ?>
                                            <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold text-[10px]">✕ ไม่อนุมัติ</span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">⏳ รอหัวหน้างานอนุมัติ</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Existing Repair Log (If resolved) -->
            <?php if ($repairLog): ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 text-xs space-y-3">
                <div class="flex items-center justify-between border-b border-emerald-200 pb-2">
                    <span class="font-bold text-emerald-900 text-sm">📋 บันทึกการซ่อมแซมและส่งมอบงาน</span>
                    <span class="font-semibold text-emerald-700">เวลาซ่อมจริง: <?= $repairLog['actual_hours'] ?> ชม.</span>
                </div>
                <div>
                    <span class="font-bold text-emerald-800">สาเหตุจริง:</span>
                    <p class="text-slate-700 mt-0.5 bg-white p-2 rounded-lg"><?= nl2br(htmlspecialchars($repairLog['root_cause'])) ?></p>
                </div>
                <div>
                    <span class="font-bold text-emerald-800">วิธีแก้ไข:</span>
                    <p class="text-slate-700 mt-0.5 bg-white p-2 rounded-lg"><?= nl2br(htmlspecialchars($repairLog['solution_note'])) ?></p>
                </div>
                <?php if (!empty($repairLog['proof_image_path'])): ?>
                <div>
                    <span class="font-bold text-emerald-800">รูปภาพผลงานหลังซ่อม:</span>
                    <a href="<?= asset('/' . ltrim($repairLog['proof_image_path'], '/')) ?>" target="_blank" class="block mt-1">
                        <img src="<?= asset('/' . ltrim($repairLog['proof_image_path'], '/')) ?>" alt="Proof" class="max-h-48 rounded-lg border border-emerald-200">
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Comments Thread -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800">
                        💬 บันทึกความเห็น &amp; สื่อสารกับผู้แจ้ง (<?= count($comments) ?>)
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <?php foreach ($comments as $c): 
                        $isTech = $c['user_role'] === 'technician' || $c['user_role'] === 'admin';
                        $roleEnum = \App\Enums\UserRole::tryFrom($c['user_role']);
                    ?>
                        <div class="flex flex-col <?= $isTech ? 'items-end' : 'items-start' ?>">
                            <div class="flex items-center gap-2 mb-1 text-[11px] text-slate-400">
                                <span class="font-bold text-slate-700"><?= htmlspecialchars($c['user_name']) ?></span>
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-[10px] font-semibold text-slate-600"><?= $roleEnum ? $roleEnum->label() : $c['user_role'] ?></span>
                                <span><?= date('d/m H:i', strtotime($c['created_at'])) ?></span>
                            </div>
                            <div class="max-w-lg rounded-2xl px-4 py-3 text-sm shadow-xs <?= $isTech ? 'bg-amber-500 text-white rounded-tr-none' : 'bg-slate-100 text-slate-800 rounded-tl-none' ?>">
                                <p class="whitespace-pre-wrap leading-relaxed"><?= htmlspecialchars($c['body']) ?></p>
                                <?php if (!empty($c['image_path'])): ?>
                                    <div class="mt-2">
                                        <a href="<?= asset('/' . ltrim($c['image_path'], '/')) ?>" target="_blank">
                                            <img src="<?= asset('/' . ltrim($c['image_path'], '/')) ?>" alt="Attached image" class="max-h-48 rounded-lg object-cover">
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($ticket['status'] !== 'closed' && $ticket['status'] !== 'cancelled'): ?>
                        <form action="<?= url('/technician/jobs/' . $ticket['id'] . '/comments') ?>" method="POST" enctype="multipart/form-data" class="mt-4 pt-4 border-t border-slate-100 space-y-3">
                            <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
                            <textarea name="body" rows="2" placeholder="พิมพ์ข้อความตอบกลับผู้แจ้ง หรือบันทึกโน้ตหน้างาน..." 
                                      class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500"></textarea>
                            <div class="flex justify-between items-center">
                                <label class="text-xs text-slate-500 cursor-pointer">
                                    <input type="file" name="image" class="hidden" accept="image/*">
                                    <span>📎 แนบรูปภาพ</span>
                                </label>
                                <button type="submit" class="px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition">
                                    ส่งข้อความ
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right: Contact, SLA & Timeline Logs -->
        <div class="space-y-6">
            <!-- Contact Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4 text-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">ข้อมูลผู้แจ้ง &amp; สถานที่</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-sm">
                        👤
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($ticket['user_name']) ?></h4>
                        <p class="text-slate-500"><?= htmlspecialchars($ticket['user_email']) ?></p>
                    </div>
                </div>
                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-slate-500">สถานที่:</span>
                        <span class="text-slate-800 font-bold text-right"><?= htmlspecialchars($ticket['location']) ?></span>
                    </div>
                    <?php if ($ticket['sla_due_at']): ?>
                    <div class="flex justify-between <?= $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-600' ?>">
                        <span>กำหนดเวลา SLA:</span>
                        <span><?= date('d/m/Y H:i', strtotime($ticket['sla_due_at'])) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Status Logs -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">ประวัติการเปลี่ยนสถานะ</h3>
                <div class="space-y-4">
                    <?php foreach ($logs as $log): 
                        $toStatusEnum = \App\Enums\TicketStatus::tryFrom($log['to_status']);
                    ?>
                        <div class="flex items-start gap-3 text-xs border-l-2 border-amber-400 pl-3 py-0.5">
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-slate-800"><?= $toStatusEnum ? $toStatusEnum->label() : htmlspecialchars($log['to_status']) ?></span>
                                    <span class="text-slate-400 text-[10px]"><?= date('d/m H:i', strtotime($log['created_at'])) ?></span>
                                </div>
                                <p class="text-slate-500 text-[11px] mt-0.5">โดย: <?= htmlspecialchars($log['changed_by_name']) ?></p>
                                <?php if (!empty($log['note'])): ?>
                                    <p class="text-slate-700 bg-slate-50 p-1.5 rounded mt-1 text-[11px]"><?= htmlspecialchars($log['note']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Spare Part Requisition -->
<div id="part-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-xs">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900">📦 ขอเบิกอะไหล่ / อุปกรณ์</h3>
            <button onclick="document.getElementById('part-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="<?= url('/technician/jobs/' . $ticket['id'] . '/request-part') ?>" method="POST" class="space-y-3">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">เลือกรายการอะไหล่ <span class="text-rose-500">*</span></label>
                <select name="part_id" required class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs">
                    <option value="">-- เลือกอะไหล่จากคลัง --</option>
                    <?php foreach ($spareParts as $sp): ?>
                        <option value="<?= $sp['id'] ?>">
                            <?= htmlspecialchars($sp['name']) ?> (คงเหลือ: <?= $sp['stock_quantity'] ?> ชิ้น | <?= number_format($sp['unit_price'], 2) ?> ฿)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">จำนวนที่ต้องการเบิก <span class="text-rose-500">*</span></label>
                <input type="number" name="quantity" min="1" value="1" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
            </div>
            <p class="text-[11px] text-slate-400">คำขอเบิกจะถูกส่งไปยังหัวหน้างานเพื่อพิจารณาอนุมัติและตัดสต็อก</p>
            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('part-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600">ยกเลิก</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold shadow-md">ส่งคำขอเบิก</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Log & Close Job (Repair Proof & Solution) -->
<div id="close-job-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 text-xs">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900">📋 บันทึกการซ่อมจริง &amp; ส่งมอบงาน</h3>
            <button onclick="document.getElementById('close-job-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="<?= url('/technician/jobs/' . $ticket['id'] . '/close') ?>" method="POST" enctype="multipart/form-data" class="space-y-3">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Auth::csrfToken() ?>">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">สาเหตุจริงของปัญหา <span class="text-rose-500">*</span></label>
                <textarea name="root_cause" rows="2" required placeholder="เช่น ซีลยางวาล์วน้ำเสื่อมสภาพ, คอยล์ร้อนมีฝุ่นอุดตัน, สายต่อการ์ดจอหลวม..." 
                          class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">วิธีการแก้ไขปัญหา <span class="text-rose-500">*</span></label>
                <textarea name="solution_note" rows="3" required placeholder="อธิบายวิธีแก้ไข เช่น ทำการเปลี่ยนวาล์วตัวใหม่, ล้างแผงฟิลเตอร์และเติมน้ำยาแอร์, ทดสอบเปิดเครื่องต่อเนื่อง 1 ชม..." 
                          class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">เวลาที่ใช้ซ่อมจริง (ชม.)</label>
                <input type="number" step="0.25" min="0.25" name="actual_hours" value="1.00" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
            </div>
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">รูปถ่ายหลังซ่อมเสร็จ</label>
                <input type="file" name="proof_photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700">
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('close-job-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600">ยกเลิก</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md">บันทึกผล &amp; ปิดงานส่งมอบ</button>
            </div>
        </form>
    </div>
</div>
