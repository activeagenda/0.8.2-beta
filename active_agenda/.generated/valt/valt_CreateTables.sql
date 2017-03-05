CREATE TABLE `valt` (
   ThreatID int unsigned auto_increment not null,
   SharedValueID int,
   ThreatTitle varchar(128),
   ThreatDesc text,
   SurveyValidation bool default 0,
   SurveyID int unsigned not null,
   ThreatAbateStatusID int,
   ResolutionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ThreatID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `valt_l` (
   _RecordID int unsigned not null auto_increment,
   ThreatID int unsigned  not null,
   SharedValueID int,
   ThreatTitle varchar(128),
   ThreatDesc text,
   SurveyValidation bool default 0,
   SurveyID int unsigned not null,
   ThreatAbateStatusID int,
   ResolutionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ThreatID
   )
) TYPE=InnoDB;
