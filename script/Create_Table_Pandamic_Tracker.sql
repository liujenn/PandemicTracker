Drop Table IF EXISTS Link_Doctor_Account;
Drop Table IF EXISTS Link_Personal_Account;
Drop Table IF EXISTS Fever_Logic;
Drop Table IF EXISTS Input_Health_Status;
Drop Table IF EXISTS Risk_Level;
Drop Table IF EXISTS Input_Risk_Status;
Drop Table IF EXISTS Resident_Quarantine_Status;
Drop Table IF EXISTS Works_At;
Drop Table IF EXISTS Cases;
Drop Table IF EXISTS Medication;
Drop Table IF EXISTS Records;
Drop Table IF EXISTS Doctor;
Drop Table IF EXISTS Resident;
Drop Table IF EXISTS Medical_Center;
Drop Table IF EXISTS Main_Address;

Create Table Doctor (
    Did			INTEGER,
    Name 			Varchar(60),
    Specialization		Varchar(60),
    PRIMARY KEY (Did)
);

Create Table Main_Address (
	PostalCode	Varchar(7),
	Street		Varchar(60),
	City		Varchar(60) NOT NULL,
	PRIMARY KEY (PostalCode, Street));

Create Table Resident (
	Rid		Integer,
	Email		Varchar(60),
	HealthCare_num	Integer UNIQUE,
	FirstName	Varchar(60),
	LastName	Varchar(60),
	Phone_num	Varchar(12),
	DOB		Date,
	PostalCode	Varchar(7),
	Street		Varchar(60),
	PRIMARY KEY (Rid),
	FOREIGN KEY (PostalCode, Street) REFERENCES Main_Address (PostalCode, Street)
		    ON DELETE SET NULL
		    ON UPDATE CASCADE);

Create Table Link_Doctor_Account (
	UserName	Varchar(60),
	Password	Varchar(60),
	Did		INTEGER NOT NULL,
	PRIMARY KEY (UserName),
	FOREIGN KEY (Did) REFERENCES Doctor (Did) ON DELETE CASCADE ON UPDATE CASCADE);

Create Table Link_Personal_Account (
	UserName	Varchar(60),
	Password	Varchar(60),
	Rid		Integer NOT NULL,
	PRIMARY KEY (UserName),
	FOREIGN KEY (Rid) REFERENCES Resident (Rid)
			ON DELETE CASCADE
			ON UPDATE  CASCADE);

Create Table Fever_Logic (
	Temperature	Real NOT NULL,
	Fever_Status	Varchar(20),
	PRIMARY KEY (Temperature)
);


Create Table Input_Health_Status (
	Date	Date,
	Rid	Integer NOT NULL,
	Temperature	Real,
	FluSymptoms		Boolean,
	PRIMARY KEY (Date, Rid),
	FOREIGN KEY (Rid) REFERENCES Resident (Rid)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
	FOREIGN KEY (Temperature) REFERENCES Fever_Logic (Temperature)
		    ON DELETE CASCADE
		    ON UPDATE CASCADE
);

Create Table Risk_Level (
	ExposureToConfirmedCase	Boolean,
    ConfirmedCaseInNeighbourhood	Boolean, 
    CloseContactHasFlu_likeSymptom	Boolean,
    RiskLevel	Varchar(10),
    PRIMARY KEY (ExposureToConfirmedCase, ConfirmedCaseInNeighbourhood, CloseContactHasFlu_likeSymptom)	
);

Create Table Input_Risk_Status(
	Date	Date,
	Rid	Integer NOT NULL,
	ExposureToConfirmedCase	Boolean,
    ConfirmedCaseInNeighbourhood	Boolean, 
    CloseContactHasFlu_likeSymptom	Boolean,
    PRIMARY KEY (Date, Rid),
    FOREIGN KEY (ExposureToConfirmedCase, ConfirmedCaseInNeighbourhood, CloseContactHasFlu_likeSymptom) REFERENCES Risk_Level (ExposureToConfirmedCase, ConfirmedCaseInNeighbourhood, CloseContactHasFlu_likeSymptom) 
            ON DELETE CASCADE
			ON UPDATE CASCADE
);

Create Table Resident_Quarantine_Status (
	StartDate	Date,
	EndDate	Date,
	Status		Boolean,
	Rid		Integer NOT NULL,
	PRIMARY KEY (StartDate, EndDate, Rid),
	FOREIGN KEY (Rid) REFERENCES Resident (Rid)
			ON DELETE CASCADE
			ON UPDATE CASCADE
);

