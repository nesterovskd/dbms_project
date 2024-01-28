import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.*;
import java.util.List;
import java.util.stream.Collectors;

public class Destination {
  public static void main(String args[]) {
    try {
      Class.forName("oracle.jdbc.driver.OracleDriver");
      String database = "jdbc:oracle:thin:@oracle19.cs.univie.ac.at:1521:orclcdb";
      String user = "a11944514";
      String pass = "1510";
      Connection con = DriverManager.getConnection(database, user, pass);
      Statement stmt = con.createStatement();

      // Path PATH = Paths.get("worldcities.csv");

      List<String> alllines = Files.lines(Paths.get("worldcities.csv")).skip(1).limit(15000)
          .collect(Collectors.toList());
      // int i = 1;
      for (String line : alllines) {
        String[] parts = line.split(",");
        String[] city1 = parts[1].split("\"");
        String city = city1[1];
        String[] country1 = parts[4].split("\"");
        String country = country1[1];

        String insertSql = "INSERT INTO destination(country, city) VALUES ('" + country + "', '" + city + "')";
        // System.out.println(insertSql);

        try {
          int rowsAffected = stmt.executeUpdate(insertSql);
        } catch (Exception e) {
          System.err.println("Error while executing INSERT INTO statement: " +
              e.getMessage());
        }
      }
      // Check number of datasets in person table
      ResultSet rs = stmt.executeQuery("SELECT COUNT(*) FROM destination");
      if (rs.next()) {
        int count = rs.getInt(1);
        System.out.println("Number of datasets: " + count);
      }

      // Clean up connections
      rs.close();
      stmt.close();
      con.close();
    } catch (

    Exception e) {
      System.err.println(e.getMessage());
    }
  }
}
