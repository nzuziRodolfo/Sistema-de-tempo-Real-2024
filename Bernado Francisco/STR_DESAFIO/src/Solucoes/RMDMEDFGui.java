/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Solucoes;

/**
 *
 * @author biblioteca
 */
import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.util.ArrayList;
import java.util.List;

public class RMDMEDFGui extends JFrame {

    private JTextField nameField, execTimeField, periodField, deadlineField, activationField;
    private JComboBox<String> algorithmBox;
    private JTable taskTable, resultTable;
    private DefaultTableModel taskTableModel, resultTableModel;

    public RMDMEDFGui() {
        setTitle("ESCALONAMENTO DE ALGORÍTMO RM/DM/EDF");
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setSize(800, 750);
        setLocationRelativeTo(null);

        JPanel panel = new JPanel(new GridBagLayout());
        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(5, 5, 5, 5);

        gbc.gridx = 0;
        gbc.gridy = 0;
        panel.add(new JLabel("Nome da Tarefa:"), gbc);
        gbc.gridx = 1;
        nameField = new JTextField(10);
        panel.add(nameField, gbc);

        gbc.gridx = 0;
        gbc.gridy = 1;
        panel.add(new JLabel("Tempo Execução:"), gbc);
        gbc.gridx = 1;
        execTimeField = new JTextField(10);
        panel.add(execTimeField, gbc);

        gbc.gridx = 0;
        gbc.gridy = 2;
        panel.add(new JLabel("Período:"), gbc);
        gbc.gridx = 1;
        periodField = new JTextField(10);
        panel.add(periodField, gbc);

        gbc.gridx = 0;
        gbc.gridy = 3;
        panel.add(new JLabel("Deadline Relativo:"), gbc);
        gbc.gridx = 1;
        deadlineField = new JTextField(10);
        panel.add(deadlineField, gbc);

        gbc.gridx = 0;
        gbc.gridy = 4;
        panel.add(new JLabel("Ponto de Ativação:"), gbc);
        gbc.gridx = 1;
        activationField = new JTextField(10);
        panel.add(activationField, gbc);

        gbc.gridx = 0;
        gbc.gridy = 5;
        panel.add(new JLabel("Tipo de Algoritmo:"), gbc);
        gbc.gridx = 1;
        algorithmBox = new JComboBox<>(new String[]{"RM", "DM", "EDF"});
        panel.add(algorithmBox, gbc);

        JButton addButton = new JButton("Adicionar Tarefa");
        gbc.gridx = 0;
        gbc.gridy = 6;
        gbc.gridwidth = 2;
        panel.add(addButton, gbc);
        addButton.addActionListener(e -> adicionarTarefa());

        JButton simulateButton = new JButton("Simular");
        gbc.gridy = 7;
        panel.add(simulateButton, gbc);
        simulateButton.addActionListener(e -> simular());

        JButton clearButton = new JButton("Limpar Tarefas");
        gbc.gridy = 8;
        panel.add(clearButton, gbc);
        clearButton.addActionListener(e -> limparTarefas());

        // Tabela de Tarefas
        String[] colunasTarefas = {"Nome", "Execução", "Período", "Deadline", "Ativação"};
        taskTableModel = new DefaultTableModel(colunasTarefas, 0);
        taskTable = new JTable(taskTableModel);
        JScrollPane taskScrollPane = new JScrollPane(taskTable);
        taskScrollPane.setPreferredSize(new Dimension(750, 100));
        gbc.gridy = 9;
        panel.add(taskScrollPane, gbc);

        // Tabela de Resultados
        String[] colunasResultados = {"Tempo", "Tarefa Executada"};
        resultTableModel = new DefaultTableModel(colunasResultados, 0);
        resultTable = new JTable(resultTableModel);
        JScrollPane resultScrollPane = new JScrollPane(resultTable);
        resultScrollPane.setPreferredSize(new Dimension(750, 200));
        gbc.gridy = 10;
        panel.add(resultScrollPane, gbc);

        add(panel);
        setVisible(true);
    }

    private void adicionarTarefa() {
        try {
            String nome = nameField.getText();
            int exec = Integer.parseInt(execTimeField.getText());
            int period = Integer.parseInt(periodField.getText());
            int deadline = Integer.parseInt(deadlineField.getText());
            int activation = Integer.parseInt(activationField.getText());

//            if (exec > period || deadline > period) {
//                JOptionPane.showMessageDialog(this, "Execução ou deadline inválido.");
//                return;
//            }
            String algoritmoSelecionado = (String) algorithmBox.getSelectedItem();

            if (exec <= 0 || period <= 0 || deadline <= 0) {
                JOptionPane.showMessageDialog(this, "Execução, Período e Deadline devem ser maiores que zero.");
                return;
            }

            if (exec > deadline) {
                JOptionPane.showMessageDialog(this, "Tempo de execução não pode ser maior que o deadline.");
                return;
            }

            if (!algoritmoSelecionado.equals("EDF") && deadline > period) {
                JOptionPane.showMessageDialog(this, "Deadline não pode ser maior que o período nos algoritmos RM/DM.");
                return;
            }

            taskTableModel.addRow(new Object[]{nome, exec, period, deadline, activation});

            nameField.setText("");
            execTimeField.setText("");
            periodField.setText("");
            deadlineField.setText("");
            activationField.setText("");
        } catch (NumberFormatException ex) {
            JOptionPane.showMessageDialog(this, "Insira valores válidos.");
        }
    }

    private void limparTarefas() {
        taskTableModel.setRowCount(0);
        resultTableModel.setRowCount(0);
    }

    private void simular() {
        if (taskTableModel.getRowCount() == 0) {
            JOptionPane.showMessageDialog(this, "Adicione tarefas primeiro.");
            return;
        }

        List<Jobs> taskList = new ArrayList<>();
        for (int i = 0; i < taskTableModel.getRowCount(); i++) {
            String nome = taskTableModel.getValueAt(i, 0).toString();
            int exec = Integer.parseInt(taskTableModel.getValueAt(i, 1).toString());
            int period = Integer.parseInt(taskTableModel.getValueAt(i, 2).toString());
            int deadline = Integer.parseInt(taskTableModel.getValueAt(i, 3).toString());
            int activation = Integer.parseInt(taskTableModel.getValueAt(i, 4).toString());
            taskList.add(new Jobs(nome, exec, period, deadline, activation));
        }

        String alg = (String) algorithmBox.getSelectedItem();
        Programa scheduler = new Programa(taskList, alg);
        List<String> timeline = scheduler.runSimulation();

        resultTableModel.setRowCount(0);
        for (int i = 0; i < timeline.size(); i++) {
            resultTableModel.addRow(new Object[]{i, timeline.get(i)});
        }

        Color background;
        switch (alg) {
            case "RM":
                background = new Color(220, 240, 255);
                break;
            case "DM":
                background = new Color(240, 255, 220);
                break;
            case "EDF":
                background = new Color(255, 240, 220);
                break;
            default:
                background = Color.WHITE;
        }

        Grafico.exibirGrafico(timeline, taskList, scheduler.getHyperPeriod(), alg, background);
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(RMDMEDFGui::new);
    }
}
