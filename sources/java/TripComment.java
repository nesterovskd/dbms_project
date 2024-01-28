import java.sql.*;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.time.LocalDate;
import java.time.Period;

public class TripComment {
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

      String[] texte = { "Well done!", "Nice Trip!!!", "LOVE IT", "Also were there, it was amazing!",
          "Spent lot more than you. Very expensive city",
          "Didnt know you can go there and not spend a fortune. Great Itinerary!",
          "Perfect country. Everything is so cheap!", "Overrated..",
          "DO NOT go there!! It rains a LOT there, we stayed in our hotel room for almost the whole time!!" };
      for (int i = 0; i < 300; ++i) {
        try {
          Random random = new Random();
          Integer tripId = tripids.get(random.nextInt(tripids.size()));
          Integer person = persons.get(random.nextInt(persons.size()));
          String text = texte[random.nextInt(texte.length)];
          String insertSql = "INSERT INTO tripcomment(sharedtripid, personid,text) VALUES ("
              + tripId
              + ", " + person + ", '" + text + "')";
          System.out.println(insertSql);
          int rowsAffected = stmt.executeUpdate(insertSql);
        } catch (Exception e) {
          System.err.println("Error while executing INSERT INTO statement: " + e.getMessage());
        }
      }

      // Check number of datasets in person table
      rs = stmt.executeQuery("SELECT COUNT(*) FROM tripcomment");
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
