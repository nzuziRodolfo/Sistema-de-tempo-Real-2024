/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Main.java to edit this template
 */
package escalonamentorm;

/**
 *
 * @author domin
 */
public class EscalonamentoRM {

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        // TODO code application logic here
        Escalonador escalonador = new Escalonador();
        
        escalonador.adicionarTarefa( new Tarefa(20,10,"A"));
        escalonador.adicionarTarefa( new Tarefa(1,20,"B"));
        escalonador.adicionarTarefa( new Tarefa(6,30,"C"));
        escalonador.adicionarTarefa( new Tarefa(1,50,"D"));
        escalonador.adicionarTarefa( new Tarefa(40,5,"E"));

        escalonador.executar();
        
          EscalonadorDM escalonadorDm = new EscalonadorDM();
        
        escalonadorDm.adicionarTarefa( new TarefaDM(20,10,"A"));
        escalonadorDm.adicionarTarefa( new TarefaDM(40,20,"B"));
        escalonadorDm.adicionarTarefa( new TarefaDM(6,30,"C"));
        escalonadorDm.adicionarTarefa( new TarefaDM(10,50,"D"));
        escalonadorDm.adicionarTarefa( new TarefaDM(40,5,"E"));

        escalonadorDm.executar();

    }
    
}
