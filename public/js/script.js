// dashboard script: charts, interactions, animations
// Data untuk setiap mata kuliah (counts untuk pie, percentages for monthly)
const courseData = {
    pemrograman_web: {
        name: 'Pemrograman Web',
        pie: [14, 2, 1, 1], // Hadir, Izin, Sakit, Alpha (total 18)
        monthly: [85, 80, 90, 82, 88, 92, 85, 80, 87, 90, 88, 85]
    },
    basis_data: {
        name: 'Basis Data Lanjut',
        pie: [12, 3, 2, 1],
        monthly: [78, 75, 82, 80, 85, 88, 83, 79, 84, 86, 85, 82]
    },
    jaringan: {
        name: 'Jaringan Komputer',
        pie: [15, 1, 1, 1],
        monthly: [88, 82, 91, 85, 90, 93, 87, 83, 89, 92, 90, 88]
    }
};

function average(arr) {
    if (!arr || !arr.length) return 0;
    return Math.round(arr.reduce((s, v) => s + v, 0) / arr.length);
}

const initialCourse = 'pemrograman_web';
let attendanceChart = null, monthlyChart = null;

function animateNumber(el, target, opts = {}) {
    if (!el) return;
    const suffix = opts.suffix || '';
    const duration = opts.duration || 700;
    const start = Number(el.dataset.current ? el.dataset.current : (el.textContent.replace('%', '') || 0));
    const startTime = performance.now();

    function step(now) {
        const progress = Math.min((now - startTime) / duration, 1);
        const value = Math.round(start + (target - start) * progress);
        el.textContent = value + (suffix || '');
        if (progress < 1) requestAnimationFrame(step);
        else el.dataset.current = target;
    }
    requestAnimationFrame(step);
}

function initializeCharts() {
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    attendanceChart = new Chart(attendanceCtx, {
        type: 'pie',
        data: {
            labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
            datasets: [{
                data: courseData[initialCourse].pie.slice(),
                backgroundColor: ['#2ecc71', '#3498db', '#f39c12', '#e74c3c'],
                borderColor: 'transparent',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: 6 },
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 8, font: { size: 12 } } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed || 0;
                            const dataArr = context.dataset.data || [];
                            const total = dataArr.reduce((a, b) => a + b, 0);
                            const pct = total ? Math.round((value / total) * 100) : 0;
                            return `${context.label}: ${value} dari ${total} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });

    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Persentase Kehadiran',
                data: courseData[initialCourse].monthly.slice(),
                borderColor: '#0b6fa6',
                backgroundColor: 'rgba(11,111,166,0.08)',
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#0b6fa6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: false, min: 60, max: 100, ticks: { callback: v => v + '%' } }
            },
            plugins: {
                tooltip: { callbacks: { label: ctx => `Kehadiran: ${ctx.parsed.y}%` } }
            }
        }
    });
}

function updateForCourse(key) {
    const data = courseData[key];
    if (!data) return;
    attendanceChart.data.datasets[0].data = data.pie.slice();
    attendanceChart.update();
    monthlyChart.data.datasets[0].data = data.monthly.slice();
    monthlyChart.update();

    const statPctEl = document.getElementById('stat1');
    const statLabelEl = document.getElementById('stat1-label');
    const avg = average(data.monthly);
    if (statPctEl) animateNumber(statPctEl, avg, { suffix: '%' });
    if (statLabelEl) statLabelEl.textContent = data.name;
}

function setupSidebarToggle() {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    if (!sidebar || !toggleBtn) return;
    if (localStorage.getItem('sidebarCollapsed') === 'true') sidebar.classList.add('collapsed');
    toggleBtn.addEventListener('click', () => {
        const isCollapsed = sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed ? 'true' : 'false');
    });
}

function hookAbsenButtons() {
    document.querySelectorAll('.btn.btn-success[data-matkul]').forEach(btn => {
        btn.addEventListener('click', function() {
            const matkul = this.getAttribute('data-matkul');
            // Simple UX: disable button and show confirmation (replace with real AJAX later)
            if (!confirm(`Konfirmasi: Absen untuk ${matkul}?`)) return;
            this.disabled = true;
            this.textContent = 'Terkirim';
            this.classList.remove('btn-success');
            this.classList.add('btn-primary');
            // TODO: call backend endpoint to record attendance
            console.log('Absen dikirim untuk', matkul);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    try {
        initializeCharts();
    } catch (e) {
        console.error('Chart init error', e);
    }

    // initial UI
    updateForCourse(initialCourse);

    // animate other stats
    document.querySelectorAll('.quick-stats h3[data-target]').forEach(el => {
        const t = Number(el.getAttribute('data-target')) || 0;
        if (el.id !== 'stat1') animateNumber(el, t);
    });

    // handlers
    const select = document.getElementById('courseSelect');
    if (select) select.addEventListener('change', function() { updateForCourse(this.value); });
    setupSidebarToggle();
    hookAbsenButtons();
});