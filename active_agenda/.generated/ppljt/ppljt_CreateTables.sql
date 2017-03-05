CREATE TABLE `ppljt` (
   PeopleJobTitleID int unsigned auto_increment not null,
   PersonID int unsigned,
   OrgJobTitleID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PeopleJobTitleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ppljt_l` (
   _RecordID int unsigned not null auto_increment,
   PeopleJobTitleID int unsigned  not null,
   PersonID int unsigned,
   OrgJobTitleID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PeopleJobTitleID
   )
) TYPE=InnoDB;
