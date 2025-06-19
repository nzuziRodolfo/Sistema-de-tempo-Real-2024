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
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Graphics;
import javax.swing.JPanel;
import java.awt.Rectangle;
import java.awt.FontMetrics;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Grafico {
    private static final Color[] CORES = { Color.BLUE, Color.GREEN, Color.ORANGE, Color.MAGENTA, Color.CYAN };

    public static void exibirGrafico(List<String> timeline, List<Jobs> tarefas, int hyperPeriod, String algoritmo, Color background) {
        // Define a cor de fundo baseada no algoritmo
        switch (algoritmo) {
            case "RM":
                background = Color.decode("#DCF0FF"); // Azul claro
                break;
            case "DM":
                background = Color.decode("#F0FFDC"); // Verde claro
                break;
            case "EDF":
                background = Color.decode("#FFF0DC"); // Laranja claro
                break;
        }

        JFrame frame = new JFrame("Gráfico de Gantt - Algoritmo: " + algoritmo);
        frame.setDefaultCloseOperation(JFrame.DISPOSE_ON_CLOSE);
        frame.setSize(1200, 400);

        Map<String, Integer> linhaTarefa = new HashMap<>();
        for (int i = 0; i < tarefas.size(); i++) {
            linhaTarefa.put(tarefas.get(i).getName(), i);
        }

        Color finalBackground = background;
        JPanel panel = new JPanel() {
            protected void paintComponent(Graphics g) {
                super.paintComponent(g);
                setBackground(finalBackground);

                int larguraCelula = 40;
                int alturaCelula = 30;

                for (int i = 0; i < tarefas.size(); i++) {
                    g.setColor(Color.BLACK);
                    g.drawString(tarefas.get(i).getName(), 10, (i + 1) * alturaCelula + 20);
                }

                for (int tempo = 0; tempo < hyperPeriod; tempo++) {
                    String tarefaInstancia = timeline.get(tempo);
                    String tarefaBase = tarefaInstancia.contains(".") ? tarefaInstancia.split("\\.")[0] : tarefaInstancia;
                    for (int i = 0; i < tarefas.size(); i++) {
                        g.setColor(Color.WHITE);
                        g.fillRect((tempo + 1) * larguraCelula, (i + 1) * alturaCelula, larguraCelula, alturaCelula);
                        g.setColor(Color.BLACK);
                        g.drawRect((tempo + 1) * larguraCelula, (i + 1) * alturaCelula, larguraCelula, alturaCelula);
                    }

                    if (!tarefaInstancia.equals("Idle")) {
                        Integer linha = linhaTarefa.get(tarefaBase);
                        if (linha != null) {
                            g.setColor(CORES[linha % CORES.length]);
                            g.fillRect((tempo + 1) * larguraCelula, (linha + 1) * alturaCelula, larguraCelula, alturaCelula);
                            g.setColor(Color.BLACK);
                            g.drawRect((tempo + 1) * larguraCelula, (linha + 1) * alturaCelula, larguraCelula, alturaCelula);
                            g.drawString(tarefaInstancia, (tempo + 1) * larguraCelula + 3, (linha + 1) * alturaCelula + 20);
                        }
                    }
                }

                for (int tempo = 0; tempo <= hyperPeriod; tempo++) {
                    g.setColor(Color.BLACK);
                    g.drawString(String.valueOf(tempo), (tempo + 1) * larguraCelula + 10, (tarefas.size() + 1) * alturaCelula + 20);
                }
            }
        };

        panel.setPreferredSize(new Dimension(hyperPeriod * 45 + 100, tarefas.size() * 40 + 100));
        frame.add(new JScrollPane(panel));
        frame.setVisible(true);
    }
}
