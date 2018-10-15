DELIMITER $$
CREATE DEFINER=`ermine`@`%` PROCEDURE `proc_createPollSubTables`(IN pCode VARCHAR(6), INOUT tableName varchar(255))
BEGIN
	
	SET @t = DATE_FORMAT(CURRENT_TIMESTAMP, "%Y%m%d%H%i%s");
	SET @formatTable 	= CONCAT('PollWebAppData.'	, pCode , @t	, '_Format');
    SET @dataTable 		= CONCAT('PollWebAppData.'	, pCode	, @t	, '_Data');
    
	SET @qFormat 	= CONCAT( 'CREATE TABLE IF NOT EXISTS '	, @formatTable	, ' LIKE PollWebApp.Template_PollFormat' );
    SET @qData 		= CONCAT( 'CREATE TABLE IF NOT EXISTS '	, @dataTable	, ' LIKE PollWebApp.Template_PollData' );
    
    PREPARE stmtf FROM @qFormat;
    PREPARE stmtd FROM @qData;
    
    EXECUTE stmtf;
    EXECUTE stmtd;
    
    SELECT CONCAT(pCode , @t) INTO tableName;
    
    DEALLOCATE PREPARE stmtf;
    DEALLOCATE PREPARE stmtd;
END$$
DELIMITER ;
