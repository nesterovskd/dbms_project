import java.sql.*;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.time.LocalDate;
import java.time.Period;

public class Trip {
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

      List<Integer> personIds = new ArrayList<>();
      ResultSet rs = stmt.executeQuery("SELECT PersonId FROM person");

      while (rs.next()) {
        personIds.add(rs.getInt("PersonId"));
      }
      rs.close();
      List<Integer> destinations = new ArrayList<>();
      rs = stmt.executeQuery("SELECT destinationId FROM destination");

      while (rs.next()) {
        destinations.add(rs.getInt("destinationid"));
      }
      rs.close();
      for (int i = 0; i < 400; ++i) {
        try {
          Random random = new Random();
          Integer destination = destinations.get(random.nextInt(destinations.size()));
          Integer person = personIds.get(random.nextInt(personIds.size()));

          LocalDate start = LocalDate.of(2023, 1, 1);
          LocalDate fromdate = start.plusDays(new Random().nextInt(500));
          LocalDate todate = fromdate.plusDays(new Random().nextInt(25));

          int red = random.nextInt(256);
          int green = random.nextInt(256);
          int blue = random.nextInt(256);
          String hex = String.format("#%02X%02X%02X", red, green, blue);

          String insertSql = "INSERT INTO Trip(Destinationid, personId, datefrom, dateto, color) VALUES ('"
              + destination
              + "', '" + person + "', TO_DATE('" + todate + "', 'YYYY-MM-DD'), TO_DATE('" + todate
              + "', 'YYYY-MM-DD'),'"
              + hex + "')";
          // System.out.println(insertSql);
          // int rowsAffected = stmt.executeUpdate(insertSql);
        } catch (Exception e) {
          System.err.println("Error while executing INSERT INTO statement: " + e.getMessage());
        }
      }

      // Check number of datasets in person table
      rs = stmt.executeQuery("SELECT COUNT(*) FROM trip");
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
