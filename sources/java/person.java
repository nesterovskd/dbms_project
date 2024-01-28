import java.sql.*;
import java.util.Random;

public class person {
  public static void main(String args[]) {
    try {
      // Loads the class "oracle.jdbc.driver.OracleDriver" into the memory
      Class.forName("oracle.jdbc.driver.OracleDriver");

      // Connection details
      String database = "jdbc:oracle:thin:@oracle19.cs.univie.ac.at:1521:orclcdb";
      String user = "a11944514";
      String pass = "1510";

      // Establish a connection to the database
      Connection con = DriverManager.getConnection(database, user, pass);
      Statement stmt = con.createStatement();

      String[] last = { "a", "be", "ci", "de", "e", "fi", "ge", "hi", "i", "ji", "ke", "le", "me", "ne",
          "o", "pi", "que", "re", "si", "te", "u", "ve", "we", "xi", "ye", "ze" };
      String[] first = { "ar", "bar", "car", "dar", "er", "far", "gar", "har", "ir", "jar", "kar", "lar",
          "mar", "nar", "or", "par", "qar", "rar", "sar", "tar", "ur", "var", "war", "xar", "yar", "zar" };

      // Generate a random first name

      for (int i = 0; i < 200; i++) {
        Random random = new Random();
        StringBuilder name = new StringBuilder();
        name.append(first[random.nextInt(first.length)]);
        name.append(last[random.nextInt(last.length)]);

        String firstname = name.substring(0, 1).toUpperCase() + name.substring(1);
        String lastname = first[random.nextInt(first.length)] + last[random.nextInt(last.length)];
        lastname = lastname.substring(0, 1).toUpperCase() + lastname.substring(1);
        try {

          String insertSql = "INSERT INTO person(LASTNAME,FIRSTNAME,EMAIL,PASSWORD) VALUES ('" + firstname
              + "', '" + lastname + "', '" + firstname.toLowerCase() + "." + lastname.toLowerCase() + "@gmail.com', '"
              + i + "password')";
          // executeUpdate Method: Executes the SQL statement, which can be an INSERT,
          // UPDATE, or DELETE statement
          int rowsAffected = stmt.executeUpdate(insertSql);
        } catch (Exception e) {
          System.err.println("Error while executing INSERT INTO statement: " + e.getMessage());
        }

      }

      // Check number of datasets in person table
      ResultSet rs = stmt.executeQuery("SELECT COUNT(*) FROM person");
      if (rs.next()) {
        int count = rs.getInt(1);
        System.out.println("Number of datasets: " + count);
      }

      // Clean up connections
      rs.close();
      stmt.close();
      con.close();
    } catch (Exception e) {
      System.err.println(e.getMessage());
    }
  }
}