Create Table Medical_Center (
    Name 			Varchar(60),
    City 			Varchar(60),
    StreetAddress 	Varchar(60),
    PRIMARY KEY (Name, City)
);

Create Table Works_At (
    Did 		INTEGER,
    Name 		Varchar(60),
    City 		Varchar(60),
    PRIMARY KEY (Did, Name, City),
    FOREIGN KEY (Did) 	REFERENCES Doctor (Did)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    FOREIGN KEY (Name, City) REFERENCES Medical_Center(Name, City)
		    ON DELETE CASCADE
            ON UPDATE CASCADE
);

Create Table Cases (
    CaseNum 		INTEGER AUTO_INCREMENT,
    EncounterDate 	DATE ,
    RecoveryStatus 	Varchar(60),
	Notes           Varchar(5000), 
    Did 			INTEGER NOT NULL,
    RID 			INTEGER NOT NULL,
    PRIMARY KEY (CaseNum),
    FOREIGN KEY (Did) REFERENCES Doctor (Did)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    FOREIGN KEY (Rid) REFERENCES Resident (Rid)
	    	ON DELETE CASCADE
            ON UPDATE CASCADE
);

Create Table Medication(
    Name 		Varchar(60),
    Dosage 	Varchar(60),
    PRIMARY KEY (Name, Dosage)
);

Create Table Records(
    CaseNum 		INTEGER,
    Name 			Varchar(60),
    Dosage 		Varchar(60),
    PRIMARY KEY (CaseNum),
    FOREIGN KEY (CaseNum) REFERENCES Cases (CaseNum)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    FOREIGN KEY (Name, Dosage) REFERENCES Medication (Name, Dosage)
		    ON DELETE CASCADE
            ON UPDATE CASCADE
);

insert into Doctor
values(12345, 'Lucas', 'Respiratory');
insert into Doctor
values(67890, 'Dan', 'Respiratory');
insert into Doctor
values(13579, 'Jeniffer', 'Family Medicine');
insert into Doctor
values(24680, 'Maggie', 'Medical Biochemistry');
insert into Doctor
values(13927, 'Leo', 'Infectious Diseases');

insert into Main_Address
values('V6T1A1', '1101 This Street', 'Vancouver');
insert into Main_Address
values('V6T1A2', '1201 That Street', 'Richmond');
insert into Main_Address
values('V6T1A3', '1301 Another Street', 'Burnaby');
insert into Main_Address
values('V6T1A4', '1401 One Street', 'Hope');
insert into Main_Address
values('V6T1A5', '1501 Two Street', 'Vancouver');

insert into Resident
values(1, '1@j.mail', 1111, 'John', 'Doe', '6041231231', '1990-01-01', 'V6T1A1', '1101 This Street');
insert into Resident
values(2, '2@j.mail', 2222, 'Jack', 'Ma', '7781231231', '1999-02-02', 'V6T1A2', '1201 That Street');
insert into Resident
values(3, '3@j.mail', 3333, 'Samll', 'Cat', '2361231231', '2001-03-03', 'V6T1A3', '1301 Another Street');
insert into Resident
values(4, '4@j.mail', 4444, 'Big', 'Dog', '7181231231', '2011-04-04', 'V6T1A4', '1401 One Street');
insert into Resident
values(5, '5@j.mail', 5555, 'Tiny', 'Mouse', '6131231231', '2015-05-05', 'V6T1A5', '1501 Two Street');
insert into Resident
values(6, '6@j.mail', 6666, 'Little', 'Snake', '6043242421', '1975-08-18', 'V6T1A1', '1101 This Street');

insert into Link_Doctor_Account
values('ilovedoctor', '123456', 12345);
insert into Link_Doctor_Account
values('doctor123', '123123', 67890);
insert into Link_Doctor_Account
values('meowmeow', 'meow123', 13579);
insert into Link_Doctor_Account
values('blablabla', 'bla123', 24680);
insert into Link_Doctor_Account
values('hahaha', 'haha', 13927);

insert into Link_Personal_Account
values('person', '1234567', 1);
insert into Link_Personal_Account
values('resident', '9876543', 2);
insert into Link_Personal_Account
values('hello123', 'password123', 3);
insert into Link_Personal_Account
values('myname', 'qwertyu', 4);
insert into Link_Personal_Account
values('ubcstudent1', 'iloveubc', 5);

