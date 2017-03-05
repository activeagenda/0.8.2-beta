CREATE TABLE `lpa` (
   LossEmpPracticeID int unsigned auto_increment not null,
   OrganizationID int,
   ShiftID int,
   PracticeTypeID int,
   Assertions text,
   DesiredOutcome text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossEmpPracticeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lpa_l` (
   _RecordID int unsigned not null auto_increment,
   LossEmpPracticeID int unsigned  not null,
   OrganizationID int,
   ShiftID int,
   PracticeTypeID int,
   Assertions text,
   DesiredOutcome text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossEmpPracticeID
   )
) TYPE=InnoDB;
