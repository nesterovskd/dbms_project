<?php
   
class DatabaseHelper
{
    const username = 'a11944514'; // use a + your matriculation number
    const password = '1510'; // use your oracle db password
    const con_string = '//oracle19.cs.univie.ac.at:1521/orclcdb';

    protected $conn;

    public function __construct()
    {
        try {
            $this->conn = oci_connect(
                DatabaseHelper::username,
                DatabaseHelper::password,
                DatabaseHelper::con_string
            );
   
            if (!$this->conn) {
                die("DB error: Connection can't be established!");
            }
              
        } catch (Exception $e) {
            die("DB error: {$e->getMessage()}");
        }
    }
   
    public function __destruct()
    {
        oci_close($this->conn);
    }

       public function findPerson($email, $password)
    {  $sql = "SELECT * FROM person
            WHERE password = '$password'
            AND upper(email) = 'upper($email)'";

    $statement = oci_parse($this->conn, $sql);


    oci_execute($statement);

    oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    oci_free_statement($statement);

    return $res;
    }
    public function selectAllPersons()
    {
        $sql = "SELECT * FROM person";
        $statement = oci_parse($this->conn, $sql);
   
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
   
        return $res;
    }
        public function selectAllCategories()
    {
        $sql = "SELECT * FROM category";
        $statement = oci_parse($this->conn, $sql);
   
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
   
        return $res;
    }
public function getAllExpenses($tripId){
$sql = "SELECT BudgetId FROM Budget where tripId = :tripId";
        $statement = oci_parse($this->conn, $sql);
   
        oci_bind_by_name($statement, ':tripId', $tripId);

        oci_execute($statement);
        $row = oci_fetch_assoc($statement);

        $budgetId = $row ? $row['BUDGETID'] : null;

        oci_free_statement($statement);

        $sql = "SELECT * FROM expenseview where budgetId = :budgetId";
        $statement = oci_parse($this->conn, $sql);
   
        oci_bind_by_name($statement, ':budgetId', $budgetId);

        oci_execute($statement); 
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
   
        return $res;;
}
     public function addExpense($tripId, $description, $price, $category){
        $sql = "SELECT BudgetId FROM Budget where tripId = :tripId";
        $statement = oci_parse($this->conn, $sql);
   
        oci_bind_by_name($statement, ':tripId', $tripId);

        oci_execute($statement);
        $row = oci_fetch_assoc($statement);

        $budgetId = $row ? $row['BUDGETID'] : null;

        oci_free_statement($statement);
   
        $sql = "INSERT INTO Expense (BudgetId, description, price, categoryID) VALUES ('{$budgetId}', '{$description}', '{$price}', '{$category}')";
        
        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }
          public function selectAllDestinations()
    {
        $sql = "SELECT * FROM Destination";
        $statement = oci_parse($this->conn, $sql);
   
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
   
        return $res;
    }
 public function searchTrips($personId)
{
    $sql = "SELECT * FROM tripview WHERE personid = :personId";
    $statement = oci_parse($this->conn, $sql);

    oci_bind_by_name($statement, ":personId", $personId);


    oci_execute($statement);


    oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    oci_free_statement($statement);

    return $res;
}

public function findUserByEmail($email){
    $sql = "SELECT * FROM person WHERE email = :email";
    $statement = oci_parse($this->conn, $sql);


    oci_bind_by_name($statement, ":email", $email);

    oci_execute($statement);

    oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    oci_free_statement($statement);

    return $res;
}
public function getUserById($userId){
    $sql = "SELECT * FROM person WHERE personId = :userId";
    $statement = oci_parse($this->conn, $sql);


    oci_bind_by_name($statement, ":userId", $userId);

    oci_execute($statement);

    oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    oci_free_statement($statement);

    return $res;
}
public function getAllSharedTrips(){
$sql = "SELECT * FROM sharedtripview";
    $statement = oci_parse($this->conn, $sql);

    oci_execute($statement);

    oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
    oci_free_statement($statement);

    return $res;
}
public function getSavedTrips($userId){
$sql = "SELECT * FROM tripsave s, sharedtripview st WHERE s.personId = :userId and st.sharedTripid=s.sharedtripid";
    $statement = oci_parse($this->conn, $sql);

    oci_bind_by_name($statement, ":userId", $userId);

    oci_execute($statement);

    oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    oci_free_statement($statement);

    return $res;
}
public function checkUserExists($email) {
    try {
        $sql = "SELECT COUNT(*) FROM person WHERE email = :email";
        $statement = oci_parse($this->conn, $sql);

        oci_bind_by_name($statement, ":email", $email);

        oci_execute($statement);

        $row = oci_fetch_array($statement, OCI_ASSOC);
        $count = $row['COUNT(*)'];

        oci_free_statement($statement);

        return $count > 0;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
public function insertIntoPerson($firstname, $surname, $email, $password)
    {
        $sql = "INSERT INTO PERSON (firstNAME, lastname, email, password) VALUES ('{$firstname}', '{$surname}', '{$email}', '{$password}')";
   
        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
    }
public function updateUserProfile($userId, $firstname, $surname, $email, $password)
{
    try {
        $sql = "UPDATE person SET firstname = :firstname, lastname = :surname, email = :email, password = :password WHERE personId = :userId";
        $statement = oci_parse($this->conn, $sql);

        oci_bind_by_name($statement, ':firstname', $firstname);
        oci_bind_by_name($statement, ':surname', $surname);
        oci_bind_by_name($statement, ':email', $email);
        oci_bind_by_name($statement, ':userId', $userId);
        oci_bind_by_name($statement, ':password', $password);

        $success = oci_execute($statement);

        oci_free_statement($statement);
        return $success;
    } catch (Exception $e) {
        error_log('Error in updateUserProfile: ' . $e->getMessage());
        return false;
    }
}

public function searchSharedTrips($userId){
 $sql = "SELECT * FROM sharedtripview WHERE personid = :userId";
    $statement = oci_parse($this->conn, $sql);

    oci_bind_by_name($statement, ":userId", $userId);


    oci_execute($statement);


    oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    oci_free_statement($statement);

    return $res;
}
public function getComments($tripId){
 $sql = "SELECT sharedTripId FROM shared_trip where tripId = :tripId";
        $statement = oci_parse($this->conn, $sql);
   
        oci_bind_by_name($statement, ':tripId', $tripId);

        oci_execute($statement);
        $row = oci_fetch_assoc($statement);

        $sharedTripId = $row ? $row['SHAREDTRIPID'] : null;

        oci_free_statement($statement);

        $sql = "SELECT * FROM tripcommentView where sharedTripId = :sharedTripId";
        $statement = oci_parse($this->conn, $sql);
   
        oci_bind_by_name($statement, ':sharedTripId', $sharedTripId);

        oci_execute($statement); 
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($statement);
   
        return $res;
}
public function addFriend($userId, $recieverId){
    $sql = "INSERT INTO is_friend_of (SENDERID, RECIEVERID, STATUS) VALUES ('{$userId}', '{$recieverId}', 'P')";
   
        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
}
public function addSave($userId, $sharedTripId){
    try {
    $sql = "INSERT INTO tripsave (personId, sharedTripId) VALUES ('{$userId}', '{$sharedTripId}')";
   
        $statement = oci_parse($this->conn, $sql);
        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
}
    catch (Exception $e) {
        error_log('Error in addSave: ' . $e->getMessage());
        return false;
    }
     return $success;
       
}
public function rejectFriendship($userId, $senderId){
    $sql="Begin RejectFriendRequest(p_recieverId => :userId, p_senderId => :senderId); end;";

        $statement = oci_parse($this->conn, $sql);
        oci_bind_by_name($statement, ":userId", $userId);
        oci_bind_by_name($statement, ":senderId", $senderId);

        $success = oci_execute($statement) && oci_commit($this->conn);
        oci_free_statement($statement);
        return $success;
}
public function selectFriends($userId){
 $sql = "SELECT * FROM is_friend_of WHERE senderId = :userId or recieverid=:userId";
    $statement = oci_parse($this->conn, $sql);

    oci_bind_by_name($statement, ":userId", $userId);

    oci_execute($statement);

 oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    oci_free_statement($statement);

    return $res; 
}
public function updateTrip($tripId, $destination, $fromDate, $toDate, $color, $budget, $currency, $sharedTrip, $description, $suitableFor){
try {
        $sql = "UPDATE trip SET destinationId = :destination, datefrom = TO_DATE(:fromDate,'YYYY-MM-DD'), dateto = TO_DATE(:toDate,'YYYY-MM-DD'),  color=:color WHERE tripId = :tripId";
        $statement = oci_parse($this->conn, $sql);

        oci_bind_by_name($statement, ':destination', $destination);
        oci_bind_by_name($statement, ':fromDate', $fromDate);
        oci_bind_by_name($statement, ':toDate', $toDate);
        oci_bind_by_name($statement, ':color', $color);
        oci_bind_by_name($statement, ':tripId', $tripId);

        $success = oci_execute($statement);

        oci_free_statement($statement);
         if ($success){
            $sql = "Update Budget set Budgetsoll = :budget, Currency=:currency where tripId=:tripId";
            $statement = oci_parse($this->conn, $sql);

            oci_bind_by_name($statement, ':tripId', $tripId);
            oci_bind_by_name($statement, ':budget', $budget);
            oci_bind_by_name($statement, ':currency', $currency);


            $success = oci_execute($statement);
            oci_commit($this->conn);
            oci_free_statement($statement);
        }
        if ($success && $sharedTrip) {
        $sql = "Update shared_Trip  set description = :description, suitableFor =:suitableFor where tripId=:tripId";
        $statement = oci_parse($this->conn, $sql);

        oci_bind_by_name($statement, ':tripId', $tripId);
        oci_bind_by_name($statement, ':description', $description);
        oci_bind_by_name($statement, ':suitableFor', $suitableFor);

        $success = oci_execute($statement);
        oci_commit($this->conn);
        oci_free_statement($statement);
        }
    } catch (Exception $e) {
        error_log('Error in updateUserProfile: ' . $e->getMessage());
        return false;
    }
     return $success;
}
public function addNewTrip($userId, $destination, $fromDate, $toDate, $color, $budget, $currency, $sharedTrip, $description, $suitableFor) {

    $sql = "INSERT INTO Trip(Destinationid, personId, datefrom, dateto, color) VALUES (:destination, :userId, TO_DATE(:fromDate,'YYYY-MM-DD'), TO_DATE(:toDate,'YYYY-MM-DD'), :color) RETURNING TripId INTO :tripId";
    $statement = oci_parse($this->conn, $sql);


    oci_bind_by_name($statement, ':destination', $destination);
    oci_bind_by_name($statement, ':userId', $userId);
    oci_bind_by_name($statement, ':fromDate', $fromDate);
    oci_bind_by_name($statement, ':toDate', $toDate);
    oci_bind_by_name($statement, ':color', $color);

    $tripId = 0;
    oci_bind_by_name($statement, ':tripId', $tripId, -1, SQLT_INT);


    $success = oci_execute($statement);
    oci_commit($this->conn);
    oci_free_statement($statement);
 if ($success){
    $sql = "INSERT INTO Budget(TripId, Budgetsoll, Currency) VALUES (:tripId, :budget,:currency)";
    $statement = oci_parse($this->conn, $sql);

    oci_bind_by_name($statement, ':tripId', $tripId);
    oci_bind_by_name($statement, ':budget', $budget);
    oci_bind_by_name($statement, ':currency', $currency);


    $success = oci_execute($statement);
    oci_commit($this->conn);
    oci_free_statement($statement);
}
    if ($success && $sharedTrip) {
        $sql = "INSERT INTO shared_Trip(tripid, description, suitableFor) VALUES (:tripId, :description, :suitableFor)";
        $statement = oci_parse($this->conn, $sql);

        oci_bind_by_name($statement, ':tripId', $tripId);
        oci_bind_by_name($statement, ':description', $description);
        oci_bind_by_name($statement, ':suitableFor', $suitableFor);

        $success = oci_execute($statement);
        oci_commit($this->conn);
        oci_free_statement($statement);
    }

    return $success;
}
public function getTrip($tripId){
    $sql = "SELECT * FROM tripview WHERE tripId = :tripId";
    $statement = oci_parse($this->conn, $sql);

    oci_bind_by_name($statement, ":tripId", $tripId);

    oci_execute($statement);

    $res = oci_fetch_assoc($statement);

    oci_free_statement($statement);

    return $res; 

}
public function getSharedTrip($tripId){
    $sql = "SELECT * FROM sharedTripview WHERE tripId = :tripId";
    $statement = oci_parse($this->conn, $sql);

    oci_bind_by_name($statement, ":tripId", $tripId);

    oci_execute($statement);

    $res = oci_fetch_assoc($statement);

    oci_free_statement($statement);

    return $res; 

}
public function deleteTrip($tripId)
{
  try {
        $sql = "DELETE FROM trip WHERE tripId = :tripId";
        $statement = oci_parse($this->conn, $sql);

        oci_bind_by_name($statement, ':tripId', $tripId);

        $success = oci_execute($statement);

        oci_free_statement($statement);
        return $success;
    } catch (Exception $e) {
        error_log('Error in deleteTrip: ' . $e->getMessage());
        return false;
    }
}

public function deleteSave($userId, $sharedTripId)
{
  try {
        $sql = "DELETE FROM tripsave WHERE sharedTripId = :sharedTripId AND Personid=:userId ";
        $statement = oci_parse($this->conn, $sql);

        oci_bind_by_name($statement, ':sharedTripId', $sharedTripId);
        oci_bind_by_name($statement, ':userId', $userId);

        $success = oci_execute($statement);

        oci_free_statement($statement);
        return $success;
    } catch (Exception $e) {
        error_log('Error in deleteSave: ' . $e->getMessage());
        return false;
    }
}
public function getAllDestinations(){
$sql = "SELECT * FROM destinationdropdown";

    $statement = oci_parse($this->conn, $sql);


    oci_execute($statement);

    oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);

    oci_free_statement($statement);

    return $res;
}

   
    public function deletePerson($person_id)
    {
        $errorcode = 0;
   
        $sql = 'delete from person where personid=:person_id';
        $statement = oci_parse($this->conn, $sql);
   
        oci_bind_by_name($statement, ':person_id', $person_id);

        $success= oci_execute($statement);
   
        oci_free_statement($statement);
   
        return $success;
    }


}