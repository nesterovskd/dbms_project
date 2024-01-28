--person
CREATE TABLE Person (
    LastName varchar(32),
    FirstName varchar(32),
    Email varchar(32) UNIQUE,
    Password varchar(32),
    PersonId INTEGER CONSTRAINT pk_person PRIMARY KEY
);

CREATE SEQUENCE seq_person
  START WITH 1
  MINVALUE 1
  INCREMENT BY 1
  CACHE 1000;

--end Person

--friend
CREATE TABLE is_friend_of (
  SenderId Integer NOT NULL,
  RecieverId Integer not null,
  Status CHAR(1) DEFAULT 'P',
  FOREIGN KEY (SenderId) REFERENCES Person(PersonId),
  FOREIGN KEY (RecieverId) REFERENCES  Person(PersonId),
  CONSTRAINT pk_friend PRIMARY KEY (SenderId, RecieverId)
);

    
--end Friend

CREATE TABLE Destination (
  DestinationID INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  Country VARCHAR(32),
  City VARCHAR(32)
);



CREATE TABLE Trip (
  TripID INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  DateFrom Date,
  DateTo Date ,
  Color CHAR(7), --hexadecimal

  PersonID INTEGER NOT NULL,
  DestinationID INTEGER NOT NULL,
  constraint fk_trip_person FOREIGN KEY (PersonID) REFERENCES Person(PersonID) on delete cascade,
  constraint fk_trip_destination FOREIGN KEY (DestinationID) REFERENCES Destination(DestinationID) on delete set null,
  CONSTRAINT CHK_DURATION CHECK (Datefrom<=dateTo)
);


--budget
CREATE TABLE Budget (
  BudgetID integer not null,
  BudgetSOLL decimal(8,2),
  TripID integer not null,
  Currency CHAR(3) not null,
  CONSTRAINT fk_budget_trip FOREIGN KEY (TripID) REFERENCES Trip(TripID) ON DELETE CASCADE,
  CONSTRAINT pk_Budget PRIMARY KEY (BudgetID)
);

CREATE SEQUENCE seq_budget
  START WITH 1
  MINVALUE 1
  INCREMENT BY 1
  CACHE 1000;

--end Budget


CREATE TABLE Category (
  CategoryID INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  Description VARCHAR(64) not null,
  Icon VARCHAR(32)
);


--expense
CREATE TABLE EXPENSE(
  ExpenseID INTEGER NOT NULL,
  BudgetID INTEGER NOT NULL,
  CategoryID INTEGER NOT NULL, 
  Description VARCHAR(100),
  Price DECIMAL(8,2) default 0,
  CONSTRAINT fk_expense_category FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID) ON DELETE Set null,
  CONSTRAINT fk_expense_budget FOREIGN KEY (BudgetID) REFERENCES Budget(BudgetID) ON DELETE CASCADE,
  CONSTRAINT pk_expense PRIMARY KEY (BudgetID, ExpenseID)
);

--end Expense

--sharedtrip
CREATE TABLE Shared_Trip (
  SharedTripID integer NOT NULL,
  TripID integer NOT NULL unique,
  Description VARCHAR(1000),
  SuitableFor varchar(30),
  CONSTRAINT pk_Shared_Trip PRIMARY KEY (SharedTripID),
  CONSTRAINT fk_trip_sharedtrip FOREIGN KEY (TripID) REFERENCES Trip(TripID)
);

CREATE SEQUENCE seq_Shared_Trip
  START WITH 1
  MINVALUE 1
  INCREMENT BY 1
  CACHE 1000;


--end SharedTrip

CREATE TABLE TripSave (
  SharedTripID integer NOT NULL,
  PersonId integer NOT NULL,
  CONSTRAINT pk_TripSave PRIMARY KEY (SharedTripID,PersonID),
  CONSTRAINT fk_person_sharedtrip FOREIGN KEY (PersonId) REFERENCES Person(PersonId),
  CONSTRAINT fk_sharedtrip_person FOREIGN KEY (SharedTripID) REFERENCES Shared_Trip(SharedTripID)
);

CREATE TABLE TripComment (
  CommentId INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  SharedTripID integer NOT NULL,
  PersonId integer NOT NULL,
  Text VARCHAR(1000) Not Null,
  CONSTRAINT fk_comment_person FOREIGN KEY (PersonId) REFERENCES Person(PersonId),
  CONSTRAINT fk_comment_sharedtrip FOREIGN KEY (SharedTripID) REFERENCES Shared_Trip(SharedTripID)
);

