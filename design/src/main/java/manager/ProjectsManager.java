package manager;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

import model.Project;

public class ProjectsManager extends Manager<Project>{
	
	
	public ProjectsManager(){
		READ_ALL = "SELECT * FROM PROJECTS";
	}
		
	protected List<Project> processSelect( ResultSet resultSet ) throws SQLException{
		List<Project> projects =  new ArrayList<Project>();
		while( resultSet.next() ){
			Project project = new Project();
			project.setClientCode( resultSet.getLong( "CLIENTCODE" ) );
			project.setCode( resultSet.getLong( "CODE" ) );
			project.setComments( resultSet.getString( "COMMENTS" ) );
			project.setContactCode( resultSet.getLong( "CONTACTCODE" ) );
			project.setDeadline( resultSet.getDate( "DEADLINE" ) );
			project.setDelivered( resultSet.getDate( "DELIVERED" ) );
			project.setEmployeeCode( resultSet.getLong( "EMPLOYEECODE" ) );
			project.setHourlyRate( resultSet.getDouble( "HOURLYRATE" ) );
			project.setName( resultSet.getString( "NAME" ) );
			project.setOrdered( resultSet.getDate( "ORDERED" ) );
			project.setStatusCode( resultSet.getLong( "STATUSCODE" ) );
			projects.add( project );
		}
		return projects;
	}

}
