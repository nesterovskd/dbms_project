import java.sql.*;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.time.LocalDate;
import java.time.Period;
import java.time.format.DateTimeFormatter;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;

public class tripfix {
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

            for (Integer trip : tripids) {
                try {
                    LocalDate start = LocalDate.of(2023, 10, 1);
                    LocalDate fromdat = start.plusDays(new Random().nextInt(500));
                    LocalDate todate = fromdat.plusDays(new Random().nextInt(25));
                    DateTimeFormatter formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd");
                    String insertSql = "UPDATE trip SET DATEFROM = TO_DATE(?, 'YYYY-MM-DD'), DATETO = TO_DATE(?, 'YYYY-MM-DD') WHERE tripId = ?";
                    try (PreparedStatement pstmt = con.prepareStatement(insertSql)) {
                        pstmt.setString(1, fromdat.format(formatter)); // Erstes Fragezeichen ersetzen
                        pstmt.setString(2, todate.format(formatter)); // Zweites Fragezeichen ersetzen
                        pstmt.setInt(3, trip); // Drittes Fragezeichen ersetzen

                        int affectedRows = pstmt.executeUpdate();
                        if (affectedRows > 0) {
                            System.out.println("Update erfolgreich für tripId: " + trip);
                        } else {
                            System.out.println("Keine Zeilen aktualisiert für tripId: " + trip);
                        }
                    }
                    // if (affectedRows > 0) {
                    // // Erfolgreich aktualisiert
                    // } else {
                    // // Keine Zeilen betroffen
                    // }
                    // String insertSql = "UPDATE trip SET DATEFROM = TO_DATE('" + fromdate
                    // + "', 'YYYY-MM-DD'), DATETO = TO_DATE('" + todate + "', 'YYYY-MM-DD') WHERE
                    // tripId="
                    // + trip + ";";

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
        } catch (

        Exception e) {
            System.err.println(e.getMessage());
        }
    }
}