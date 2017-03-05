CREATE TABLE `gap` (
   GapAnalysisID int unsigned auto_increment not null,
   ModuleID varchar(5),
   AnalysisTypeID int unsigned,
   AnalysisItem varchar(128),
   AnalysisDescription text,
   KeyResources text,
   KeyLocations text,
   KeyRisks text,
   HoursEstimate float,
   MethodsandBenefits text,
   AdvancedCopy bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      GapAnalysisID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `gap_l` (
   _RecordID int unsigned not null auto_increment,
   GapAnalysisID int unsigned  not null,
   ModuleID varchar(5),
   AnalysisTypeID int unsigned,
   AnalysisItem varchar(128),
   AnalysisDescription text,
   KeyResources text,
   KeyLocations text,
   KeyRisks text,
   HoursEstimate float,
   MethodsandBenefits text,
   AdvancedCopy bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      GapAnalysisID
   )
) TYPE=InnoDB;
