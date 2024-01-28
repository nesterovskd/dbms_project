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

public class Expense {
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

      List<Integer> categories = new ArrayList<>();
      ResultSet rs = stmt
          .executeQuery("SELECT categoryId FROM category");

      while (rs.next()) {
        categories.add(rs.getInt("categoryId"));
      }
      List<Integer> BudgetIds = new ArrayList<>();
      rs = stmt
          .executeQuery("SELECT BudgetId FROM Budget");

      while (rs.next()) {
        BudgetIds.add(rs.getInt("BudgetId"));
      }
      rs.close();

      Random random = new Random();
      for (int i = 0; i < 5000; i++) {
        Integer budget = random.nextInt(BudgetIds.size());
        String description = "Went to Museum";
        Double price = 0.0;
        Integer category = random.nextInt(categories.size()) + 1;
        switch (category) {
          case 1:
            description = "Went to Museum";
            price = (100 + random.nextInt(3000)) / 100.;
            break;
          case 2:
            description = "Tickets for Bus";
            price = (100 + random.nextInt(3000)) / 100.;
            break;
          case 3:
            description = "Hotel reservations";
            price = (3000 + random.nextInt(50000)) / 100.;
            break;
          case 4:
            description = "Bought postcards for friends";
            price = (100 + random.nextInt(30)) / 100.;
            break;
          case 5:
            description = "City Tour";
            price = (100 + random.nextInt(3000)) / 100.;
            break;
          case 6:
            description = "Dinner at \"4 Seasons\"";
            price = (5000 + random.nextInt(20000)) / 100.;
            break;
          default:
            description = "Went to Museum";
        }
        try {
          String insertSql = "INSERT INTO Expense(BudgetId, categoryid, description, price) VALUES ("
              + budget
              + ", " + category + ", '" + description + "', '" + price + "')";
          // System.out.println(countrydb + " - " + currency);
          System.out.println(insertSql);
          int rowsAffected = stmt.executeUpdate(insertSql);
        } catch (Exception e) {
          System.err.println("Error while executing INSERT INTO statement: " + e.getMessage());
        }

      }

      rs = stmt.executeQuery("SELECT COUNT(*) FROM expense");
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
