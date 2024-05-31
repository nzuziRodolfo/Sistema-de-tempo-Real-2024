/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package escalonamentorm;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

/**
 *
 * @author domin
 */
public class EscalonadorDM {
    private List <TarefaDM> tarefas;
    
    public EscalonadorDM(){
        tarefas = new ArrayList<>();
    }
    
    public void adicionarTarefa( TarefaDM tarefa){
        tarefas.add(tarefa);
        Collections.sort(tarefas);
    }
    
    public void executar(){
        int priod = 1;
        System.out.println("Escalonamento DM");
        for( TarefaDM tarefa : tarefas ){
            System.out.println("-------------------       " + tarefa.getNome() +"   ----------------------------");
            System.out.println("Tarefa : "+tarefa.getNome());
            System.out.println("Priodo : "+tarefa.getPeriodo());
            System.out.println("DeadLine da tarefa : "+tarefa.getDeadLine());
            System.out.println("Prioridade da tarefa : "+priod);
            System.out.println("");
            priod++;
        }
    }
}
