using System.Windows.Forms;

namespace EscalonamentoEDF_RM_DM
{
    partial class Form1
    {


        private Button btnAddTask;
        private Button btnStartScheduler;
        private Button btnGenerateChart;
        private DataGridView dataGridViewTasks;
        private Panel panelChart;
        


        /// <summary>
        ///  Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        ///  Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        ///  Required method for Designer support - do not modify
        ///  the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            DataGridViewCellStyle dataGridViewCellStyle1 = new DataGridViewCellStyle();
            DataGridViewCellStyle dataGridViewCellStyle2 = new DataGridViewCellStyle();
            dataGridViewTasks = new DataGridView();
            dataGridViewTextBoxColumn1 = new DataGridViewTextBoxColumn();
            dataGridViewTextBoxColumn2 = new DataGridViewTextBoxColumn();
            dataGridViewTextBoxColumn3 = new DataGridViewTextBoxColumn();
            dataGridViewTextBoxColumn4 = new DataGridViewTextBoxColumn();
            btnAddTask = new Button();
            btnStartScheduler = new Button();
            btnGenerateChart = new Button();
            panelChart = new Panel();
            listSimulationLog = new ListBox();
            btnRMScheduler = new Button();
            label1 = new Label();
            txtSimulationTime = new TextBox();
            btnDMScheduler = new Button();
            ((System.ComponentModel.ISupportInitialize)dataGridViewTasks).BeginInit();
            SuspendLayout();
            // 
            // dataGridViewTasks
            // 
            dataGridViewCellStyle1.Alignment = DataGridViewContentAlignment.MiddleCenter;
            dataGridViewCellStyle1.BackColor = SystemColors.Control;
            dataGridViewCellStyle1.Font = new Font("Segoe UI", 9F);
            dataGridViewCellStyle1.ForeColor = SystemColors.WindowText;
            dataGridViewCellStyle1.SelectionBackColor = SystemColors.Highlight;
            dataGridViewCellStyle1.SelectionForeColor = SystemColors.HighlightText;
            dataGridViewCellStyle1.WrapMode = DataGridViewTriState.True;
            dataGridViewTasks.ColumnHeadersDefaultCellStyle = dataGridViewCellStyle1;
            dataGridViewTasks.ColumnHeadersHeight = 50;
            dataGridViewTasks.Columns.AddRange(new DataGridViewColumn[] { dataGridViewTextBoxColumn1, dataGridViewTextBoxColumn2, dataGridViewTextBoxColumn3, dataGridViewTextBoxColumn4 });
            dataGridViewCellStyle2.Alignment = DataGridViewContentAlignment.MiddleCenter;
            dataGridViewCellStyle2.BackColor = SystemColors.Window;
            dataGridViewCellStyle2.Font = new Font("Segoe UI", 9F);
            dataGridViewCellStyle2.ForeColor = SystemColors.ControlText;
            dataGridViewCellStyle2.SelectionBackColor = SystemColors.Highlight;
            dataGridViewCellStyle2.SelectionForeColor = SystemColors.HighlightText;
            dataGridViewCellStyle2.WrapMode = DataGridViewTriState.False;
            dataGridViewTasks.DefaultCellStyle = dataGridViewCellStyle2;
            dataGridViewTasks.Location = new Point(26, 27);
            dataGridViewTasks.Name = "dataGridViewTasks";
            dataGridViewTasks.RowHeadersWidth = 51;
            dataGridViewTasks.Size = new Size(433, 284);
            dataGridViewTasks.TabIndex = 0;
            dataGridViewTasks.CellContentClick += dataGridViewTasks_CellContentClick;
            // 
            // dataGridViewTextBoxColumn1
            // 
            dataGridViewTextBoxColumn1.HeaderText = "Tarefa";
            dataGridViewTextBoxColumn1.MinimumWidth = 6;
            dataGridViewTextBoxColumn1.Name = "dataGridViewTextBoxColumn1";
            dataGridViewTextBoxColumn1.Width = 110;
            // 
            // dataGridViewTextBoxColumn2
            // 
            dataGridViewTextBoxColumn2.HeaderText = "Período (T)";
            dataGridViewTextBoxColumn2.MinimumWidth = 6;
            dataGridViewTextBoxColumn2.Name = "dataGridViewTextBoxColumn2";
            dataGridViewTextBoxColumn2.Width = 90;
            // 
            // dataGridViewTextBoxColumn3
            // 
            dataGridViewTextBoxColumn3.HeaderText = "Tempo Execução (C)";
            dataGridViewTextBoxColumn3.MinimumWidth = 6;
            dataGridViewTextBoxColumn3.Name = "dataGridViewTextBoxColumn3";
            dataGridViewTextBoxColumn3.Width = 90;
            // 
            // dataGridViewTextBoxColumn4
            // 
            dataGridViewTextBoxColumn4.HeaderText = "Deadline (D)";
            dataGridViewTextBoxColumn4.MinimumWidth = 6;
            dataGridViewTextBoxColumn4.Name = "dataGridViewTextBoxColumn4";
            dataGridViewTextBoxColumn4.Width = 90;
            // 
            // btnAddTask
            // 
            btnAddTask.BackColor = SystemColors.ButtonFace;
            btnAddTask.Location = new Point(491, 27);
            btnAddTask.Name = "btnAddTask";
            btnAddTask.Size = new Size(211, 34);
            btnAddTask.TabIndex = 1;
            btnAddTask.Text = "Adicionar Tarefa";
            btnAddTask.UseVisualStyleBackColor = false;
            btnAddTask.Click += BtnAddTask_Click;
            // 
            // btnStartScheduler
            // 
            btnStartScheduler.BackColor = SystemColors.ButtonFace;
            btnStartScheduler.Location = new Point(491, 158);
            btnStartScheduler.Name = "btnStartScheduler";
            btnStartScheduler.Size = new Size(211, 34);
            btnStartScheduler.TabIndex = 2;
            btnStartScheduler.Text = "Iniciar Escalonador EDF";
            btnStartScheduler.UseVisualStyleBackColor = false;
            btnStartScheduler.Click += BtnStartScheduler_Click;
            // 
            // btnGenerateChart
            // 
            btnGenerateChart.BackColor = SystemColors.ButtonFace;
            btnGenerateChart.Location = new Point(491, 277);
            btnGenerateChart.Name = "btnGenerateChart";
            btnGenerateChart.Size = new Size(211, 34);
            btnGenerateChart.TabIndex = 3;
            btnGenerateChart.Text = "Gerar Gráfico";
            btnGenerateChart.UseVisualStyleBackColor = false;
            btnGenerateChart.Click += BtnGenerateChart_Click;
            // 
            // panelChart
            // 
            panelChart.Location = new Point(26, 338);
            panelChart.Name = "panelChart";
            panelChart.Size = new Size(1260, 360);
            panelChart.TabIndex = 4;
            // 
            // listSimulationLog
            // 
            listSimulationLog.FormattingEnabled = true;
            listSimulationLog.Location = new Point(722, 27);
            listSimulationLog.Name = "listSimulationLog";
            listSimulationLog.Size = new Size(564, 284);
            listSimulationLog.TabIndex = 5;
            // 
            // btnRMScheduler
            // 
            btnRMScheduler.BackColor = SystemColors.ButtonFace;
            btnRMScheduler.Location = new Point(491, 198);
            btnRMScheduler.Name = "btnRMScheduler";
            btnRMScheduler.Size = new Size(211, 34);
            btnRMScheduler.TabIndex = 6;
            btnRMScheduler.Text = "Iniciar Escalonador RM";
            btnRMScheduler.UseVisualStyleBackColor = false;
            btnRMScheduler.Click += btnRMScheduler_Click;
            // 
            // label1
            // 
            label1.AutoSize = true;
            label1.Location = new Point(491, 82);
            label1.Name = "label1";
            label1.Size = new Size(147, 20);
            label1.TabIndex = 7;
            label1.Text = "Tempo de simulação";
            // 
            // txtSimulationTime
            // 
            txtSimulationTime.Location = new Point(491, 105);
            txtSimulationTime.Name = "txtSimulationTime";
            txtSimulationTime.Size = new Size(211, 27);
            txtSimulationTime.TabIndex = 8;
            // 
            // btnDMScheduler
            // 
            btnDMScheduler.BackColor = SystemColors.ButtonFace;
            btnDMScheduler.Location = new Point(491, 238);
            btnDMScheduler.Name = "btnDMScheduler";
            btnDMScheduler.Size = new Size(211, 34);
            btnDMScheduler.TabIndex = 9;
            btnDMScheduler.Text = "Iniciar Escalonador DM";
            btnDMScheduler.UseVisualStyleBackColor = false;
            btnDMScheduler.Click += btnDMScheduler_Click;
            // 
            // Form1
            // 
            ClientSize = new Size(1298, 721);
            Controls.Add(btnDMScheduler);
            Controls.Add(txtSimulationTime);
            Controls.Add(label1);
            Controls.Add(btnRMScheduler);
            Controls.Add(listSimulationLog);
            Controls.Add(dataGridViewTasks);
            Controls.Add(btnAddTask);
            Controls.Add(btnStartScheduler);
            Controls.Add(btnGenerateChart);
            Controls.Add(panelChart);
            Name = "Form1";
            Text = "EDF Scheduler - STR";
            ((System.ComponentModel.ISupportInitialize)dataGridViewTasks).EndInit();
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private DataGridViewTextBoxColumn dataGridViewTextBoxColumn1;
        private DataGridViewTextBoxColumn dataGridViewTextBoxColumn2;
        private DataGridViewTextBoxColumn dataGridViewTextBoxColumn3;
        private DataGridViewTextBoxColumn dataGridViewTextBoxColumn4;
        private ListBox listSimulationLog;
        private Button btnRMScheduler;
        private Label label1;
        private TextBox txtSimulationTime;
        private Button btnDMScheduler;
    }
}
