package manager;

import gui.Alert;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.List;



abstract class Manager<E>{

	protected String READ_ALL;

	public List<E> readAll(){ 
		List<E> data = null;
		Connection connection = ConnectionManager.getConnection();
		ResultSet resultSet = null;
		try {
			Statement stmnt = connection.createStatement();
			resultSet = stmnt.executeQuery( READ_ALL );
			data = processSelect( resultSet );
		} catch (SQLException e) {
			Alert.alert( e );
		} finally {
			try {
				connection.close();
			} catch (SQLException e) {
				Alert.alert( e );
			}
		}
		return data;	
	}

	abstract protected List<E> processSelect( ResultSet resultSet ) throws SQLException;
}
