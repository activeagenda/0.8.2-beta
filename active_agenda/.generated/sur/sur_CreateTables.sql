CREATE TABLE `sur` (
   SurveyID int unsigned auto_increment not null,
   OrganizationID int,
   SurveyName varchar(128),
   SurveyDesc varchar(255),
   SurveyScaleTitleID int,
   NoOfQuest int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SurveyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sur_l` (
   _RecordID int unsigned not null auto_increment,
   SurveyID int unsigned  not null,
   OrganizationID int,
   SurveyName varchar(128),
   SurveyDesc varchar(255),
   SurveyScaleTitleID int,
   NoOfQuest int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SurveyID
   )
) TYPE=InnoDB;
