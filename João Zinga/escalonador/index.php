<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Simulador de Escalonamento de Tarefas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        }
        .glass-effect {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }
        .neon-glow {
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
        }
        .code-input {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(148, 163, 184, 0.3);
            color: #e2e8f0;
        }
        .code-input:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
        }
    </style>
</head>
<body class="text-slate-200 min-h-screen flex flex-col items-center p-4">
    <h1 class="text-4xl font-bold mb-8 text-green-400 drop-shadow-lg text-center">
        <span class="text-cyan-400">{'</span>
         Simulador de Escalonamento de Tarefas
        <span class="text-cyan-400">}</span>
    </h1>

    <!-- Formulário para adicionar tarefas -->
    <div class="glass-effect rounded-lg p-6 mb-8 w-full max-w-4xl">
        <h2 class="text-2xl font-semibold mb-4 text-green-300"> // Adicionar Tarefa</h2>
        <form id="taskForm" class="flex flex-wrap gap-3 justify-center">
            <input type="text" name="nome" placeholder="Nome da Tarefa" required 
                   class="code-input px-4 py-2 rounded focus:outline-none">
            <input type="number" name="periodo" placeholder="Período (T)" required min="1" 
                   class="code-input px-4 py-2 rounded focus:outline-none">
            <input type="number" name="tempo_execucao" placeholder="Tempo Exec. (C)" required min="1" 
                   class="code-input px-4 py-2 rounded focus:outline-none">
            <input type="number" name="deadline" placeholder="Deadline (D)" required min="1" 
                   class="code-input px-4 py-2 rounded focus:outline-none">
            <button type="submit" class="bg-green-600 text-black font-bold px-6 py-2 rounded neon-glow hover:bg-green-500 transition-all duration-300">
                ➕ adicionar_tarefa()
            </button>
        </form>
    </div>

    <!-- Controles de simulação -->
    <div class="glass-effect rounded-lg p-6 mb-8 w-full max-w-4xl">
        <h2 class="text-2xl font-semibold mb-4 text-green-300"> // Configurações de Simulação</h2>
        <div class="flex flex-wrap gap-4 items-center justify-center mb-4">
            <div class="flex items-center gap-2">
                <label class="font-medium text-slate-300">⏱ tempo_simulação:</label>
                <input type="number" id="tempo_simulacao" value="0" min="0" 
                       class="code-input px-3 py-2 rounded w-24 focus:outline-none">
            </div>
            <div class="flex items-center gap-2">
                <label class="font-medium text-slate-300">🔄 algoritmo:</label>
                <select id="algoritmo" class="code-input px-3 py-2 rounded focus:outline-none">
                    <option value="RM">Rate Monotonic (RM)</option>
                    <option value="DM">Deadline Monotonic (DM)</option>
                    <option value="EDF">Earliest Deadline First (EDF)</option>
                </select>
            </div>
        </div>
        <div class="flex flex-wrap gap-4 justify-center">
            <button onclick="gerar()" class="bg-cyan-600 text-black font-bold px-6 py-2 rounded hover:bg-cyan-500 transition-all duration-300" style="box-shadow: 0 0 20px rgba(34, 211, 238, 0.3);">
                📊 executar_simulação()
            </button>
            <button onclick="limparTarefas()" class="bg-red-600 text-white font-bold px-6 py-2 rounded hover:bg-red-500 transition-all duration-300" style="box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);">
                🗑️ limpar_tudo()
            </button>
        </div>
    </div>

    <!-- Tabela de tarefas -->
    <div class="glass-effect rounded-lg p-6 mb-8 w-full max-w-4xl">
        <h2 class="text-2xl font-semibold mb-4 text-green-300"> // Tabela</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-center table-auto border-collapse">
                <thead class="bg-slate-800 text-green-400">
                    <tr>
                        <th class="p-3 border border-slate-600">Tarefa</th>
                        <th class="p-3 border border-slate-600">Período [Ti]</th>
                        <th class="p-3 border border-slate-600">Tempo_execução [Ci]</th>
                        <th class="p-3 border border-slate-600">Deadline [Di]</th>
                        <th class="p-3 border border-slate-600">Taxa_de_utilização [Un]</th>
                        <th class="p-3 border border-slate-600">acções</th>
                    </tr>
                </thead>
                <tbody id="taskTable" class="text-slate-200"></tbody>
            </table>
        </div>
    </div>

    <!-- Informações da simulação -->
    <div id="infoSimulacao" class="glass-effect rounded-lg p-6 mb-8 w-full max-w-4xl hidden">
        <h2 class="text-2xl font-semibold mb-4 text-green-300"> // Resultados da Simulação</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 text-center">
            <div class="bg-slate-800 p-4 rounded-lg border border-cyan-500/30">
                <div class="text-2xl font-bold text-cyan-400" id="macroCiclo">-</div>
                <div class="text-sm text-slate-400">macro_ciclo</div>
            </div>
            <div class="bg-slate-800 p-4 rounded-lg border border-green-500/30">
                <div class="text-2xl font-bold text-green-400" id="microCiclo">-</div>
                <div class="text-sm text-slate-400">micro_ciclo</div>
            </div>
            <div class="bg-slate-800 p-4 rounded-lg border border-purple-500/30">
                <div class="text-2xl font-bold text-purple-400" id="utilizacao">-</div>
                <div class="text-sm text-slate-400">Taxa de utilizacao</div>
            </div>
            <div class="bg-slate-800 p-4 rounded-lg border border-yellow-500/30">
                <div class="text-2xl font-bold text-yellow-400" id="algoritmoUsado">-</div>
                <div class="text-sm text-slate-400">algoritmo</div>
            </div>
            <div class="bg-slate-800 p-4 rounded-lg border border-orange-500/30">
                <div class="text-2xl font-bold" id="escalonavel">-</div>
                <div class="text-sm text-slate-400">escalonável</div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-center mt-4">
            <div class="bg-slate-800 p-4 rounded-lg border border-slate-500/30">
                <div class="text-2xl font-bold text-slate-300" id="tempoSimulacao">-</div>
                <div class="text-sm text-slate-400">tempo_simulação</div>
            </div>
            <div class="bg-slate-800 p-4 rounded-lg border border-indigo-500/30">
                <div class="text-2xl font-bold text-indigo-400" id="limiteLiuLayland">-</div>
                <div class="text-sm text-slate-400">limite_liu_layland</div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Gantt -->
    <div id="graficoContainer" class="w-full max-w-6xl glass-effect rounded-lg p-6 hidden">
        <h2 class="text-2xl font-semibold mb-4 text-green-300"> // Diagrama de Gantt</h2>
        <div class="relative bg-slate-900 rounded-lg p-4">
            <canvas id="ganttChart" height="400"></canvas>
        </div>
    </div>

    <script>
        let tarefas = [];
        let chart;

        // Adicionar tarefa
        document.getElementById('taskForm').onsubmit = function(e) {
            e.preventDefault();
            const form = new FormData(e.target);
            const tarefa = {
                nome: form.get('nome'),
                periodo: parseInt(form.get('periodo')),
                tempo_execucao: parseInt(form.get('tempo_execucao')),
                deadline: parseInt(form.get('deadline'))
            };
            
            // Validações
            if (tarefa.tempo_execucao > tarefa.periodo) {
                alert('// ERRO: Tempo de execução não pode ser maior que o período!');
                return;
            }
            if (tarefa.deadline > tarefa.periodo) {
                alert('// ERRO: Prazo não pode ser maior que o período!');
                return;
            }
            
            tarefas.push(tarefa);
            atualizarTabelaTarefas();
            e.target.reset();
        };

        function atualizarTabelaTarefas() {
            const tbody = document.getElementById("taskTable");
            tbody.innerHTML = '';
            
            tarefas.forEach((tarefa, index) => {
                const utilizacao = (tarefa.tempo_execucao / tarefa.periodo).toFixed(4);
                const row = document.createElement("tr");
                row.className = "hover:bg-slate-700/50 border-b border-slate-600";
                row.innerHTML = `
                    <td class='p-3 border border-slate-600 font-mono text-cyan-300'>${tarefa.nome}</td>
                    <td class='p-3 border border-slate-600 font-mono'>${tarefa.periodo}</td>
                    <td class='p-3 border border-slate-600 font-mono'>${tarefa.tempo_execucao}</td>
                    <td class='p-3 border border-slate-600 font-mono'>${tarefa.deadline}</td>
                    <td class='p-3 border border-slate-600 font-mono text-purple-300'>${utilizacao}</td>
                    <td class='p-3 border border-slate-600'>
                        <button onclick="removerTarefa(${index})" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-500 transition-colors font-mono">
                            eliminar(${index})
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function removerTarefa(index) {
            tarefas.splice(index, 1);
            atualizarTabelaTarefas();
        }

        function limparTarefas() {
            tarefas = [];
            atualizarTabelaTarefas();
            document.getElementById('infoSimulacao').classList.add('hidden');
            document.getElementById('graficoContainer').classList.add('hidden');
        }

        function gerar() {
            if (tarefas.length === 0) {
                alert('// ERRO: Adicione pelo menos uma tarefa!');
                return;
            }

            const tempo_simulacao = parseInt(document.getElementById('tempo_simulacao').value) || 0;
            const algoritmo = document.getElementById('algoritmo').value;

            fetch('escalonador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    tarefas,
                    tempo_simulacao,
                    algoritmo
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert('// ERRO: ' + data.error);
                    return;
                }
                atualizarInfoSimulacao(data, algoritmo);
                desenharGrafico(data);
            })
            .catch(error => {
                console.error('// ERRO:', error);
                alert('// ERRO: Erro ao processar simulação');
            });
        }

        function atualizarInfoSimulacao(data, algoritmo) {
            document.getElementById('macroCiclo').textContent = data.macro_ciclo;
            document.getElementById('microCiclo').textContent = data.micro_ciclo;
            document.getElementById('utilizacao').textContent = data.utilizacao;
            document.getElementById('algoritmoUsado').textContent = algoritmo;
            document.getElementById('tempoSimulacao').textContent = data.tempo_simulacao;
            document.getElementById('limiteLiuLayland').textContent = data.limite_liu_layland || 'N/A';
            
            const escalonavel = data.escalonavel;
            const elemEscalonavel = document.getElementById('escalonavel');
            if (escalonavel) {
                elemEscalonavel.textContent = 'cumpre';
                elemEscalonavel.className = 'text-2xl font-bold text-green-400';
            } else {
                elemEscalonavel.textContent = 'não_cumpre';
                elemEscalonavel.className = 'text-2xl font-bold text-red-400';
            }
            
            document.getElementById('infoSimulacao').classList.remove('hidden');
        }

        function desenharGrafico(data) {
            const ctx = document.getElementById('ganttChart').getContext('2d');
            const eventos = data.eventos;
            
            if (eventos.length === 0) {
                alert('// AVISO: Nenhum evento para exibir no gráfico!');
                return;
            }

            // Paleta de cores neon para tema escuro
            const cores = {};
            const paleta = [
                '#22c55e', '#3b82f6', '#ef4444', '#f59e0b', 
                '#6366f1', '#ec4899', '#8b5cf6', '#06b6d4',
                '#84cc16', '#f97316', '#64748b', '#dc2626'
            ];
            
            const tarefasUnicas = [...new Set(eventos.map(e => e.tarefa))];
            tarefasUnicas.forEach((t, i) => {
                cores[t] = paleta[i % paleta.length];
            });

            // Preparar dados para Chart.js
            const datasets = tarefasUnicas.map(tarefa => ({
                label: tarefa,
                data: eventos
                    .filter(e => e.tarefa === tarefa)
                    .map(e => ({
                        x: [e.inicio, e.fim],
                        y: tarefa
                    })),
                backgroundColor: cores[tarefa] + '80',
                borderColor: cores[tarefa],
                borderWidth: 2,
                borderSkipped: false,
                borderRadius: 6,
                barThickness: 25
            }));

            // Destruir gráfico anterior se existir
            if (chart) {
                chart.destroy();
            }

            chart = new Chart(ctx, {
                type: 'bar',
                data: { datasets },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                font: { size: 12, family: 'Monaco, monospace' },
                                color: '#e2e8f0'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleColor: '#22c55e',
                            bodyColor: '#e2e8f0',
                            borderColor: '#22c55e',
                            borderWidth: 1,
                            callbacks: {
                                title: function(context) {
                                    return `// Tarefa: ${context[0].dataset.label}`;
                                },
                                label: function(context) {
                                    const inicio = context.raw.x[0];
                                    const fim = context.raw.x[1];
                                    const duracao = fim - inicio;
                                    return `execucao: [${inicio}, ${fim}] // duracao: ${duracao}`;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: `// Diagrama de Gantt - Algoritmo: ${document.getElementById('algoritmo').value}`,
                            font: { size: 18, weight: 'bold', family: 'Monaco, monospace' },
                            color: '#22c55e'
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'unidades_tempo [ms]',
                                font: { size: 14, weight: 'bold', family: 'Monaco, monospace' },
                                color: '#e2e8f0'
                            },
                            grid: {
                                display: true,
                                color: 'rgba(148, 163, 184, 0.2)'
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: { family: 'Monaco, monospace' }
                            }
                        },
                        y: {
                            type: 'category',
                            labels: tarefasUnicas,
                            title: {
                                display: true,
                                text: 'tarefas',
                                font: { size: 14, weight: 'bold', family: 'Monaco, monospace' },
                                color: '#e2e8f0'
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: { family: 'Monaco, monospace' }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutCubic'
                    }
                }
            });

            document.getElementById('graficoContainer').classList.remove('hidden');
        }
    </script>
</body>
</html>