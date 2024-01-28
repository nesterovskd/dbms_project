import java.sql.*;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.time.LocalDate;
import java.time.Period;

public class SharedTrip {
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
      ResultSet rs = stmt.executeQuery("SELECT TripId FROM Trip");

      while (rs.next()) {
        tripids.add(rs.getInt("TripId"));
      }
      rs.close();
      String[] suitableForArray = { "Families", "Friends", "Solo Travelers", "Couples", "Business Trips", "Students" };
      String description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam eu dui eu orci rhoncus imperdiet non at augue. Aliquam vestibulum, dui eget molestie cursus, erat dui varius mauris, quis sodales augue nisi in nisi.";

      for (int i = 0; i < 250; ++i) {
        try {
          Random random = new Random();
          String suitablefor = suitableForArray[random.nextInt(suitableForArray.length)];
          Integer tripId = tripids.get(random.nextInt(tripids.size()));

          String insertSql = "INSERT INTO shared_Trip(tripid, description, suitableFor) VALUES ("
              + tripId
              + ", '" + description + "','"
              + suitablefor + "')";
          System.out.println(insertSql);
          int rowsAffected = stmt.executeUpdate(insertSql);
        } catch (Exception e) {
          System.err.println("Error while executing INSERT INTO statement: " + e.getMessage());
        }
      }

      // Check number of datasets in person table
      rs = stmt.executeQuery("SELECT COUNT(*) FROM shared_Trip");
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
