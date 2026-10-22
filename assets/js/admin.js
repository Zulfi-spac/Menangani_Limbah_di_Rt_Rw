document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.querySelector('#adminSidebar');
  const scrim = document.querySelector('.sidebar-scrim');
  const closeSidebar = () => { sidebar?.classList.remove('open'); scrim?.classList.remove('visible'); };
  document.querySelector('[data-sidebar-toggle]')?.addEventListener('click', () => {
    sidebar?.classList.toggle('open'); scrim?.classList.toggle('visible');
  });
  scrim?.addEventListener('click', closeSidebar);
  if (window.Chart && Array.isArray(window.wasteChartData)) {
    const canvas = document.querySelector('#wasteChart');
    if (canvas) {
      const labels = window.wasteChartData.map((row) => {
        const [year, month] = row.bulan.split('-');
        return new Intl.DateTimeFormat('id-ID', { month: 'short' }).format(new Date(Number(year), Number(month) - 1));
      });
      new Chart(canvas, {
        type: 'bar',
        data: { labels, datasets: [{ data: window.wasteChartData.map((row) => Number(row.jumlah)), backgroundColor: '#72a486', hoverBackgroundColor: '#286a50', borderRadius: 3, maxBarThickness: 34 }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false }, border: { display: false }, ticks: { color: '#87938b', font: { family: 'DM Sans', size: 10 } } }, y: { beginAtZero: true, grid: { color: '#edf0ed' }, border: { display: false }, ticks: { precision: 0, color: '#87938b', font: { family: 'DM Sans', size: 9 } } } } }
      });
    }
  }
});