CREATE DEFINER=`ermine`@`%` FUNCTION `fn_generateSessionID`() RETURNS varchar(32) CHARSET latin1
BEGIN
	
RETURN MD5(NOW());
END