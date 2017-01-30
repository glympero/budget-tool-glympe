budget-tool-glympe
==================

A Symfony project created on January 29, 2017, 5:56 pm.
# budget-tool-glympe
Brief
A three page authenticated application written in php using the Symfony framework.
Only the login page should be publicly accessible.
There should be migration scripts generated to build the database.
Page 1
A login page.
Page 2
A data entry page creating a new budget with the following attributes:
1.  Title (mandatory);
2.  Value (mandatory numeric (13, 4));
3.  Start Date (mandatory date);
4.  End Date (mandatory date).
On failed validation return to the same page with relevant error messages.
On success move to page two with success message.
Page 3
A data entry page creating a new initiative with the following attributes:
1.  Title (mandatory);
2.  Value (mandatory numeric (13, 4));
Below the data entry form there should be a table of current initiatives (title, value).
It is expected that there will be a method on the budget called budgetExceeded which returns a Boolean describing if the sum of initiative values exceeds the budget value.
There should be a single unit test in place which:
1.  Creates a new budget with value 1000;
2.  Adds an initiative with value 200;
3.  Asserts the result is false;
4.  Adds a second initiative with value 800;
5.  Asserts the result is false;
6.  Adds a third  initiative with value 200;
7.  Asserts the result is true.
