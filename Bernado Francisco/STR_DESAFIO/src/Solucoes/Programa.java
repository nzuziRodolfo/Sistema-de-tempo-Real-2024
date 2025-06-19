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
import java.util.*;

public class Programa {
    private List<Jobs> tasks;
    private int hyperPeriod;
    private String algorithm;

    public Programa(List<Jobs> tasks, String algorithm) {
        this.tasks = tasks;
        this.algorithm = algorithm;
        this.hyperPeriod = lcmOfPeriods(tasks);


    }
    

    public int getHyperPeriod() {
        return hyperPeriod;
    }

    public List<String> runSimulation() {
        List<String> schedule = new ArrayList<>();
        Map<String, Integer> nextRelease = new HashMap<>();
        Map<String, Integer> remainingTime = new HashMap<>();
        Map<String, List<Integer>> deadlines = new HashMap<>();
        Map<String, Integer> jobInstance = new HashMap<>();

        for (Jobs task : tasks) {
            nextRelease.put(task.getName(), task.getActivation());
            remainingTime.put(task.getName(), 0);
            deadlines.put(task.getName(), new ArrayList<>());
            jobInstance.put(task.getName(), 0);
        }

        Map<String, Integer> currentJob = new HashMap<>();

        for (int t = 0; t < hyperPeriod; t++) {
            for (Jobs task : tasks) {
                if (t == nextRelease.get(task.getName())) {
                    remainingTime.put(task.getName(), task.getExecTime());
                    deadlines.get(task.getName()).add(t + task.getRelativeDeadline());
                    currentJob.put(task.getName(), jobInstance.get(task.getName()));
                    jobInstance.put(task.getName(), jobInstance.get(task.getName()) + 1);
                    nextRelease.put(task.getName(), t + task.getPeriod());
                }
            }

            Jobs selected = null;
            for (Jobs task : tasks) {
                if (remainingTime.get(task.getName()) > 0) {
                    if (selected == null || compare(task, selected, t, deadlines)) {
                        selected = task;
                    }
                }
            }

            if (selected != null) {
                int instance = currentJob.getOrDefault(selected.getName(), 0);
                schedule.add(selected.getName() + "." + instance);
                remainingTime.put(selected.getName(), remainingTime.get(selected.getName()) - 1);
            } else {
                schedule.add("Idle");
            }
        }

        return schedule;
    }

    private boolean compare(Jobs a, Jobs b, int currentTime, Map<String, List<Integer>> deadlines) {
        switch (algorithm) {
            case "RM": return a.getPeriod() < b.getPeriod();
            case "DM": return a.getRelativeDeadline() < b.getRelativeDeadline();
            case "EDF":
                return earliestDeadline(deadlines.get(a.getName()), currentTime) <
                       earliestDeadline(deadlines.get(b.getName()), currentTime);
            default: return false;
        }
    }

    private int earliestDeadline(List<Integer> list, int currentTime) {
        return list.stream().filter(d -> d >= currentTime).min(Integer::compare).orElse(Integer.MAX_VALUE);
    }

    private int lcmOfPeriods(List<Jobs> tasks) {
        int lcm = tasks.get(0).getPeriod();
        for (int i = 1; i < tasks.size(); i++) {
            lcm = lcm(lcm, tasks.get(i).getPeriod());
        }
        return lcm;
    }

    private int lcm(int a, int b) {
        return (a * b) / gcd(a, b);
    }

    private int gcd(int a, int b) {
        return (b == 0) ? a : gcd(b, a % b);
    }
}