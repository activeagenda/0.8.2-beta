CREATE TABLE `usrds` (
   RecordID int auto_increment not null,
   PersonID int not null,
   Title varchar(50) not null,
   Link varchar(128) not null,
   Type varchar(10) not null,
   ModuleID varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RecordID
   ),
   INDEX usrds_PersonID (
      PersonID
   ),
   INDEX usrds_PersonLink (
      PersonID,
      Link
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `usrds_l` (
   _RecordID int unsigned not null auto_increment,
   RecordID int  not null,
   PersonID int not null,
   Title varchar(50) not null,
   Link varchar(128) not null,
   Type varchar(10) not null,
   ModuleID varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RecordID
   ),
   INDEX usrds_l_PersonID (
      PersonID
   ),
   INDEX usrds_l_PersonLink (
      PersonID,
      Link
   )
) TYPE=InnoDB;