--  triggers
create or replace NONEDITIONABLE TRIGGER trg_generate_expenseid
BEFORE INSERT ON EXPENSE
FOR EACH ROW
DECLARE
  max_id INTEGER;
BEGIN
  SELECT COALESCE(MAX(ExpenseID), 0) INTO max_id FROM EXPENSE WHERE BudgetID = :NEW.BudgetID;
  :NEW.ExpenseID := max_id + 1;
END;


create or replace NONEDITIONABLE TRIGGER tri_budget
  BEFORE INSERT
  ON Budget
  FOR EACH ROW
  BEGIN
    SELECT seq_budget.nextval
    INTO :new.BudgetID
    FROM dual;
  END;



create or replace NONEDITIONABLE TRIGGER tri_friend
BEFORE INSERT ON is_friend_of
FOR EACH ROW
DECLARE
    v_count NUMBER;
    v_updated_rows NUMBER;
BEGIN
    SELECT COUNT(*)
    INTO v_count
    FROM is_friend_of
    WHERE (SenderId = :NEW.RecieverId AND RecieverId = :NEW.SenderId AND status = 'A');

    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'Diese Freundschaft existiert bereits.');
    END IF;

    UPDATE is_friend_of
    SET status = 'A'
    WHERE SenderId = :NEW.RecieverId 
      AND RecieverId = :NEW.SenderId 
      AND status = 'P'
      RETURNING COUNT(*) INTO v_updated_rows;
    if v_updated_rows>0 then
        :NEW.status := 'A';
     end if;
END;


create or replace NONEDITIONABLE TRIGGER tri_person
  BEFORE INSERT
  ON person
  FOR EACH ROW
  BEGIN
    SELECT seq_person.nextval
    INTO :new.PersonId
    FROM dual;
  END;

  create or replace NONEDITIONABLE TRIGGER tri_Shared_Trip
  BEFORE INSERT
  ON Shared_Trip
  FOR EACH ROW
  BEGIN
    SELECT seq_Shared_Trip.nextval
    INTO :new.SharedTripID
    FROM dual;
  END;

  --end trigger

  -- views
CREATE OR REPLACE VIEW BUDGETIST AS
select b."BUDGETID",b."BUDGETSOLL",b."TRIPID", Sum(e.price) as BudgetIST, b.currency
from budget b
left join expense e on b.budgetID = e.budgetid
GROUP BY b.BudgetID, b.BudgetSOLL, b.tripid, b.currency

CREATE OR REPLACE VIEW DESTINATIONDROPDOWN AS
SELECT 
   d.destinationid,  (d.city||', '|| d.country) as destination
FROM 
    destination d
    order by country, city, destinationid


CREATE OR REPLACE VIEW EXPENSEVIEW AS
select e.price, e.budgetid, e.description as Expence, c.description as Category, c.icon, b.currency
from expense e
    inner join Category c on e.categoryid=c.categoryid
    inner join budget b on e.budgetId=b.budgetid


CREATE OR REPLACE VIEW SHAREDTRIPVIEW AS
select p.personid, b.budgetist, b.currency, (d.city||', '|| d.country) as destination, t.datefrom as DateFrom, t.dateto as DateTo, t.tripId, st.suitablefor, st.description, st.sharedtripid
,t.color, (SELECT COUNT(*) FROM tripsave WHERE sharedtripid = st.sharedtripid) AS times_saved from budgetist b, trip t, shared_trip st, destination d, person p
where t.destinationid=d.destinationid and t.tripId=b.tripId and t.tripId=st.tripId and p.personId=t.personid


CREATE OR REPLACE VIEW TRIPCOMMENTVIEW AS
SELECT c.text, p.lastname, p.firstname, c.commentId, c.sharedTripId
FROM tripcomment c, person p
WHERE c.personId = p.personId


CREATE OR REPLACE VIEW TRIPVIEW AS
select p.personid, b.budgetist, b.budgetsoll, b.currency, (d.city||', '|| d.country) as destination, d.destinationid, t.datefrom as DateFrom, t.dateto as DateTo, t.tripId
,t.color from budgetist b, trip t, destination d, person p
where t.destinationid=d.destinationid and t.tripId=b.tripId and p.personId=t.personid

  -- end views