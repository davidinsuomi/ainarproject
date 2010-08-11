package core;

import gui.MainFrame;

import javax.swing.UIManager;

import manager.ConnectionManager;

public class Main {
	
	private static MainFrame mainFrame;
	
	/**
	 * @param args the command line arguments
	 */
	public static void main(String args[]) {
		try {
			UIManager.setLookAndFeel( UIManager.getSystemLookAndFeelClassName() );
		} catch ( Exception e ) {}

		mainFrame = new MainFrame();
		mainFrame.setVisible(true);
		
		ConnectionManager.getConnection();
	}
}
