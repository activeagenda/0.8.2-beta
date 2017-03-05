CREATE TABLE `lpas` (
   SubjectID int unsigned auto_increment not null,
   LossEmpPracticeID int,
   OrganizationID int,
   ComplaintDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SubjectID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lpas_l` (
   _RecordID int unsigned not null auto_increment,
   SubjectID int unsigned  not null,
   LossEmpPracticeID int,
   OrganizationID int,
   ComplaintDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SubjectID
   )
) TYPE=InnoDB;
