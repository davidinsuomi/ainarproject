package core;

import gui.MainFrame;

import java.util.List;

import javax.swing.UIManager;

import manager.ProjectsManager;
import model.Project;

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
		
		List<Project> projects = ( new ProjectsManager() ).readAll();
		int i = 0;
	}
}
