CREATE TABLE `surso` (
   OptionID int auto_increment not null,
   SurveyScaleID int unsigned not null,
   Label varchar(50) not null,
   Value int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OptionID
   ),
   INDEX surso_SurveyScaleID (
      SurveyScaleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surso_l` (
   _RecordID int unsigned not null auto_increment,
   OptionID int  not null,
   SurveyScaleID int unsigned not null,
   Label varchar(50) not null,
   Value int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OptionID
   ),
   INDEX surso_l_SurveyScaleID (
      SurveyScaleID
   )
) TYPE=InnoDB;
