CREATE TABLE `jan` (
   JobAnalysisID int unsigned auto_increment not null,
   OrganizationID int,
   FunctionID int,
   JobAnalysisTitle varchar(128),
   JobAnalysisDesc text,
   JobAnalysisNumber varchar(50),
   JobAnalysisStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobAnalysisID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `jan_l` (
   _RecordID int unsigned not null auto_increment,
   JobAnalysisID int unsigned  not null,
   OrganizationID int,
   FunctionID int,
   JobAnalysisTitle varchar(128),
   JobAnalysisDesc text,
   JobAnalysisNumber varchar(50),
   JobAnalysisStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobAnalysisID
   )
) TYPE=InnoDB;
