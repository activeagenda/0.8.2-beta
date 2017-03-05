CREATE TABLE `surq` (
   QuestionID int not null auto_increment,
   SurveyID int not null,
   SurveyQuestion varchar(255),
   QuestionGroupID int,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      QuestionID
   ),
   INDEX surq_SurveyID (
      SurveyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surq_l` (
   _RecordID int unsigned not null auto_increment,
   QuestionID int not null ,
   SurveyID int not null,
   SurveyQuestion varchar(255),
   QuestionGroupID int,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      QuestionID
   ),
   INDEX surq_l_SurveyID (
      SurveyID
   )
) TYPE=InnoDB;
