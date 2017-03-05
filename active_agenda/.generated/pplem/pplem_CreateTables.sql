CREATE TABLE `pplem` (
   EmergContactID int unsigned auto_increment not null,
   _ModDate datetime not null,
   EmployeePersonID int unsigned,
   ContactLastName varchar(50),
   ContactFirstName varchar(50),
   ContactMiddleName varchar(50),
   RelationshipID int unsigned,
   ContactAddress1 varchar(128),
   ContactAddress2 varchar(128),
   ContactStateID int,
   ContactCity varchar(50),
   ContactPostalCode varchar(50),
   ContactPhone varchar(50),
   ContactAltPhone varchar(50),
   ContactFax varchar(50),
   ContactEmail varchar(128),
   ContactPriority int,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EmergContactID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pplem_l` (
   _RecordID int unsigned not null auto_increment,
   EmergContactID int unsigned  not null,
   _ModDate datetime not null,
   EmployeePersonID int unsigned,
   ContactLastName varchar(50),
   ContactFirstName varchar(50),
   ContactMiddleName varchar(50),
   RelationshipID int unsigned,
   ContactAddress1 varchar(128),
   ContactAddress2 varchar(128),
   ContactStateID int,
   ContactCity varchar(50),
   ContactPostalCode varchar(50),
   ContactPhone varchar(50),
   ContactAltPhone varchar(50),
   ContactFax varchar(50),
   ContactEmail varchar(128),
   ContactPriority int,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EmergContactID
   )
) TYPE=InnoDB;
