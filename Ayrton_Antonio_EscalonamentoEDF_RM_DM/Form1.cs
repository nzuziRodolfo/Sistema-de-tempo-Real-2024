namespace EscalonamentoEDF_RM_DM
{
    public partial class Form1 : Form
    {

        private List<Tarefa> tarefas = new List<Tarefa>();
        private List<string> scheduleLog = new List<string>();
        private int simulationTime = 60; // Tempo total de simulação em ms
        private string tipoEscalocamentoSelected;

        public Form1()
        {
            InitializeComponent();
        }

        private void BtnAddTask_Click(object sender, EventArgs e)
        {
            // Adiciona uma linha vazia para nova tarefa
            dataGridViewTasks.Rows.Add();
        }


        private void BtnGenerateChart_Click(object sender, EventArgs e)
        {
            DrawGanttChart();
        }
        private void BtnStartScheduler_Click(object sender, EventArgs e)
        {
            tipoEscalocamentoSelected = "EDM";
            CarregarTarefasDoGrid();

            if (tarefas.Count == 0)
            {
                MessageBox.Show("Adicione pelo menos uma tarefa válida.");
                btnGenerateChart.Enabled = false;
                return;
            }

            // Executa o escalonador EDF
            RunEDFScheduler();

            btnGenerateChart.Enabled = true;

            foreach (var stap in scheduleLog)
            {
                listSimulationLog.Items.Add(stap);
            }

            MessageBox.Show("Escalonamento concluído! Clique em Gerar Gráfico para visualizar.");
        }

        private void CarregarTarefasDoGrid()
        {

            tarefas.Clear();
            scheduleLog.Clear();

            int.TryParse(txtSimulationTime.Text, out var time);
            simulationTime = time;
            
            // Adiciona tarefas a partir do DataGridView
            foreach (DataGridViewRow row in dataGridViewTasks.Rows)
            {
                if (row.Cells[0].Value != null &&
                    int.TryParse(row.Cells[1].Value?.ToString(), out int periodoCel) &&
                    int.TryParse(row.Cells[2].Value?.ToString(), out int execucaoCel) &&
                    int.TryParse(row.Cells[3].Value?.ToString(), out int deadlineCel))
                {
                    string nome = row.Cells[0].Value.ToString();
                    tarefas.Add(new Tarefa(tarefas.Count + 1, nome, periodoCel, execucaoCel, deadlineCel));
                }
            }
        }

        private void DrawGanttChart()
        {
            Graphics g = panelChart.CreateGraphics();
            g.Clear(Color.White);

            // Configurações do gráfico
            int scale = 10; // pixels por ms
            int heightPerTask = 30;
            int margin = 20;

            // Desenha eixos
            Pen axisPen = new Pen(Color.Black, 2);
            g.DrawLine(axisPen, margin, margin, margin, margin + tarefas.Count * heightPerTask);
            g.DrawLine(axisPen, margin, margin + tarefas.Count * heightPerTask,
                      margin + simulationTime * scale, margin + tarefas.Count * heightPerTask);

            // Desenha ticks e labels no eixo X (tempo)
            for (int time = 0; time <= simulationTime; time += 10)
            {
                g.DrawLine(Pens.Black, margin + time * scale, margin + tarefas.Count * heightPerTask,
                          margin + time * scale, margin + tarefas.Count * heightPerTask + 5);
                g.DrawString(time.ToString(), this.Font, Brushes.Black,
                            margin + time * scale - 10, margin + tarefas.Count * heightPerTask + 7);
            }

            // Cores para as tarefas
            Color[] taskColors = { Color.Red, Color.Blue, Color.Green, Color.Orange, Color.Purple };

            // Desenha barras para cada tarefa
            for (int i = 0; i < tarefas.Count; i++)
            {
                var task = tarefas[i];
                int y = margin + i * heightPerTask;

                // Nome da tarefa
                g.DrawString(task.Nome, this.Font, Brushes.Black, 5, y + 5);

                // Processa o log para encontrar períodos de execução
                bool isExecuting = false;
                int startTime = 0;

                for (int time = 0; time < simulationTime; time++)
                {
                    string logEntry = scheduleLog.FirstOrDefault(entry => entry.StartsWith($"Tempo {time}ms: Executando {task.Nome}"));

                    if (logEntry != null && !isExecuting)
                    {
                        // Início de execução
                        isExecuting = true;
                        startTime = time;
                    }
                    else if ((logEntry == null || !logEntry.Contains($"Executando {task.Nome}")) && isExecuting)
                    {
                        // Fim de execução
                        isExecuting = false;
                        int duration = time - startTime;

                        // Desenha a barra de execução
                        Brush taskBrush = new SolidBrush(taskColors[i % taskColors.Length]);
                        g.FillRectangle(taskBrush, margin + startTime * scale, y, duration * scale, heightPerTask - 5);
                        g.DrawRectangle(Pens.Black, margin + startTime * scale, y, duration * scale, heightPerTask - 5);
                    }
                }

                // Verifica se estava executando no final
                if (isExecuting)
                {
                    int duration = simulationTime - startTime;
                    Brush taskBrush = new SolidBrush(taskColors[i % taskColors.Length]);
                    g.FillRectangle(taskBrush, margin + startTime * scale, y, duration * scale, heightPerTask - 5);
                    g.DrawRectangle(Pens.Black, margin + startTime * scale, y, duration * scale, heightPerTask - 5);
                }
            }

            // Legenda
            for (int i = 0; i < tarefas.Count; i++)
            {
                Brush legendBrush = new SolidBrush(taskColors[i % taskColors.Length]);
                g.FillRectangle(legendBrush, margin + 400, margin + tarefas.Count * heightPerTask + 20 + i * 20, 15, 15);
                g.DrawString(tarefas[i].Nome, this.Font, Brushes.Black, margin + 420, margin + tarefas.Count * heightPerTask + 20 + i * 20);
            }

            // Título
            g.DrawString($"Diagrama de Gantt - Escalonamento {tipoEscalocamentoSelected}", new Font("Arial", 12, FontStyle.Bold),
                        Brushes.Black, margin + 200, 5);
        }

        private void dataGridView1_CellContentClick(object sender, DataGridViewCellEventArgs e)
        {

        }

        private void dataGridViewTasks_CellContentClick(object sender, DataGridViewCellEventArgs e)
        {

        }

        private void btnRMScheduler_Click(object sender, EventArgs e)
        {
            tipoEscalocamentoSelected = "RM";
            CarregarTarefasDoGrid();

            if (tarefas.Count == 0)
            {
                MessageBox.Show("Adicione pelo menos uma tarefa válida.");
                btnGenerateChart.Enabled = false;
                return;
            }

            // Executa o escalonador RM
            RunRMScheduler();

            // Habilita botão de gerar gráfico
            btnGenerateChart.Enabled = true;

            foreach (var stap in scheduleLog)
            {
                listSimulationLog.Items.Add(stap);
            }

            MessageBox.Show("Escalonamento RM concluído! Clique em Gerar Gráfico para visualizar.");
        }

        private void RunRMScheduler()
        {
            // Ordena tarefas por período (Rate Monotonic - menor período tem maior prioridade)
            tarefas = tarefas.OrderBy(t => t.Periodo).ToList();

            // Executa o escalonamento para o tempo de simulação
            for (int time = 0; time < simulationTime; time++)
            {
                // Verifica se alguma tarefa foi liberada
                foreach (var terefa in tarefas)
                {
                    if (terefa.ProximaLiberacao == time)
                    {
                        terefa.Reset();
                        scheduleLog.Add($"Tempo {time}ms: {terefa.Nome} liberada (Deadline: {terefa.DeadlineAbsoluto}ms)");
                    }
                }

                // Obtém tarefas prontas para executar (liberadas e com tempo restante)
                var tarefasProntas = tarefas.Where(t => t.TempoRestante > 0 && t.ProximaLiberacao <= time).ToList();

                if (tarefasProntas.Count > 0)
                {
                    // Ordena por período (RM - tarefas com menor período têm maior prioridade)
                    tarefasProntas = tarefasProntas.OrderBy(t => t.Periodo).ToList();

                    // Executa a tarefa com maior prioridade (menor período)
                    var tarefaExecutando = tarefasProntas[0];
                    tarefaExecutando.TempoRestante--;

                    scheduleLog.Add($"Tempo {time}ms: Executando {tarefaExecutando.Nome} (Tempo restante: {tarefaExecutando.TempoRestante}ms)");

                    // Verifica se a tarefa foi concluída
                    if (tarefaExecutando.TempoRestante == 0)
                    {
                        scheduleLog.Add($"Tempo {time}ms: {tarefaExecutando.Nome} concluída!");
                        tarefaExecutando.ProximaLiberacao += tarefaExecutando.Periodo;
                    }

                    // Verifica se alguma tarefa perdeu deadline
                    foreach (var terefa in tarefasProntas)
                    {
                        if (time > terefa.DeadlineAbsoluto)
                        {
                            scheduleLog.Add($"Tempo {time}ms: ALERTA - {terefa.Nome} perdeu deadline!");
                        }
                    }
                }
                else
                {
                    scheduleLog.Add($"Tempo {time}ms: CPU ociosa");
                }
            }
        }

        private void RunDMScheduler()
        {
            tarefas = tarefas.OrderBy(t => t.Deadline).ToList();

            for (int time = 0; time < simulationTime; time++)
            {
                foreach (var tarefa in tarefas)
                {
                    if (tarefa.ProximaLiberacao == time)
                    {
                        tarefa.Reset();
                        scheduleLog.Add($"Tempo {time}ms: {tarefa.Nome} liberada (Deadline: {tarefa.DeadlineAbsoluto}ms)");
                    }
                }

                var tarefasProntas = tarefas.Where(t => t.TempoRestante > 0 && t.ProximaLiberacao <= time).ToList();

                if (tarefasProntas.Count > 0)
                {
                    tarefasProntas = tarefasProntas.OrderBy(t => t.Deadline).ToList();
                    var tarefaExecutando = tarefasProntas[0];
                    tarefaExecutando.TempoRestante--;

                    scheduleLog.Add($"Tempo {time}ms: Executando {tarefaExecutando.Nome} (Tempo restante: {tarefaExecutando.TempoRestante}ms)");

                    if (tarefaExecutando.TempoRestante == 0)
                    {
                        scheduleLog.Add($"Tempo {time}ms: {tarefaExecutando.Nome} concluída!");
                        tarefaExecutando.ProximaLiberacao += tarefaExecutando.Periodo;
                    }

                    foreach (var task in tarefasProntas)
                    {
                        if (time > task.DeadlineAbsoluto)
                        {
                            scheduleLog.Add($"Tempo {time}ms: ALERTA - {task.Nome} perdeu deadline!");
                        }
                    }
                }
                else
                {
                    scheduleLog.Add($"Tempo {time}ms: CPU ociosa");
                }
            }
        }
        private void RunEDFScheduler()
        {
            // Ordena tarefas pelo próximo tempo de liberação
            tarefas = tarefas.OrderBy(t => t.ProximaLiberacao).ToList();

            // Executa o escalonamento para o tempo de simulação
            for (int time = 0; time < simulationTime; time++)
            {
                // Verifica se alguma tarefa foi liberada
                foreach (var tarefa in tarefas)
                {
                    if (tarefa.ProximaLiberacao == time)
                    {
                        tarefa.Reset();
                        scheduleLog.Add($"Tempo {time}ms: {tarefa.Nome} liberada (Deadline: {tarefa.DeadlineAbsoluto}ms)");
                    }
                }

                // Obtém tarefas prontas para executar (liberadas e com tempo restante)
                var tarefasProntas = tarefas.Where(t => t.TempoRestante > 0 && t.ProximaLiberacao <= time).ToList();

                if (tarefasProntas.Count > 0)
                {
                    // Ordena por deadline absoluta (EDF)
                    tarefasProntas = tarefasProntas.OrderBy(t => t.DeadlineAbsoluto).ToList();

                    // Executa a tarefa com deadline mais próxima
                    var tarefaExecutando = tarefasProntas[0];
                    tarefaExecutando.TempoRestante--;

                    scheduleLog.Add($"Tempo {time}ms: Executando {tarefaExecutando.Nome} (Tempo restante: {tarefaExecutando.TempoRestante}ms)");

                    // Verifica se a tarefa foi concluída
                    if (tarefaExecutando.TempoRestante == 0)
                    {
                        scheduleLog.Add($"Tempo {time}ms: {tarefaExecutando.Nome} concluída!");
                        tarefaExecutando.ProximaLiberacao += tarefaExecutando.Periodo;
                    }

                    // Verifica se alguma tarefa perdeu deadline
                    foreach (var tarefa in tarefasProntas)
                    {
                        if (time > tarefa.DeadlineAbsoluto)
                        {
                            scheduleLog.Add($"Tempo {time}ms: ALERTA - {tarefa.Nome} perdeu deadline!");
                        }
                    }
                }
                else
                {
                    scheduleLog.Add($"Tempo {time}ms: CPU ociosa");
                }
            }
        }

        private Color GetColorForIndex(int index)
        {
            Color[] predefinedColors = { Color.Red, Color.Blue, Color.Green, Color.Orange, Color.Purple, Color.Teal, Color.Brown };
            if (index < predefinedColors.Length)
                return predefinedColors[index];
            return Color.FromArgb(255, (index * 40) % 255, (index * 80) % 255, (index * 120) % 255);
        }

        private void btnDMScheduler_Click(object sender, EventArgs e)
        {
            tipoEscalocamentoSelected = "DM";
            CarregarTarefasDoGrid();

            if (tarefas.Count == 0)
            {
                MessageBox.Show("Adicione pelo menos uma tarefa válida.");
                btnGenerateChart.Enabled = false;
                return;
            }

            // Executa o escalonador RM
            RunDMScheduler();

            // Habilita botão de gerar gráfico
            btnGenerateChart.Enabled = true;

            foreach (var stap in scheduleLog)
            {
                listSimulationLog.Items.Add(stap);
            }

            MessageBox.Show("Escalonamento DM concluído! Clique em Gerar Gráfico para visualizar.");
        }
    }
    
}
