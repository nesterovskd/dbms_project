import java.sql.*;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.time.LocalDate;
import java.time.Period;

public class TripSave {
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

      List<Integer> tripids = new ArrayList<>();
      ResultSet rs = stmt.executeQuery("SELECT sharedtripid FROM shared_trip");

      while (rs.next()) {
        tripids.add(rs.getInt("sharedTripId"));
      }
      rs.close();

      List<Integer> persons = new ArrayList<>();
      rs = stmt
          .executeQuery("SELECT personid FROM person");

      while (rs.next()) {
        persons.add(rs.getInt("personid"));
      }
      rs.close();

      for (int i = 0; i < 3000; ++i) {
        try {
          Random random = new Random();
          Integer tripId = tripids.get(random.nextInt(tripids.size()));
          Integer person = persons.get(random.nextInt(persons.size()));

          String insertSql = "INSERT INTO tripsave(sharedtripid, personid) VALUES ("
              + tripId
              + ", " + person + ")";
          System.out.println(insertSql);
          int rowsAffected = stmt.executeUpdate(insertSql);
        } catch (Exception e) {
          System.err.println("Error while executing INSERT INTO statement: " + e.getMessage());
        }
      }

      // Check number of datasets in person table
      rs = stmt.executeQuery("SELECT COUNT(*) FROM tripsave");
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
