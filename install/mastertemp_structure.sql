CREATE TABLE `agrt_tmp` (
   AgreementTypeID int unsigned auto_increment not null,
   AgreementCategoryID int,
   AgreementTypeTitle varchar(128),
   AgreementTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AgreementTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `bcqt_tmp` (
   BusinessConsequenceTypeID int unsigned auto_increment not null,
   ConsequenceCategoryID int,
   ConsequenceType varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessConsequenceTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `budat_tmp` (
   AccountTypeID int unsigned auto_increment not null,
   AccountCategoryID int,
   AccountTypeTitle varchar(128),
   AccountDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AccountTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `buit_tmp` (
   BuildingTypeID int unsigned auto_increment not null,
   BuildingCategoryID int,
   BuildingTypeTitle varchar(128),
   BuildingTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BuildingTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `chmhc_tmp` (
   ChemicalHazardClassificationID int unsigned auto_increment not null,
   ClassificationTypeID int,
   ClassificationTitle varchar(128),
   ClassificationNumber varchar(10),
   ClassificationDesc text,
   ClassificationOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalHazardClassificationID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `chmph_tmp` (
   ChemicalPhraseID int unsigned auto_increment not null,
   ChemicalPhraseTypeID int unsigned not null default 0,
   ChemicalPhraseNumber varchar(10),
   Phrase text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalPhraseID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `chmt_tmp` (
   ChemicalTypeID int unsigned auto_increment not null,
   ChemicalCategoryID int,
   ChemicalTypeName varchar(128),
   ChemicalTypeDesc text,
   ChemicalTypeColor varchar(128),
   ApplyColorToLabel bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `cod_tmp` (
   CodeID int not null,
   CodeTypeID int not null,
   SortOrder int,
   Value varchar(25),
   Description varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CodeTypeID,
      CodeID
   ),
   INDEX cod_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `codt_tmp` (
   CodeTypeID int not null auto_increment,
   Name varchar(128),
   Description varchar(255),
   UseValue bool default 0 not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CodeTypeID
   ),
   INDEX codt_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `corst_tmp` (
   CorrectiveSituationTypeID int unsigned auto_increment not null,
   PolicyOrganizationID int unsigned not null,
   CorrectiveSituationCategoryID int,
   CorrectiveSituationTitle varchar(128),
   CorrectiveSituationDescription text,
   WorkRuleNo varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CorrectiveSituationTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `cost_tmp` (
   CostTypeID int auto_increment not null,
   CostCategoryID int,
   CostTitle varchar(128),
   CostTypeDesc text,
   Expenditure bool default 1,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CostTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `cspt_tmp` (
   ConfinedSpaceTypeID int unsigned auto_increment not null,
   ConfinedSpaceCategoryID int,
   ConfinedSpaceTitle varchar(128),
   ConfinedSpaceDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ConfinedSpaceTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `cti_tmp` (
   CountyID int unsigned auto_increment not null,
   StateID int unsigned,
   CountyName varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CountyID
   ),
   INDEX cti_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `ctr_tmp` (
   CountryID int unsigned auto_increment not null,
   CountryName varchar(50),
   NativeCountryName varchar(50),
   CountryAbbreviation varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CountryID
   ),
   INDEX ctr_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `doct_tmp` (
   DocumentTypeID int unsigned auto_increment not null,
   DocumentCategoryID int,
   DocumentTypeName varchar(128),
   DocumentTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `eqpt_tmp` (
   EquipmentTypeID int unsigned auto_increment not null,
   EquipmentCategoryID int,
   EquipmentTypeTitle varchar(128),
   EquipmentTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `ewkt_tmp` (
   ElevatedWorkTypeID int unsigned auto_increment not null,
   ElevatedWorkCategoryID int,
   ElevatedWorkTitle varchar(128),
   ElevatedWorkDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ElevatedWorkTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `filt_tmp` (
   FileTypeID int unsigned auto_increment not null,
   FileCategoryID int,
   FileTypeTitle varchar(128),
   FileTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FileTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `gap_tmp` (
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
CREATE TABLE `glo_tmp` (
   GlossaryID int unsigned auto_increment not null,
   OrganizationID int,
   GlossaryItem varchar(128),
   Definition text,
   GlossaryURL varchar(128),
   Protected bool,
   Display bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      GlossaryID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `hazt_tmp` (
   HazardTypeID int unsigned auto_increment not null,
   HazCategoryID int,
   HazType varchar(128),
   HazTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HazardTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `hwkt_tmp` (
   HotWorkTypeID int unsigned auto_increment not null,
   HotWorkCategoryID int,
   HotWorkTitle varchar(128),
   HotWorkDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HotWorkTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `inst_tmp` (
   InsuranceTypeID int unsigned auto_increment not null,
   InsuranceCategoryID int,
   InsuranceTypeTitle varchar(128),
   InsuranceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InsuranceTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `lbrt_tmp` (
   LineBreakTypeID int unsigned auto_increment not null,
   LineBreakCategoryID int,
   LineBreakTitle varchar(128),
   LineBreakDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LineBreakTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `lcot_tmp` (
   LossCostTypeID int unsigned auto_increment not null,
   CostCategoryID int,
   CostType varchar(128),
   CostCode varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossCostTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `linbp_tmp` (
   BodyPartID int unsigned auto_increment not null,
   BodyPartTypeID int,
   BodyPartTitle varchar(128),
   BodyPartDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BodyPartID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `linbt_tmp` (
   BodyPartTypeID int unsigned auto_increment not null,
   BodyPartCategoryID int,
   PartType varchar(128),
   BodyPartTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BodyPartTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `linc_tmp` (
   InjuryIllnessCategoryID int unsigned auto_increment not null,
   InjIllCategoryID int,
   CategoryDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryIllnessCategoryID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `linet_tmp` (
   InjuryExposureTypeID int unsigned auto_increment not null,
   InjuryExposureCategoryID int,
   ExposureType varchar(128),
   InjuryExposureTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryExposureTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `linex_tmp` (
   InjuryExposureID int unsigned auto_increment not null,
   InjuryExposureTypeID int,
   InjuryExposureTitle varchar(128),
   InjuryExposureDivision varchar(10),
   InjuryExposureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryExposureID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `linna_tmp` (
   InjuryNatureID int unsigned auto_increment not null,
   InjuryNatureTypeID int,
   InjuryNatureTitle varchar(128),
   InjuryNatureDivision varchar(10),
   InjuryNatureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryNatureID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `linnt_tmp` (
   InjuryNatureTypeID int unsigned auto_increment not null,
   InjuryNatureCategoryID int,
   NatureType varchar(128),
   InjuryNatureTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryNatureTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `linsc_tmp` (
   InjurySourceID int unsigned auto_increment not null,
   InjurySourceTypeID int,
   InjurySourceTitle varchar(128),
   InjurySourceDivision varchar(10),
   InjurySourceDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjurySourceID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `linst_tmp` (
   InjurySourceTypeID int unsigned auto_increment not null,
   InjurySourceCategoryID int,
   SourceType varchar(128),
   InjurySourceTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjurySourceTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `lint_tmp` (
   InjuryIllnessTypeID int unsigned auto_increment not null,
   InjIllTypeID int,
   InjuryIllnessTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryIllnessTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `litii_tmp` (
   ITIncidentIndicatorID int unsigned auto_increment not null,
   IndicatorCategoryID int,
   IndicatorType varchar(128),
   IndicatorDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ITIncidentIndicatorID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `lstt_tmp` (
   LossStatusTypeID int unsigned auto_increment not null,
   LossStatusCategoryID int,
   LossStatus varchar(128),
   LossStatusDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossStatusTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `medet_tmp` (
   MedicalExamTypeID int unsigned auto_increment not null,
   ExamCategoryID int,
   ExamType varchar(128),
   ExamTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalExamTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `mod_tmp` (
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
CREATE TABLE `modch_tmp` (
   ModuleChartID int unsigned auto_increment not null,
   ModuleID varchar(5),
   Name varchar(10),
   Title varchar(128),
   Type varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleChartID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `naic_tmp` (
   IndustryCodeID int unsigned not null,
   IndustrySectorID int unsigned not null,
   IndustrySubSectorID int unsigned not null,
   IndustryGroupID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustryCodeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `naig_tmp` (
   IndustryGroupID int unsigned not null,
   IndustrySectorID int unsigned not null,
   IndustrySubSectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustryGroupID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `nais_tmp` (
   IndustrySectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustrySectorID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `naiss_tmp` (
   IndustrySubSectorID int unsigned not null,
   IndustrySectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustrySubSectorID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `opdpt_tmp` (
   ProductServiceTypeID int unsigned auto_increment not null,
   ProductorServiceID int,
   ProductServiceCategoryID int,
   ProductServiceTypeTitle varchar(128),
   ProductServiceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ProductServiceTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `oppt_tmp` (
   PermitTypeID int unsigned auto_increment not null,
   PermitCategoryID int,
   PermitTypeName varchar(128),
   PermitTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `orglt_tmp` (
   LocationTypeID int unsigned auto_increment not null,
   LocationCategoryID int,
   LocationTypeTitle varchar(128),
   LocationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocationTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `orgt_tmp` (
   OrganizationTypeID int unsigned auto_increment not null,
   OrganizationCategoryID int,
   OrganizationTypeTitle varchar(128),
   OrganizationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OrganizationTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `orgtt_tmp` (
   JobTitleTypeID int unsigned auto_increment not null,
   JobTitleCategoryID int,
   JobTitleTypeTitle varchar(128),
   JobTitleTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobTitleTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `orgwu_tmp` (
   WorkUnavailabilityTypeID int unsigned auto_increment not null,
   UnavailabilityCategoryID int,
   UnavailabilityType varchar(128),
   UnavailabilityDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WorkUnavailabilityTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `pplat_tmp` (
   AvailabilityModificationTypeID int unsigned auto_increment not null,
   ModificationCategoryID int,
   ModificationType varchar(128),
   ModificationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AvailabilityModificationTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `prtt_tmp` (
   ParticipationTypeID int auto_increment not null,
   ParticipationPurposeID int,
   ParticipationTitle varchar(128),
   ParticipationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ParticipationTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `pubt_tmp` (
   PublicityTypeID int unsigned auto_increment not null,
   PublicityExposureCategoryID int,
   PublicityExposureType varchar(128),
   PublicityExposureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PublicityTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `rskl_tmp` (
   LikelihoodID int unsigned auto_increment not null,
   RiskLikelihoodTerm varchar(50),
   RiskLikelihood varchar(128),
   LikelihoodValue int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LikelihoodID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `rsks_tmp` (
   SeverityID int unsigned auto_increment not null,
   RiskSeverityTerm varchar(50),
   RiskSeverity varchar(128),
   SeverityLowCost decimal(12,4),
   SeverityHighCost decimal(12,4),
   SeverityValue int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SeverityID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `rskx_tmp` (
   RiskIndexID int unsigned auto_increment not null,
   IndexValue int,
   RiskRecommendation varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RiskIndexID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `rtct_tmp` (
   CausationTypeID int unsigned auto_increment not null,
   CauseCategoryID int,
   CauseType varchar(128),
   CauseTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CausationTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `sitt_tmp` (
   SituationTypeID int unsigned auto_increment not null,
   SituationCategoryID int,
   SituationTitle varchar(128),
   SituationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `spt_tmp` (
   SupportDocumentID int unsigned auto_increment not null,
   ModuleID varchar(5),
   LocalDocumentationStatusID int,
   WikiArticle varchar(128),
   WikiArticleStatusID int,
   WikiGuide varchar(128),
   WikiGuideStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SupportDocumentID
   ),
   INDEX spt_ModuleID (
      ModuleID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `sta_tmp` (
   StateID int unsigned auto_increment not null,
   CountryID int unsigned,
   StateName varchar(50),
   StateAbbreviation varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      StateID
   ),
   INDEX sta_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `supt_tmp` (
   SupplierTypeID int unsigned auto_increment not null,
   SupplierCategoryID int,
   SupplierTypeTitle varchar(128),
   SupplierTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SupplierTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `sursv_tmp` (
   SurveyScaleValueID int unsigned auto_increment not null,
   SurveyScaleTitleID int,
   ScaleValue float,
   ScaleValueDesc varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SurveyScaleValueID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `sysct_tmp` (
   SysComponentTypeID int unsigned auto_increment not null,
   ComponentCategoryID int,
   ComponentType varchar(128),
   ComponentDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SysComponentTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `syst_tmp` (
   SystemTypeID int unsigned auto_increment not null,
   SystemCategoryID int,
   SystemTypeName varchar(128),
   SystemTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SystemTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `tast_tmp` (
   TaskTypeID int unsigned auto_increment not null,
   TaskCategoryID int,
   TaskTypeTitle varchar(128),
   TaskTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TaskTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `trat_tmp` (
   TrainingTypeID int auto_increment not null,
   TrainingCategoryID int,
   TrainingTypeTitle varchar(128),
   TrainingTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `uts_tmp` (
   UnitID int unsigned auto_increment not null,
   UnitCategoryID int,
   UnitName varchar(128),
   UnitValue decimal(12,2),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      UnitID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `veht_tmp` (
   VehicleTypeID int unsigned auto_increment not null,
   VehicleTypeName varchar(128),
   VehicleTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehicleTypeID
   )
) TYPE=InnoDB;
-- statement separator --
CREATE TABLE `wast_tmp` (
   WasteTypeID int unsigned auto_increment not null,
   WasteCategoryID int,
   WasteTypeName varchar(128),
   WasteTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteTypeID
   )
) TYPE=InnoDB;
-- statement separator --
