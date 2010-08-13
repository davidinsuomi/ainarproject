package manager;

import gui.Alert;

import java.sql.Connection;
import java.sql.DriverManager;

public class ConnectionManager {

	public static Connection getConnection(){
		Connection connection = null;
		try {
			Class.forName("com.mysql.jdbc.Driver");
			connection = DriverManager.getConnection(
					"jdbc:mysql://smartdesign.ee/np25851_is",
			        "np25851_is", 
			        "aaaaa"
			);
			connection.setTransactionIsolation( Connection.TRANSACTION_SERIALIZABLE );
		}
		catch ( Exception e) {
			Alert.alert( e );
		}
		return connection;
	}
}
