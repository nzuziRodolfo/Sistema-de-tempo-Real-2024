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
public class Jobs {
    private String name;
    private int execTime;
    private int period;
    private int deadline;
    private int activation;

    public Jobs(String name, int execTime, int period, int deadline, int activation) {
        this.name = name;
        this.execTime = execTime;
        this.period = period;
        this.deadline = deadline;
        this.activation = activation;
    }

    public String getName() {
        return name;
    }

    public int getExecTime() {
        return execTime;
    }

    public int getPeriod() {
        return period;
    }

    public int getDeadline() {
        return deadline;
    }

    public int getRelativeDeadline() {
        return deadline;
    }

    public int getActivation() {
        return activation;
    }
}