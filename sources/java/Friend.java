import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Random;
import java.util.stream.Collectors;
import java.time.LocalDate;
import java.time.Period;

public class Friend {
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

      List<Integer> persons = new ArrayList<>();
      ResultSet rs = stmt
          .executeQuery("SELECT personid FROM person");

      while (rs.next()) {
        persons.add(rs.getInt("personid"));
      }
      rs.close();

      String[] statuses = { "P", "R", "A" };

      Random random = new Random();
      for (int i = 0; i < 450; i++) {
        Integer person1 = persons.get(random.nextInt(persons.size()));
        Integer person2 = persons.get(random.nextInt(persons.size()));
        String status = statuses[random.nextInt(statuses.length)];

        try {
          String insertSql = "INSERT INTO Is_friend_of VALUES (" + person1 + ", " + person2 + ", '" + status + "')";
          System.out.println(insertSql);
          int rowsAffected = stmt.executeUpdate(insertSql);
        } catch (Exception e) {
          System.err.println("Error while executing INSERT INTO statement: " + e.getMessage());
        }

      }

      rs = stmt.executeQuery("SELECT COUNT(*) FROM is_friend_of");
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
