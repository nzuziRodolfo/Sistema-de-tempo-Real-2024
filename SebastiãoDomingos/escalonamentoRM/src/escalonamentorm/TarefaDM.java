/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package escalonamentorm;

/**
 *
 * @author domin
 */
public class TarefaDM implements Comparable<TarefaDM>{

    private int periodo;
    private int deadLine;
    private String nome;

    public TarefaDM(int periodo, int deadLine, String nome) {
        this.periodo = periodo;
        this.deadLine = deadLine;
        this.nome = nome;
    }

    public int getPeriodo() {
        return periodo;
    }


    public int getDeadLine() {
        return deadLine;
    }

   
    public String getNome() {
        return nome;
    }

    public void setNome(String nome) {
        this.nome = nome;
    }
    
    @Override
    public int compareTo(TarefaDM other) {
        return Integer.compare(this.deadLine, other.deadLine);
    }
    
}
