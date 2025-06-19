/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package str_desafio;

import Solucoes.RMDMEDFGui;

/**
 *
 * @author biblioteca
 */
public class STR_DESAFIO {

    /**
     * @param args the command line arguments
     */
   public static void main(String[] args) {
        // Executar a interface gráfica na thread da interface do Swing
        javax.swing.SwingUtilities.invokeLater(() -> {
            new RMDMEDFGui();
        });
    }
}
