CREATE TABLE `tracl` (
   TrainingClassID int unsigned auto_increment not null,
   CourseID int unsigned not null,
   OrganizationID int,
   LocationID int unsigned not null,
   SpecialPreparation text,
   ClassStartTime datetime,
   ClassStatusID int,
   IssuesDiscussed text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingClassID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tracl_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingClassID int unsigned  not null,
   CourseID int unsigned not null,
   OrganizationID int,
   LocationID int unsigned not null,
   SpecialPreparation text,
   ClassStartTime datetime,
   ClassStatusID int,
   IssuesDiscussed text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingClassID
   )
) TYPE=InnoDB;
