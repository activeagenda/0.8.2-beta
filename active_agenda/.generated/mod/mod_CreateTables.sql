CREATE TABLE `mod` (
   ModuleID varchar(5) not null,
   Name varchar(128),
   ModuleDesc text,
   StandAlone bool default 0,
   SubModule bool default 0,
   Association bool default 0,
   Global bool default 0,
   Remote bool default 0,
   ModuleStatusID int unsigned,
   GlobalDiscussionAddress varchar(50),
   LocalDiscussionAddress varchar(50),
   LastParsed datetime,
   ParentModuleID varchar(5),
   OwnerField varchar(50),
   RecordDescriptionField varchar(50),
   RevisionAuthor varchar(30),
   RevisionNumber int unsigned,
   RevisionDate varchar(50),
   RecordLabelField varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mod_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleID varchar(5) not null,
   Name varchar(128),
   ModuleDesc text,
   StandAlone bool default 0,
   SubModule bool default 0,
   Association bool default 0,
   Global bool default 0,
   Remote bool default 0,
   ModuleStatusID int unsigned,
   GlobalDiscussionAddress varchar(50),
   LocalDiscussionAddress varchar(50),
   LastParsed datetime,
   ParentModuleID varchar(5),
   OwnerField varchar(50),
   RecordDescriptionField varchar(50),
   RevisionAuthor varchar(30),
   RevisionNumber int unsigned,
   RevisionDate varchar(50),
   RecordLabelField varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleID
   )
) TYPE=InnoDB;
