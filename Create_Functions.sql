--TIPS : To use functions, it is easier to use mysqli_fetch_array and then use the result arry like this : 
--			$result=mysqli_fetch_array($query);echo "<p>".result[0]."</p>";




--IMPLEMENTATION : This function allows the user to simply obtain TextAnswers, simply input the formKey,indexElement.

DELIMITER /
CREATE FUNCTION FetchAnswerText
(p_formKey VARCHAR(40),p_indexElement INTEGER)
RETURNS VARCHAR(512)
DETERMINISTIC
BEGIN
DECLARE v_value VARCHAR(512) DEFAULT 'ERROR';
SELECT `value` INTO v_value FROM `ANSWERTEXT` NATURAL JOIN `ELEMENT` WHERE `formKey`=p_formKey AND `indexElement`=p_indexElement;
RETURN v_value;
END /
DELIMITER ;

--How to use : SELECT FetchAnswerText([formKey],[indexElement]);



--IMPLEMENTATION : This function allows the user to simply obtain a list of answers corresponding to an element

DELIMITER /
CREATE PROCEDURE FetchAnswerList
(p_formKey VARCHAR(40),p_indexElement INTEGER)
DETERMINISTIC
BEGIN
SELECT value FROM CONTENT WHERE formKey=p_formKey AND indexElement=p_indexElement AND indexContent IN(SELECT indexAnswer FROM ANSWERLIST WHERE formKey=p_formKey AND indexElement=p_indexElement);
END /
DELIMITER ;

--How to use : CALL FetchAnswerList([formKey],[indexElement]);



--IMPLEMENTATION : This function allows the user to simply obtain a date answer corresponding to an element

DELIMITER /
CREATE FUNCTION FetchAnswerDate
(p_formKey VARCHAR(40),p_indexElement INTEGER)
RETURNS DATE
DETERMINISTIC
BEGIN
DECLARE v_value DATE DEFAULT '0000-00-00';
SELECT `value` INTO v_value FROM `ANSWERDATE` NATURAL JOIN `ELEMENT` WHERE `formKey`=p_formKey AND `indexElement`=p_indexElement;
RETURN v_value;
END /
DELIMITER ;

--How to use : SELECT FetchAnswerDate([formKey],[indexElement]);



--IMPLEMENTATION : This function creates a new user, returns 1 if all went well.

DELIMITER /
CREATE FUNCTION CreateUser
(p_mail VARCHAR(254),p_mdpHash VARCHAR(254),p_privileges VARCHAR(15),p_surName VARCHAR(30),p_firstName VARCHAR(30),p_birthDate DATE,p_lastLogin DATE)
RETURNS TINYINT(1)
DETERMINISTIC
BEGIN
INSERT INTO USER(login,mail,mdpHash,privileges,surName,firstName,birthDate,lastLogin)
VALUES(p_login,p_mail,p_mdpHash,p_privileges,p_surName,p_firstName,p_birthDate,p_lastLogin);
RETURN 1;
END /
DELIMITER ;

--How to use : SELECT CreateUser(.....);
