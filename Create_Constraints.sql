--IMPLEMENTATION : Every time a user is deleted from the USER table, all his forms,answers,elements are deleted at the same time


ALTER TABLE FORM ADD CONSTRAINT DeleteElementsForm FOREIGN KEY(idUser) REFERENCES USER(idUser) ON DELETE CASCADE;
ALTER TABLE ELEMENT ADD CONSTRAINT DeleteElementsForm2 FOREIGN KEY(formKey) REFERENCES FORM(FormKey) ON DELETE CASCADE;
ALTER TABLE CONTENT ADD CONSTRAINT DeleteElementsForm3 FOREIGN KEY(formKey,indexElement) REFERENCES ELEMENT(FormKey,indexElement) ON DELETE CASCADE;
ALTER TABLE ANSWERLIST ADD CONSTRAINT DeleteElementsForm4 FOREIGN KEY(formKey,indexElement) REFERENCES ELEMENT(FormKey,indexElement) ON DELETE CASCADE;
ALTER TABLE ANSWERTEXT ADD CONSTRAINT DeleteElementsForm5 FOREIGN KEY(formKey,indexElement) REFERENCES ELEMENT(FormKey,indexElement) ON DELETE CASCADE;
ALTER TABLE ANSWERDATE ADD CONSTRAINT DeleteElementsForm6 FOREIGN KEY(formKey,indexElement) REFERENCES ELEMENT(FormKey,indexElement) ON DELETE CASCADE;


--IMPLEMENTATION : These constraints are non specific and just limit sql input errors

