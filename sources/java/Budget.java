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

public class Budget {
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

      List<String> alllines = Files.lines(Paths.get("currency.csv")).skip(1)
          .collect(Collectors.toList());

      Map<String, String> csvMap = new HashMap<>();// Country-Currency
      for (String line : alllines) {
        String[] parts = line.split(",");
        csvMap.put(parts[0], parts[3]);
      }

      Map<Integer, String> dbMap = new HashMap<>();
      ResultSet rs = stmt
          .executeQuery("SELECT t.tripid, d.country FROM trip t, destination d where d.destinationid=t.destinationid ");

      while (rs.next()) {
        dbMap.put(rs.getInt("tripid"), rs.getString("country"));
      }
      rs.close();
      for (Integer tripid : dbMap.keySet()) {
        try {
          Random random = new Random();
          int budgetsoll = (1 + random.nextInt(40)) * 50;
          String countrydb = dbMap.get(tripid);
          String currency = csvMap.get(countrydb);
          if (currency == null)
            currency = "USD";
          String insertSql = "INSERT INTO Budget(TripId, Budgetsoll, Currency) VALUES ('"
              + tripid
              + "', '" + budgetsoll + "', '" + currency + "')";
          System.out.println(countrydb + " - " + currency);
          System.out.println(insertSql);
          // int rowsAffected = stmt.executeUpdate(insertSql);
        } catch (Exception e) {
          System.err.println("Error while executing INSERT INTO statement: " + e.getMessage());
        }
      }

      // Check number of datasets in person table
      rs = stmt.executeQuery("SELECT COUNT(*) FROM budget");
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
