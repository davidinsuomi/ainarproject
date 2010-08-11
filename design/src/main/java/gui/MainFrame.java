/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * MainFrame.java
 *
 * Created on 7.08.2010, 4:38:00
 */

package gui;

import javax.swing.JOptionPane;
import javax.swing.UIManager;
import javax.swing.UnsupportedLookAndFeelException;

/**
 *
 * @author Administrator
 */
public class MainFrame extends javax.swing.JFrame {
	
	private PalgadPanel palgadPanel;
	private ProjektidPanel projektidPanel;
	
    /** Creates new form MainFrame */
    public MainFrame() {
        initComponents();
        
        palgadPanel = new PalgadPanel();
        jMainTabbed.addTab( "Palgad", palgadPanel );
        
        projektidPanel = new ProjektidPanel();
        jMainTabbed.addTab( "Projektid", projektidPanel );
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        jMainTabbed = new javax.swing.JTabbedPane();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setTitle("SmartDesign");
        setBackground(new java.awt.Color(255, 255, 255));

        jMainTabbed.setBackground(new java.awt.Color(230, 230, 230));
        jMainTabbed.setAlignmentX(0.0F);
        jMainTabbed.setAlignmentY(0.0F);
        jMainTabbed.setOpaque(true);

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jMainTabbed, javax.swing.GroupLayout.DEFAULT_SIZE, 657, Short.MAX_VALUE)
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jMainTabbed, javax.swing.GroupLayout.DEFAULT_SIZE, 419, Short.MAX_VALUE)
        );

        pack();
    }// </editor-fold>//GEN-END:initComponents

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JTabbedPane jMainTabbed;
    // End of variables declaration//GEN-END:variables

}
