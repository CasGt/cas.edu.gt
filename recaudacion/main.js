let total = 0;
let target = null;
let week = 0;
const maxWeeks = 15;

const chart = new Chart(document.getElementById('progressChart'), {
  type: 'bar',
  data: {
    labels: ['Total'],
    datasets: [
      {
        label: 'Progreso',
        data: [0],
        backgroundColor: 'rgba(54, 162, 235, 0.7)'
      },
      {
        label: 'Objetivo',
        type: 'line',
        data: [0],
        borderColor: 'red',
        borderWidth: 2,
        fill: false,
        pointRadius: 0,
        borderDash: [10,5]
      }
    ]
  },
  options: {
    scales: {
      y: { beginAtZero: true }
    },
    plugins: {
      legend: { position: 'top' },
      title: {
        display: true,
        text: 'Progreso Acumulado'
      }
    }
  }
});

function setTarget() {
  const input = document.getElementById('targetInput').value;
  if (input && !isNaN(input)) {
    target = parseFloat(input);
    chart.data.datasets[1].data = [target];
    document.getElementById('targetDisplay').textContent = target;
    document.getElementById('targetSection').classList.add('hidden');
    document.getElementById('trackingSection').classList.remove('hidden');
    chart.update();
  }
}

function addWeeklyValue() {
  const value = parseFloat(document.getElementById('valueInput').value);
  if (!isNaN(value) && week < maxWeeks) {
    total += value;
    week++;
    chart.data.datasets[0].data = [total];
    document.getElementById('totalDisplay').textContent = total;
    document.getElementById('progressBar').value = Math.min((total / target) * 100, 100);
    chart.update();

    // tabla
    const row = document.createElement('tr');
    row.innerHTML = `<td>Semana ${week}</td><td>${value}</td>`;
    document.getElementById('valueTableBody').appendChild(row);

    // guardar a base de datos
    fetch('save.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ semana: week, valor: value, total, objetivo: target })
    }).then(res => res.text()).then(console.log);
  }

  document.getElementById('valueInput').value = '';
}
