CREATE TABLE `cort` (
   CorrActTypeID int auto_increment not null,
   ActionOrganizationID int unsigned not null,
   CorrectiveActionCategoryID int,
   CorrectiveActionTitle varchar(128),
   CorrectiveActionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CorrActTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cort_l` (
   _RecordID int unsigned not null auto_increment,
   CorrActTypeID int  not null,
   ActionOrganizationID int unsigned not null,
   CorrectiveActionCategoryID int,
   CorrectiveActionTitle varchar(128),
   CorrectiveActionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CorrActTypeID
   )
) TYPE=InnoDB;