insert into Fever_Logic
values(36, 'Normal');
insert into Fever_Logic
values(36.5, 'Normal');
insert into Fever_Logic
values(37, 'Low Fever');
insert into Fever_Logic
values(37.5, 'Low Fever');
insert into Fever_Logic
values(38, 'Fever');
insert into Fever_Logic
values(38.5, 'Fever');
insert into Fever_Logic
values(39, 'High Fever');
insert into Fever_Logic
values(39.5, 'High Fever');

insert into Input_Health_Status
values('2020-01-29', 1, 37.5, 0);
insert into Input_Health_Status
values('2020-02-03', 3, 36, 0);
insert into Input_Health_Status
values('2020-03-16', 4, 38, 1);
insert into Input_Health_Status
values('2020-02-27', 2, 38.5, 1);
insert into Input_Health_Status
values('2019-11-23', 5, 39.5, 1);

-- True = 1, False = 0
insert into Risk_Level
values(0, 0, 0, 'Low');
insert into Risk_Level
values(0, 0, 1, 'Medium');
insert into Risk_Level
values(1, 0, 0, 'High');
insert into Risk_Level
values(0, 1, 0, 'Medium');
insert into Risk_Level
values(1, 1, 0, 'High');
insert into Risk_Level
values(0, 1, 1, 'Medium');
insert into Risk_Level
values(1, 0, 1, 'High');
insert into Risk_Level
values(1, 1, 1, 'High');


insert into Input_Risk_Status
values('2020-01-29', 1, 0, 0, 0);
insert into Input_Risk_Status
values('2020-02-03', 3, 0, 0, 0);
insert into Input_Risk_Status
values('2020-03-16', 4, 0, 1, 0);
insert into Input_Risk_Status
values('2020-02-27', 2, 1, 0, 0);
insert into Input_Risk_Status
values('2019-11-23', 5, 1, 1, 1);

insert into Resident_Quarantine_Status
values('2020-01-01', '2020-01-15', 0, 1);
insert into Resident_Quarantine_Status
values('2020-05-30', '2020-06-30', 1, 2);
insert into Resident_Quarantine_Status
values('2020-03-02', '2020-03-16', 0, 3);
insert into Resident_Quarantine_Status
values('2020-02-02', '2020-02-28', 0, 4);
insert into Resident_Quarantine_Status
values('2020-03-04', '2020-03-18', 0, 5);

insert into Medical_Center
values('Vancouver General Hospital', 'Vancouver', '899 W 12th Ave');
insert into Medical_Center
values('Surrey Memorial Hospital', 'Surrey', '13750 96 Ave');
insert into Medical_Center
values('Richmond Hospital', 'Richmond', '7000 Westminster Hwy');
insert into Medical_Center
values('BC Women’s Hospital', 'Vancouver', '4500 Oak St');
insert into Medical_Center
values('West 10th Medical Clinic', 'Vancouver', '4303 W 10th Ave');

insert into Works_At
values(12345, 'Vancouver General Hospital', 'Vancouver');
insert into Works_At
values(67890, 'Surrey Memorial Hospital', 'Surrey');
insert into Works_At
values(13579, 'Richmond Hospital', 'Richmond');
insert into Works_At
values(24680, 'BC Women’s Hospital', 'Vancouver');
insert into Works_At
values(13927, 'West 10th Medical Clinic', 'Vancouver');

insert into Cases
values(1, '2020-03-23', 'Positive','Take care, stay at home.' , 12345, 1);
insert into Cases
values(2, '2020-02-12', 'Positive','Follow up in ten days.' ,67890, 2);
insert into Cases
values(3, '2020-01-23', 'Recovered','Take care.' , 13579, 3);
insert into Cases
values(4, '2019-12-12', 'Negative','Good, stay safe.' , 24680, 4);
insert into Cases
values(5, '2020-01-08', 'Deceased','Too Bad.' , 12345, 6);

insert into Medication
values('', '');
insert into Medication
values('Ibuprofen', '800mg');
insert into Medication
values('Amoxicillin', '500mg');
insert into Medication
values('Acetaminophen', '700mg');
insert into Medication
values('Hydroxychloroquine', '800mg');

insert into Records
values(1, 'Ibuprofen', '800mg');
insert into Records
values(2, 'Amoxicillin', '500mg');
insert into Records
values(3,'Acetaminophen', '700mg');
insert into Records
values(4, '', '');
insert into Records
values(5, 'Hydroxychloroquine', '800mg');


