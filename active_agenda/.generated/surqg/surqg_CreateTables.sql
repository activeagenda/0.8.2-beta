CREATE TABLE `surqg` (
   QuestionGroupID int not null auto_increment,
   SurveyID int not null,
   Name varchar(50),
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      QuestionGroupID
   ),
   INDEX surqg_SurveyID (
      SurveyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surqg_l` (
   _RecordID int unsigned not null auto_increment,
   QuestionGroupID int not null ,
   SurveyID int not null,
   Name varchar(50),
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      QuestionGroupID
   ),
   INDEX surqg_l_SurveyID (
      SurveyID
   )
) TYPE=InnoDB;
