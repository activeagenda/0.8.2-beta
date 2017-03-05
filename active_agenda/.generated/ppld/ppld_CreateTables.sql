CREATE TABLE `ppld` (
   DependentID int unsigned auto_increment not null,
   EmployeePersonID int unsigned,
   LastName varchar(50),
   FirstName varchar(50),
   MiddleName varchar(50),
   RelationshipID int unsigned,
   DepGenderID int unsigned,
   DepBirthdate date,
   DepAge varchar(5),
   DepSocialSecNo varchar(25),
   Employed bool,
   SpecialFactors varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DependentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ppld_l` (
   _RecordID int unsigned not null auto_increment,
   DependentID int unsigned  not null,
   EmployeePersonID int unsigned,
   LastName varchar(50),
   FirstName varchar(50),
   MiddleName varchar(50),
   RelationshipID int unsigned,
   DepGenderID int unsigned,
   DepBirthdate date,
   DepAge varchar(5),
   DepSocialSecNo varchar(25),
   Employed bool,
   SpecialFactors varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DependentID
   )
) TYPE=InnoDB;
